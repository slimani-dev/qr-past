<?php

namespace App\Events;

use App\Models\Past;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PastUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Past
     *
     * @Mixin Past
     */

    public $past;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Past $past)
    {
        $this->past = $past;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return $this->past->toArray();
    }

    public function broadcastAs(): string
    {
        return 'pasts.updated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('pasts.' . $this->past->id);
    }
}
