<?php

namespace App\Http\Controllers;

use App\Http\Services\TicketService;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripeController extends Controller
{
    protected TicketService $ticketService;
    protected EventOrganizerController $eventOrganizerController;
    protected StripeClient $stripe;

    public function __construct(TicketService $ticketService, EventOrganizerController $eventOrganizerController)
    {
        $this->middleware('auth');
        $this->ticketService = $ticketService;
        $this->eventOrganizerController = $eventOrganizerController;
        $this->stripe = new StripeClient(config('stripe.sk'));
    }

    public function session(Request $request): RedirectResponse
    {
        $eventId = $request->query('eventId');
        $amount = $request->query('amount');
        $eventName = $request->query('eventName');

        $ticket = $this->ticketService->createTicket($eventId, $amount);
        $session = $this->createStripeCheckoutSession($amount, $eventName, $eventId, $ticket->id);

        return redirect()->away($session->url);
    }

    private function createStripeCheckoutSession(int $amount, string $eventName, string $eventId, string $ticketId): Session
    {
        return $this->stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => "Ticket for event $eventName",
                        ],
                        'unit_amount' => $amount * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('events.byPassTicketShow', ['event_id' => $eventId, 'ticket_id' => $ticketId]),
            'cancel_url' => route('ticket.buy', ['event' => $eventId]),
            'metadata' => [
                'event_id' => $eventId,
                'user_id' => Auth::id()
            ],
        ]);
    }

    public function connect(Request $request): RedirectResponse
    {
        $legalId = $request->query('legal_id');

        if ($legalId) {
            session([Auth::id() . ":legal-id" => $legalId]);

            $connectUrl = $this->stripe->oauth->authorizeUrl([
                'scope' => 'read_write',
                'response_type' => 'code',
                'redirect_uri' => route('payment.stripe.connect.callback'),
                'client_id' => config('stripe.client_id')
            ]);

            return redirect()->away($connectUrl);
        }

        return redirect()->back()->with('error', 'Legal Id is required.');
    }

    public function callback(Request $request): RedirectResponse
    {
        $legalId = session(Auth::id() . ":legal-id");

        if (!$legalId) {
            return redirect()->back()->with('error', 'Legal Id is missing.');
        }

        $code = $request->input('code');

        $response = $this->stripe->oauth->token([
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        $this->eventOrganizerController->create($legalId, $response->stripe_user_id);

        session()->forget(Auth::id() . ":legal-id");

        return redirect()->away(route('events.create'));
    }

    public function transfer($amount, $accountId): void
    {
        $this->stripe->transfers->create([
            'amount' => $amount * 100,
            'currency' => 'eur',
            'destination' => $accountId,
        ]);
    }

    public function listPaymentsForEvent(Event $event, $paymentStatus = null, $userId = null): array
    {
        // Fetch all sessions created after the event
        $sessions = $this->stripe->checkout->sessions->all([
            'limit' => 100,
            'created' => ['gte' => strtotime($event->created_at)]
        ]);

        $eventPayments = [];

        // Iterate over each payment session
        foreach ($sessions->autoPagingIterator() as $payment) {
            // Check if the payment is for the specified event and meets the filter criteria
            if ($this->isPaymentForEvent($payment, $event) && $this->meetsFilterCriteria($payment, $paymentStatus, $userId)) {
                // Format and add the payment to the result array
                $eventPayments[] = $this->formatPayment($payment, $event);
            }
        }

        return $eventPayments;
    }

    public function refundPayment($paymentIntent): void
    {
        $refund = $this->stripe->refunds->create(['payment_intent' => $paymentIntent]);
    }

    // fazer reembolso de vários pagamentos, assim que um evento for cancelado -> listar todos pagamentos de um EVENT_ID e então, para cada um, fazer o reembolso
    public function refundAllPaymentsFromEvent(Event $event)
    {
        // listar todos pagamentos de um EVENT_ID
        $allPayments = $this->listPaymentsForEvent($event);

        // para cada um, fazer o reembolso
        foreach ($allPayments as $payment) {
            $this->refundPayment($payment['payment_intent']);
        }
    }

    // fazer reembolso de um pagamento, quando um usuário quiser sair do evento que pertence -> encontrar pagamento que o usuário XXX fez no evento YYY
    public function refundPaymentFromUser(Event $event, string $userId)
    {
        // encontrar pagamento que o usuário XXX fez
        $payment = $this->listPaymentsForEvent(event: $event, userId: $userId);

        if ($payment) {
            // fazer reembolso de um pagamento
            $this->refundPayment($payment[0]['payment_intent']);
        }

    }

    private function isPaymentForEvent($payment, $event): bool
    {
        // Check if the payment is associated with the given event
        return isset($payment->metadata['event_id']) && $payment->metadata['event_id'] == $event->id;
    }

    private function meetsFilterCriteria($payment, $paymentStatus, $userId): bool
    {
        // Check if the payment meets the status and user ID filter criteria
        return ($paymentStatus === null || $payment->payment_status === $paymentStatus) &&
            ($userId === null || (isset($payment->metadata['user_id']) && $payment->metadata['user_id'] == $userId));
    }

    private function formatPayment($payment, $event): array
    {
        // Format the payment information
        return [
            'session_id' => $payment->id,
            'payment_status' => $payment->payment_status, // paid, unpaid or no_payment_required
            'amount_paid' => $payment->amount_total / 100,
            'created_at' => date('Y-m-d H:i:s', $payment->created),
            'payment_intent' => $payment->payment_intent,
            'event_id' => $event->id
        ];
    }

}

/*
 * - preciso fazer reembolso de vários pagamentos, assim que um evento for cancelado -> listar todos pagamentos de um EVENT_ID e então, para cada um, fazer o reembolso
 * - listar todos pagamentos que foram pagos associados a um determinado evento -> listar tudo pelo EVENT_ID (depois do created_at) considerando STATUS = PAID
 * - listar também pagamentos negados do evento -> listar tudo que não seja STATUS = _PAID_
 * - fazer reembolso de um pagamento, quando um usuário quiser sair do evento que pertence -> encontrar pagamento que o usuário XXX fez no evento YYY
 */
