<?php

namespace App\Events;

use App\Models\Civilization;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CivilizationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Civilization $civilization;

    public function __construct(Civilization $civilization)
    {
        $this->civilization = $civilization;
        Log::info('Broadcasting CivilizationUpdated event for civilization ID: ' . $civilization->id);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('civilization-updates');
    }
}
