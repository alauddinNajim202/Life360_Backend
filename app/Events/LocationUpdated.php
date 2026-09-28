<?php

namespace App\Events;

use App\Models\UserLocation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $location;

    public $circleId;

    public function __construct(UserLocation $location, $circleId)
    {
        $this->location = $location;
        $this->circleId = $circleId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('circle.'.$this->circleId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->location->user_id,
            'latitude' => $this->location->latitude,
            'longitude' => $this->location->longitude,
            'speed' => $this->location->speed,
            'heading' => $this->location->heading,
            'updated_at' => $this->location->created_at,
        ];
    }
}
