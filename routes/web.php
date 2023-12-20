<?php

use App\Http\Controllers\Auth\ProviderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MyEventsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\EventReportController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentReportController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\EventOrganizerController;
use App\Http\Controllers\Auth\ForgotPasswordController;


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
    Route::post('/register-complete', 'registerComplete')->name('register-complete');
});

\Illuminate\Support\Facades\Auth::routes();

// Home
Route::get('/home', [EventController::class, 'index'])->name('home');
Route::view('/help', 'help')->name('help');
Route::view('/about', 'about')->name('about');

// User

Route::resource('profile', UserController::class);
Route::get('/my-events', [MyEventsController::class, 'index'])->name('my_events.index');

// Socialite
Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [ProviderController::class, 'callback']);

//Notifications
Route::get('/load-notifications', [NotificationsController::class, 'index'])->name('notifications.index');
Route::get('/load-invites', [NotificationsController::class, 'getInvites']);
Route::put('/update-read/{type}/{id}', [NotificationsController::class, 'updateRead'])->name('read-notification');
Route::post('/update-notification', [NotificationsController::class, 'received'])->name('update-notification');
Route::post('/invite/{user}/event/{event}', [NotificationsController::class, 'inviteUser']);

// Forget Password
Route::post('/password/email', [ForgotPasswordController::class, 'forgetPasswordPost'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('password.update.get');
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPasswordPost'])->name('password.update');

Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

//Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('admin/ban/user/{user}', [AdminController::class, 'banUser'])->name('ban.user');
});

// Events
Route::resource('events', EventController::class);
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('events/attendees/{event}', [EventController::class, 'showAttendees'])->name('events.attendees');
Route::get('/users/{email}', [EventController::class, 'getUsers']);

//Reports
Route::post('/events/{event}/report', [EventReportController::class, 'postReport'])->name('event-report.store');

Route::get('/admin/reports/reports-events/{event}/{reason}', [AdminController::class, 'eventReports'])->name('event-reports');
Route::get('/admin/reports/reports-comments/{user}/{reason}', [AdminController::class, 'commentReports'])->name('comment-reports');

Route::put('/events/{event}/check-all-event', [EventReportController::class, 'check_all_event']);
Route::put('/events/{user}/check-all-comment', [CommentReportController::class, 'check_all_comment']);
Route::post('events/{event}/discussion/{comment}/report', [CommentReportController::class, 'postReport'])->name('comment-report.store');
//Dashboard
Route::put('admin/reports/check-event/{reportId}', [EventReportController::class, 'checkOneReportEvent']);
Route::put('admin/reports/check-comment/{reportId}', [CommentReportController::class, 'checkOneReportComment']);
Route::put('admin/reports/check-all/{event}', [EventReportController::class, 'checkAllReport'])->name('check-all-report');

Route::put('admin/reports/ban/event/{event}', [EventReportController::class, 'banEvent'])->name('ban-event');
Route::put('admin/reports/ban/comment/{comment}', [CommentReportController::class, 'banComment']);

Route::post('/events/leave/{event_id}/{ticket_id}', [EventController::class, 'leave'])->name('events.leave');
Route::get('/events/byPass/{event_id}/{ticket_id}', [EventController::class, 'byPassTicketShow'])->name('events.byPassTicketShow');

//Universities
Route::resource('universities', UniversityController::class);

//Universities
Route::resource('universities', UniversityController::class);

//Ticket
Route::get('/events/{event}/ticket/buy', [TicketController::class, 'showBuyTicketForm'])->name('ticket.buy');
Route::post('/events/{event}/ticket/acquire', [TicketController::class, 'acquireTicket'])->name('ticket.acquire');
Route::get('/events/{event}/ticket/authorize', [TicketController::class, 'authorizeTicket'])->name('ticket.authorize');
Route::get('/events/{event}/ticket/{ticket}', [TicketController::class, 'showTicket'])
    ->name('ticket.show')
    ->middleware(['auth', 'acess.ticket']);
Route::post('/events/{event}/ticket/authenticate', [TicketController::class, 'authenticateTicket'])->name('ticket.authenticate');
Route::get('/download-ticket/{eventId}/{ticketId}', [TicketController::class, 'downloadTicket'])->name('ticket.download');



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
    Route::post('/comments/{comment}/toggle-vote/{voteType}', [CommentController::class, 'toggleVote'])->name('comment.toggleVote');
});

Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comment.update')->middleware(['auth']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy')->middleware(['auth']);

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

// event organizer routes
Route::get('/event-organizer', [EventOrganizerController::class, 'show'])
    ->name('event-organizer.show')
    ->middleware(['auth']);

Route::post('/event-organizer/{legal_id}/{stripe_account_id}', [EventOrganizerController::class, 'create'])
    ->name('event-organizer.create')
    ->middleware(['auth']);