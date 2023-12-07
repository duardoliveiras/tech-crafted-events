<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MyEventsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Home
Route::redirect('/', '/home');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

\Illuminate\Support\Facades\Auth::routes();

// Home
Route::get('/home', [EventController::class, 'index'])->name('home');
Route::view('/help', 'help')->name('help');
Route::view('/about', 'about')->name('about');

// User
Route::resource('profile', UserController::class);

Route::get('/my-events', [MyEventsController::class, 'index'])->name('my_events.index');

Route::get('/load-notifications', [NotificationsController::class, 'index'])->name('notifications.index');
Route::put('/update-read/{id}', [NotificationsController::class, 'updateRead'])->name('read-notification');

// Forget Password
Route::post('/password/email', [ForgotPasswordController::class, 'forgetPasswordPost'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('password.update.get');
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPasswordPost'])->name('password.update');

Route::get('/password/reset', function(){
    return view('auth.passwords.email');
})->name('password.request');

//Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

//Events
Route::resource('events', EventController::class);
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/byPass/{event_id}/{ticket_id}', [EventController::class, 'byPassTicketShow'])->name('events.byPassTicketShow');

//Ticket
Route::get('/events/{event}/ticket/buy', [TicketController::class, 'showBuyTicketForm'])->name('ticket.buy');
Route::post('/events/{event}/ticket/acquire', [TicketController::class, 'acquireTicket'])->name('ticket.acquire');
Route::get('/events/{event}/ticket/authorize', [TicketController::class, 'authorizeTicket'])->name('ticket.authorize');
Route::get('/events/{event}/ticket/{ticket}', [TicketController::class, 'showTicket'])
    ->name('ticket.show')
    ->middleware(['auth', 'acess.ticket']);
Route::post('/events/{event}/ticket/authenticate', [TicketController::class, 'authenticateTicket'])->name('ticket.authenticate');


//Discussion
Route::get('/events/{event}/discussion', [DiscussionController::class, 'show'])
    ->name('discussion.show')
    ->middleware(['auth', 'acess.ticket']);

// add comments
Route::post('/events/{event}/discussion/{discussion}/comment', [CommentController::class, 'store'])
    ->name('discussion.comment')
    ->middleware(['auth']);

// add vote to comment
Route::middleware(['auth'])->group(function () {
    Route::post('/comments/{comment}/toggle-vote/{voteType}', [CommentController::class, 'toggleVote'])
        ->name('comment.toggleVote');
});

// Payment Routes
Route::prefix('payment')->group(function () {
    Route::post('/session', [StripeController::class, 'session'])
        ->name('payment.session');
    Route::get('/success', [StripeController::class, 'success'])
        ->name('payment.success');
    Route::get('/connect', [StripeController::class, 'connect'])
        ->name('payment.connect');
    Route::get('/callback', [StripeController::class, 'callback'])
        ->name('payment.stripe.connect.callback');
    Route::get('/transfer', [StripeController::class, 'transfer'])
        ->name('payment.transfer');
    Route::get('/refund/{payment_intent_id}', [StripeController::class, 'refundPayment'])
        ->name('payment.refund');
});

