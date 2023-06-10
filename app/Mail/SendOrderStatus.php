<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class SendOrderStatus extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $total = number_format(
            $this->order->items
                ->map(function ($item) {
                    return $item->pivot->quantity * $item->sell_price;
                })
                ->sum(),
            2
        );

        return $this->from('noreplay@larashop.test', 'no reply')
            ->subject('order status')
            ->view('email.order_status')
            ->with([
                'order' => $this->order,
                'orderId' => $this->order->orderinfo_id,
                'orderTotal' => $total,
            ]);
    }
}
