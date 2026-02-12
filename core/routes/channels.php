<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin.dashboard', function ($user) {
    return $user->role === 'admin';
});

Broadcast::channel('business.{id}', function ($user, $id) {
    return $user->role === 'business' && $user->business && $user->business->id === (int) $id;
});
Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
