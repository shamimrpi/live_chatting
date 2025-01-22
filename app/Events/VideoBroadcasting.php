<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class VideoBroadcasting implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $signalData;

    public function __construct($signalData)
    {
        $this->signalData = $signalData;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('video-stream');
    }
}
