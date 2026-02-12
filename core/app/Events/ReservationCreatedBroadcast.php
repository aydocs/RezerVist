<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationCreatedBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;

    /**
     * Create a new event instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation->load(['user', 'business']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.dashboard'),
            new PrivateChannel('business.'.$this->reservation->business_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->reservation->id,
            'user' => $this->reservation->user->name,
            'business' => $this->reservation->business->name,
            'price' => $this->reservation->price,
            'time' => $this->reservation->created_at->diffForHumans(),
            'message' => "Yeni rezervasyon: {$this->reservation->user->name} → {$this->reservation->business->name}",
        ];
    }
}
