<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Circle;
use App\Models\CircleInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CircleController extends Controller
{
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'color_theme' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $user = auth()->user();

        if ($request->hasFile('icon')) {

            $request->icon = Helper::fileUpload(
                $request->file('icon'),
                'circle',
                getFileName($request->file('icon'))
            );
        }

        $circle = Circle::create([
            'name' => $request->name,
            'color_theme' => $request->color_theme ?? '#000000',
            'icon' => $request->icon,
            'owner_id' => $user->id,
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

        $circles = $user->circles()->get();

        return response()->json([
            'data' => $circles,
            'code' => 200,
            'message' => 'Circles fetched successfully',
            'success' => true,
        ], 200);
    }

    public function generateInvite(Request $request)
    {
        $request->validate([
            'circle_id' => 'required|exists:circles,id',
            'relation_tag' => 'required|string',
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
            'circle_id' => 'required|exists:circles,id',
            'invite_code' => 'required|string',
        ]);

        $invite = CircleInvite::where('invite_code', $request->invite_code)
            ->where('circle_id', $request->circle_id)
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
        $circle = Circle::with('members')->findOrFail($circleId);

        // Get only needed fields for members
        $members = $circle->members->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'avatar' => $member->avatar,
                'role' => $member->pivot->role,
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

    public function updateCircle(Request $request, $circleId)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'color_theme' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $circle = Circle::findOrFail($circleId);

        if ($circle->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('icon')) {
            $request->icon = Helper::fileUpload(
                $request->file('icon'),
                'circle',
                getFileName($request->file('icon'))
            );
            $circle->icon = $request->icon;
        }

        if ($request->name) $circle->name = $request->name;
        if ($request->color_theme) $circle->color_theme = $request->color_theme;

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

        if (!$circle->members()->where('user_id', $user->id)->exists()) {
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

        if ((int)$userId === auth()->id()) {
            return response()->json(['message' => 'You cannot kick yourself'], 400);
        }

        if (!$circle->members()->where('user_id', $userId)->exists()) {
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
