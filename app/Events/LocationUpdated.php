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

    public $data;
    public $user_id;
    public $latitude;
    public $longitude;
    public $speed;
    public $heading;
    public $battery_level;
    public $is_charging;
    public $updated_at;

    protected $circleId;

    public function __construct(UserLocation $location, $circleId)
    {
        $this->circleId      = $circleId;
        $this->user_id       = $location->user_id;
        $this->latitude      = $location->latitude;
        $this->longitude     = $location->longitude;
        $this->speed         = $location->speed;
        $this->heading       = $location->heading;
        $this->battery_level = $location->battery_level;
        $this->is_charging   = (bool) $location->is_charging;
        $this->updated_at    = $location->created_at;

        // MessageSendEvent এর মতো public $data
        $this->data = [
            'user_id'       => $this->user_id,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'speed'         => $this->speed,
            'heading'       => $this->heading,
            'battery_level' => $this->battery_level,
            'is_charging'   => $this->is_charging,
            'updated_at'    => $this->updated_at,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('circle.' . $this->circleId),
        ];
    }
}
