<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderInvoice;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendInvoiceListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        // dd('Send Invoice', $event->order);
        $order = $event->order;
        
        $users = User::all();
        // $user->notify( new OrderCreatedNotification($order));

        /*foreach ($users as $user) {
            $user->notify( new OrderCreatedNotification($order));
        }*/

        Notification::send($users, new OrderCreatedNotification($order));

        // Notification::route('mail', ['example@localhost', 'admin@localhost'])->notify(new OrderCreatedNotification($order));
        // ->route('nexmo', '+972594580040');

        

        // Mail::to($order->billing_email)->send(new OrderInvoice($order));

    }
}
