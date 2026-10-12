<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\CircleInviteMail;
use App\Models\Circle;
use App\Models\CircleInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CircleController extends Controller
{
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'color_theme' => 'nullable|string',
            // 'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'icon' => 'nullable|string',
            // 'status' => 'nullable|in:active,inactive',
        ]);

        $user = auth()->user();

        // if ($request->hasFile('icon')) {

        //     $request->icon = Helper::fileUpload(
        //         $request->file('icon'),
        //         'circle',
        //         getFileName($request->file('icon'))
        //     );
        // }

        $circle = Circle::create([
            'name' => $request->name,
            'color_theme' => $request->color_theme ?? '#000000',
            'icon' => $request->icon,
            'owner_id' => $user->id,
            // 'status' => $request->status ?? 'active',
        ]);

        // Owner automatically joins as admin
        $circle->members()->attach($user->id, [
            'role' => 'admin',
            'relation_tag' => 'Owner',
            'joined_at' => now(),
        ]);

        return response()->json([
            'data' => $circle,
            'code' => 201,
            'message' => 'Circle created successfully',
            'success' => true,
        ], 201);
    }

    public function list(Request $request)
    {

        $user = auth()->user();

        $query = $user->circles()->withCount('members');

        if ($request->has('status')) {
            $query->where('circles.status', $request->status);
        }

        $circles = $query->get()->map(function ($circle) use ($user) {
            $role = $circle->pivot->role ?? ($circle->owner_id === $user->id ? 'admin' : 'member');

            return [
                'id' => $circle->id,
                'name' => $circle->name,
                'color_theme' => $circle->color_theme,
                'icon' => $circle->icon,
                'owner_id' => $circle->owner_id,
                'status' => $circle->status,
                'role' => $role,
                'is_owner' => $circle->owner_id === $user->id,
                'relation_tag' => $circle->pivot->relation_tag ?? null,
                'joined_at' => $circle->pivot->joined_at ?? null,
                'total_members' => $circle->members_count,
                'created_at' => $circle->created_at,
                'updated_at' => $circle->updated_at,
            ];
        });

        return response()->json([
            'data' => $circles,
            'code' => 200,
            'message' => 'Circles fetched successfully',
            'success' => true,
        ], 200);
    }

    public function updateMultipleStatus(Request $request)
    {
        $request->validate([
            'circles' => 'nullable|array',
            'circles.*.id' => 'required_with:circles|integer|exists:circles,id',
            'circles.*.status' => 'required_with:circles|in:active,inactive',

            'circle_ids' => 'nullable|array',
            'circle_ids.*' => 'integer|exists:circles,id',
            'status' => 'nullable|required_with:circle_ids|in:active,inactive',

            'circle_id' => 'nullable|integer|exists:circles,id',
        ]);

        if (! $request->has('circles') && ! $request->has('circle_ids') && ! $request->has('circle_id')) {
            return response()->json([
                'message' => 'Please provide circles array or circle_ids with status',
                'success' => false,
                'code' => 422,
            ], 422);
        }

        $user = auth()->user();
        $updated = [];
        $unauthorized = [];

        // Format 1: Array of objects with id and status
        // e.g. [{"id": 1, "status": "active"}, {"id": 2, "status": "inactive"}]
        if ($request->has('circles') && is_array($request->circles)) {
            foreach ($request->circles as $item) {
                $circle = Circle::find($item['id']);
                if ($circle) {
                    $hasPermission = ($circle->owner_id === $user->id) ||
                        $circle->members()->where('users.id', $user->id)->where('role', 'admin')->exists();

                    if (! $hasPermission) {
                        $unauthorized[] = $item['id'];
                        continue;
                    }

                    $circle->update(['status' => $item['status']]);
                    $updated[] = $circle->fresh();
                }
            }
        }

        // Format 2: Array of circle_ids with a shared status
        // e.g. {"circle_ids": [1, 2], "status": "active"}
        if ($request->has('circle_ids') && is_array($request->circle_ids) && $request->has('status')) {
            foreach ($request->circle_ids as $id) {
                $circle = Circle::find($id);
                if ($circle) {
                    $hasPermission = ($circle->owner_id === $user->id) ||
                        $circle->members()->where('users.id', $user->id)->where('role', 'admin')->exists();

                    if (! $hasPermission) {
                        $unauthorized[] = $id;
                        continue;
                    }

                    $circle->update(['status' => $request->status]);
                    $updated[] = $circle->fresh();
                }
            }
        }

        // Format 3: Single circle_id and status
        // e.g. {"circle_id": 1, "status": "inactive"}
        if ($request->has('circle_id') && $request->has('status')) {
            $circle = Circle::find($request->circle_id);
            if ($circle) {
                $hasPermission = ($circle->owner_id === $user->id) ||
                    $circle->members()->where('users.id', $user->id)->where('role', 'admin')->exists();

                if (! $hasPermission) {
                    return response()->json([
                        'message' => 'Unauthorized: Only the owner or an admin can update this circle status',
                        'success' => false,
                        'code' => 403,
                    ], 403);
                }

                $circle->update(['status' => $request->status]);
                $updated[] = $circle->fresh();
            }
        }

        if (empty($updated) && ! empty($unauthorized)) {
            return response()->json([
                'message' => 'Unauthorized: You do not have permission to update the specified circles',
                'unauthorized_circle_ids' => $unauthorized,
                'success' => false,
                'code' => 403,
            ], 403);
        }

        return response()->json([
            'data' => $updated,
            'unauthorized_circle_ids' => ! empty($unauthorized) ? $unauthorized : null,
            'code' => 200,
            'message' => 'Circle status updated successfully',
            'success' => true,
        ], 200);
    }

    public function generateInvite(Request $request)
    {
        $request->validate([
            'circle_id' => 'required|exists:circles,id',
            'relation_tag' => 'required|string',
            'email' => 'required|email',
        ]);

        $circle = Circle::findOrFail($request->circle_id);

        // Ensure only admin/owner can generate invite
        if ($circle->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Generate unique code (e.g: SIS-49X8)
        $prefix = strtoupper(substr($request->relation_tag, 0, 3));
        $code = $prefix.'-'.strtoupper(Str::random(4));

        $invite = CircleInvite::create([
            'circle_id' => $circle->id,
            'invite_code' => $code,
            'relation_tag' => $request->relation_tag,
            'expires_at' => now()->addHours(24),
        ]);

        // Send email to the invited user
        Mail::to($request->email)->send(new CircleInviteMail($circle->name, $code, auth()->user()->name));

        return response()->json([
            'data' => $invite,
            'code' => 201,
            'message' => 'Invite code generated',
            'success' => true,
        ], 201);
    }

    public function joinCircle(Request $request)
    {
        $request->validate([
            // 'circle_id' => 'required|exists:circles,id',
            'invite_code' => 'required|string',
        ]);

        $invite = CircleInvite::where('invite_code', $request->invite_code)
            // ->where('circle_id', $request->circle_id)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $invite) {
            return response()->json(['message' => 'Invalid or expired invite code!'], 400);
        }

        $user = auth()->user();

        // Check if already in circle
        if ($invite->circle->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are already in this circle!'], 400);
        }

        // Join
        $invite->circle->members()->attach($user->id, [
            'role' => 'member',
            'relation_tag' => $invite->relation_tag,
            'joined_at' => now(),
        ]);

        $invite->update(['is_used' => true]);

        return response()->json([
            'message' => 'Successfully joined as '.$invite->relation_tag,
            'circle' => $invite->circle,
            'code' => 201,
            'success' => true,
        ], 201);
    }

    public function members($circleId)
    {
        $circle = Circle::with(['members.latestLocation'])->findOrFail($circleId);

        // Get only needed fields for members
        $members = $circle->members->map(function ($member) {
            $location = $member->latestLocation;

            return [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'avatar' => $member->avatar,
                'role' => $member->pivot->role,
                'latitude' => $location ? (float) $location->latitude : null,
                'longitude' => $location ? (float) $location->longitude : null,
                'relation_tag' => $member->pivot->relation_tag,
                'joined_at' => $member->pivot->joined_at,
            ];
        });

        return response()->json([
            'message' => 'Circle members fetched successfully',
            'data' => [
                'circle' => $circle->name,
                'members' => $members,
            ],
            'code' => 200,
            'success' => true,
        ], 200);
    }

    public function allMembersLocation(Request $request)
    {
        $user = auth()->user();

        $circlesQuery = $user->circles()->with(['members.latestLocation']);

        if ($request->has('circle_id')) {
            $circlesQuery->where('circles.id', $request->circle_id);
        }

        $circles = $circlesQuery->get();

        $allMembers = collect();

        foreach ($circles as $circle) {
            foreach ($circle->members as $member) {
                // Prevent duplicates if user is in multiple circles
                if (! $allMembers->has($member->id)) {
                    $location = $member->latestLocation;

                    $allMembers->put($member->id, [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'phone' => $member->phone,
                        'avatar' => $member->avatar,
                        'latitude' => $location ? (float) $location->latitude : null,
                        'longitude' => $location ? (float) $location->longitude : null,
                        // Get circle-specific info from first matched circle
                        'role' => $member->pivot->role,
                        'relation_tag' => $member->pivot->relation_tag,
                        'circle_id' => $circle->id,
                        'circle_name' => $circle->name,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'All members location fetched successfully',
            'data' => $allMembers->values(),
            'code' => 200,
            'success' => true,
        ], 200);
    }

    public function updateCircle(Request $request, $circleId)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'color_theme' => 'nullable|string',
            // 'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'icon' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $circle = Circle::findOrFail($circleId);

        if ($circle->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // if ($request->hasFile('icon')) {
        //     $request->icon = Helper::fileUpload(
        //         $request->file('icon'),
        //         'circle',
        //         getFileName($request->file('icon'))
        //     );
        //     $circle->icon = $request->icon;
        // }

        if ($request->icon) {
            $circle->icon = $request->icon;
        }

        if ($request->name) {
            $circle->name = $request->name;
        }
        if ($request->color_theme) {
            $circle->color_theme = $request->color_theme;
        }
        if ($request->has('status')) {
            $circle->status = $request->status;
        }

        $circle->save();

        return response()->json([
            'data' => $circle,
            'code' => 200,
            'message' => 'Circle updated successfully',
            'success' => true,
        ], 200);
    }

    public function leaveCircle($circleId)
    {
        $circle = Circle::findOrFail($circleId);
        $user = auth()->user();

        if ($circle->owner_id === $user->id) {
            return response()->json(['message' => 'Owner cannot leave the circle. Delete the circle instead.'], 400);
        }

        if (! $circle->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not a member of this circle'], 400);
        }

        $circle->members()->detach($user->id);

        return response()->json([
            'code' => 200,
            'message' => 'You have left the circle',
            'success' => true,
        ], 200);
    }

    public function kickMember($circleId, $userId)
    {
        $circle = Circle::findOrFail($circleId);

        if ($circle->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Only the owner can kick members'], 403);
        }

        if ((int) $userId === auth()->id()) {
            return response()->json(['message' => 'You cannot kick yourself'], 400);
        }

        if (! $circle->members()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'User is not a member of this circle'], 400);
        }

        $circle->members()->detach($userId);

        return response()->json([
            'code' => 200,
            'message' => 'Member kicked successfully',
            'success' => true,
        ], 200);
    }

    public function deleteCircle($circleId)
    {
        $circle = Circle::findOrFail($circleId);

        if ($circle->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Only the owner can delete the circle'], 403);
        }

        // Detach all members
        $circle->members()->detach();
        // Delete related invites
        CircleInvite::where('circle_id', $circleId)->delete();
        // Delete the circle
        $circle->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Circle deleted successfully',
            'success' => true,
        ], 200);
    }
}
