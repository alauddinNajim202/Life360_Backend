<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Circle;
use App\Models\CircleInvite;
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
            'joined_at' => now()
        ]);

        return response()->json([
            'message' => 'Circle created successfully',
            'circle' => $circle
        ], 201);
    }

    public function generateInvite(Request $request, $circleId)
    {
        $request->validate(['relation_tag' => 'required|string']);

        $circle = Circle::findOrFail($circleId);

        // Ensure only admin/owner can generate invite
        if ($circle->owner_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Generate unique code (e.g: SIS-49X8)
        $prefix = strtoupper(substr($request->relation_tag, 0, 3));
        $code = $prefix . '-' . strtoupper(Str::random(4));

        $invite = CircleInvite::create([
            'circle_id' => $circle->id,
            'invite_code' => $code,
            'relation_tag' => $request->relation_tag,
            'expires_at' => now()->addHours(24),
        ]);

        return response()->json([
            'message' => 'Invite code generated',
            'invite_code' => $invite->invite_code
        ]);
    }

    public function joinCircle(Request $request)
    {
        $request->validate(['invite_code' => 'required|string']);

        $invite = CircleInvite::where('invite_code', $request->invite_code)
                              ->where('is_used', false)
                              ->where('expires_at', '>', now())
                              ->first();

        if (!$invite) {
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
            'joined_at' => now()
        ]);

        $invite->update(['is_used' => true]);

        return response()->json([
            'message' => 'Successfully joined as ' . $invite->relation_tag,
            'circle' => $invite->circle
        ]);
    }

    public function members($circleId)
    {
        $circle = Circle::with('members')->findOrFail($circleId);

        return response()->json([
            'circle' => $circle->name,
            'members' => $circle->members
        ]);
    }
}
