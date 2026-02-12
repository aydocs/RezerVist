<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Store the user's push subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $endpoint = $request->endpoint;
        $key = $request->keys['p256dh'];
        $token = $request->keys['auth'];
        $contentEncoding = $request->getContentEncoding(); // For older versions or manual handling

        $user = $request->user();
        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true]);
    }

    /**
     * Delete the user's push subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);

        $request->user()->deletePushSubscription($request->endpoint);

        return response()->json([], 204);
    }
}
