<?php

namespace App\Events;

use App\Models\Play;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WinningCalculated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $play;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Play $play)
    {
        $this->user = $user;
        $this->play = $play;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id)
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'play_id' => $this->play->id,
            'draw_type' => $this->play->draw->type,
            'number' => $this->play->number,
            'amount' => $this->play->amount,
            'prize_amount' => $this->play->prize_amount,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
