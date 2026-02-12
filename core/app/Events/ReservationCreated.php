<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;

    /**
     * Create a new event instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
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
            new PrivateChannel('business.' . $this->reservation->business_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->reservation->id,
            'customer' => $this->reservation->user ? $this->reservation->user->name : 'Misafir',
            'time' => $this->reservation->start_time->format('H:i'),
            'date' => $this->reservation->start_time->format('d.m.Y'),
            'pax' => $this->reservation->guest_count,
            'message' => 'Yeni bir rezervasyon alındı!',
        ];
    }
}
