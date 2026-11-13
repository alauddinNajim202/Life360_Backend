<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CircleStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $circleId;
    public $status;   // 'active' | 'inactive'
    public $updatedBy; // owner user_id

    public function __construct($circleId, $status, $updatedBy)
    {
        $this->circleId  = (int) $circleId;
        $this->status    = (string) $status;
        $this->updatedBy = (int) $updatedBy;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('circle.' . $this->circleId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'circle.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'circle_id'  => $this->circleId,
            'status'     => $this->status,       // 'active' | 'inactive'
            'updated_by' => $this->updatedBy,
            'updated_at' => now()->toISOString(),
        ];
    }
}

