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

    public int    $circleId;
    public string $status;   // 'active' | 'inactive'
    public int    $updatedBy; // owner user_id

    public function __construct(int $circleId, string $status, int $updatedBy)
    {
        $this->circleId  = $circleId;
        $this->status    = $status;
        $this->updatedBy = $updatedBy;
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

