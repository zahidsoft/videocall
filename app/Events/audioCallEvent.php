<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class audioCallEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $caller_id;
    public $receiver_id;
    public $caller_peer_id;
    /**
     * Create a new event instance.
     */
    public function __construct($caller_id, $receiver_id, $caller_peer_id)
    {
        $this->caller_id = $caller_id;
        $this->receiver_id = $receiver_id;
        $this->caller_peer_id = $caller_peer_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // dd($this->caller_id);
        return [
            new PrivateChannel('audiocall.' . $this->receiver_id),
        ];
    }
}
