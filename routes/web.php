<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MyEventsController;
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
Route::view('/help', 'help')->name('help');
Route::view('/about', 'about')->name('about');

// User
Route::resource('profile', UserController::class);

Route::get('/myevents', [MyEventsController::class, 'index'])->name('myevents.index');

//Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
//Events
Route::resource('events', EventController::class);

//Ticket
Route::get('/events/{event}/ticket/buy', [TicketController::class, 'showBuyTicketForm'])->name('ticket.buy');
Route::post('/events/{event}/ticket/acquire', [TicketController::class, 'acquireTicket'])->name('ticket.acquire');

//Discussion
Route::get('/events/{event}/discussion', [DiscussionController::class, 'show'])
    ->name('discussion.show')
    ->middleware(['auth', 'acess.ticket']);

//Add comments
Route::post('/events/{event}/discussion/{discussion}/comment', [CommentController::class, 'store'])
    ->name('discussion.comment')
    ->middleware(['auth']);

//Vote in the comments
Route::post('/comments/{comment}/upvote', 'CommentController@upvote')->name('comment.upvote');
Route::post('/comments/{comment}/downvote', 'CommentController@downvote')->name('comment.downvote');





