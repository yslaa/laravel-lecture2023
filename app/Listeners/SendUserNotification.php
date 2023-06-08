<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderCreated;
use Mail;
use Barryvdh\Debugbar\Facade as DebugBar;

class SendUserNotification
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
        // dd($event);
        $orderinfoId = $event->order->orderinfo_id;
        $email = $event->email;
        $customer = $event->customer->lname . ' ' . $event->customer->lname;
        Mail::send(
            'email.user_notification',
            ['order_id' => $orderinfoId],
            function ($message) use ($email, $customer, $orderinfoId) {
                $message->from('admin@test.com', 'Admin');
                $message->to($email, $customer);
                $message->subject("Thank you !  {$customer} ");
            }
        );
    }
}
