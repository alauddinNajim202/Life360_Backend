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

    public $data;
    public $circle_id;
    public $status;
    public $updated_by;
    public $updated_at;

    protected $circleId;

    public function __construct($circleId, $status, $updatedBy)
    {
        $this->circleId   = $circleId;
        $this->circle_id  = (int) $circleId;
        $this->status     = (string) $status;
        $this->updated_by = (int) $updatedBy;
        $this->updated_at = now()->toISOString();

        // MessageSendEvent এর মতো public $data
        $this->data = [
            'circle_id'  => $this->circle_id,
            'status'     => $this->status,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('circle.' . $this->circleId),
        ];
    }
}
