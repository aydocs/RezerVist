<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Store the user's push subscription.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        try {
            $this->validate($request, [
                'endpoint' => 'required',
                'keys.auth' => 'required',
                'keys.p256dh' => 'required',
            ]);

            $endpoint = $request->endpoint;
            $key = $request->keys['p256dh'];
            $token = $request->keys['auth'];

            $user = $request->user();
            if (!$user) {
                \Log::error('Push subscription failed: User not authenticated');
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $user->updatePushSubscription($endpoint, $key, $token);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Push subscription error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete the user's push subscription.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(Request $request)
    {
        try {
            $this->validate($request, ['endpoint' => 'required']);

            $user = $request->user();
            if ($user) {
                $user->deletePushSubscription($request->endpoint);
            }

            return response()->json([], 204);
        } catch (\Exception $e) {
            \Log::error('Push unsubscription error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
