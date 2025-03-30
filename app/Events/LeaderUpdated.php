<?php

namespace App\Events;

use App\Models\Leader;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LeaderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Leader $leader;

    public function __construct(Leader $leader)
    {
        $this->leader = $leader;
        Log::info('Broadcasting LeaderUpdated event for leader ID: ' . $leader->id);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('leader-updates');
    }
}
