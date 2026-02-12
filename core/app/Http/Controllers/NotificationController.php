<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        if (request()->wantsJson()) {
            return response()->json($notifications);
        }

        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Tüm bildirimler okundu olarak işaretlendi.']);
        }

        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Bildirim okundu.', 'data' => $notification]);
        }

        // If the notification has a target URL, go there. Otherwise, go back or to index.
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return redirect()->route('notifications.index');
    }
}
