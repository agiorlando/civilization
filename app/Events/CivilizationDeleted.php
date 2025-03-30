<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CivilizationDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $civilizationId;

    public function __construct(int $civilizationId)
    {
        $this->civilizationId = $civilizationId;
        Log::info('Broadcasting CivilizationDeleted event for civilization ID: ' . $civilizationId);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('civilization-updates');
    }
}
