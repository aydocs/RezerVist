<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Show list of conversations.
     */
    public function index()
    {
        $userId = Auth::id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($msgs) use ($userId) {
                $lastMsg = $msgs->first();
                $otherUser = $lastMsg->sender_id === $userId ? $lastMsg->receiver : $lastMsg->sender;
                
                if (!$otherUser) return null;

                return [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'initials' => $otherUser->initials,
                    'profile_photo' => $otherUser->profile_photo_path ? asset('storage/' . $otherUser->profile_photo_path) : null,
                    'unread_count' => $otherUser->unread_messages_count,
                    'last_message_content' => $lastMsg->content,
                    'last_message_time' => $lastMsg->created_at->diffForHumans(),
                    'timestamp' => $lastMsg->created_at->timestamp,
                ];
            })
            ->filter()
            ->values();

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show chat with a specific user.
     */
    public function show($id)
    {
        $userId = Auth::id();
        $otherUser = User::findOrFail($id);

        $messages = Message::where(function ($q) use ($userId, $id) {
            $q->where('sender_id', $userId)->where('receiver_id', $id);
        })->orWhere(function ($q) use ($userId, $id) {
            $q->where('sender_id', $id)->where('receiver_id', $userId);
        })
        ->with('sender')
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark as read
        Message::where('sender_id', $id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.chat', compact('otherUser', 'messages'));
    }

    /**
     * Send a message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'reservation_id' => $validated['reservation_id'] ?? null,
            'content' => $validated['content'],
        ]);

        broadcast(new MessageSent($message->load('sender')))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', 'Mesaj gönderildi.');
    }
}
