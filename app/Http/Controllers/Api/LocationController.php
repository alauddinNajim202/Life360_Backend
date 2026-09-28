<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();

        // Create new location entry
        $location = UserLocation::create([
            'user_id' => $user->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed,
            'heading' => $request->heading,
        ]);

        // Get all active circles of the user
        $circles = $user->circles()->pluck('circle_id');

        // Broadcast to each circle the user belongs to
        foreach ($circles as $circleId) {
            broadcast(new LocationUpdated($location, $circleId));
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Location updated and broadcasted successfully',
            'data' => [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
            ]
        ], 200);
    }
}
