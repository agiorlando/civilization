<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LeaderDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $leaderId;

    public function __construct(int $leaderId)
    {
        $this->leaderId = $leaderId;
        Log::info('Broadcasting LeaderDeleted event for leader ID: ' . $leaderId);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('leader-updates');
    }
}
