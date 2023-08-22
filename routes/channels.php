<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('Notification.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('orders', function($user){
    if($user->type == 'super-admin' || $user->type == 'admin'){
        return true;
    }
    return false;
    // $order = Order::findOrFail($id);
    // return $user->id == $order->user_id;
});


Broadcast::channel('chat', function($user){
    return $user;
});
