<?php

namespace App\Events;

use App\Podcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class YoutubeDownloadCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $podcast;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Podcast $podcast, $userId)
    {
        $this->podcast = $podcast;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('downloads.' . $this->userId);
    }
}
