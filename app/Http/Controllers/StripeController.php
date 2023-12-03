<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\ApiErrorException;

class StripeController extends Controller
{
    public function checkout()
    {
        return view('checkout');
    }

    /**
     * @throws ApiErrorException
     */
    public function session(Request $request)
    {
        $eventId = $request->query('eventId');
        $amount = $request->query('amount');
        $eventName = $request->query('eventName');

        $event = Event::findOrFail($eventId);

        $ticket = new Ticket([
            'user_id' => Auth::id(),
            'event_id' => $eventId,
            'price_paid' => $amount
        ]);

        $event->decrement('current_tickets_qty');
        $ticket->save();

        $ticket->markTicketAsPending();

        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Ticket for event ' . $eventName,
                        ],
                        'unit_amount' => $amount * 10,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('event.details.show', ['event' => $eventId]),
            'cancel_url' => route('ticket.buy', ['event' => $eventId])
        ]);

        $ticket->markTIcketAsPaid();

        return redirect()->away($session->url);
    }

    public function checkPaymentStatus($sessionId)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            // Check the payment status
            $paymentStatus = $session->payment_status;

            if ($paymentStatus === 'paid') {
                return "Payment was successful!";
            } else {
                return "Payment failed or not yet completed.";
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return "Error retrieving session: " . $e->getMessage();
        }
    }
}
