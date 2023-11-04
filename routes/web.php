<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\CommentController;
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
Route::get('/home', [HomeController::class, 'index'])->name('home');

// User
Route::resource('profile', UserController::class);

Route::resource('events', EventController::class);

Route::get('/events/{event}/ticket/buy', [TicketController::class, 'showBuyTicketForm'])->name('ticket.buy');
Route::post('/events/{event}/ticket/acquire', [TicketController::class, 'acquireTicket'])->name('ticket.acquire');

Route::get('/events/{event}/discussion', [DiscussionController::class, 'show'])
    ->name('discussion.show')
    ->middleware(['auth', 'has.ticket']);

Route::post('/events/{event}/discussion/{discussion}/comment', [CommentController::class, 'store'])
    ->name('discussion.comment')
    ->middleware(['auth']);

Route::post('/comments/{comment}/upvote', 'CommentController@upvote')->name('comment.upvote');
Route::post('/comments/{comment}/downvote', 'CommentController@downvote')->name('comment.downvote');





