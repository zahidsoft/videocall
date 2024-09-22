<?php

use App\Http\Controllers\AudioCallController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Chat\ChatController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('user/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('user/video/call', [ChatController::class, 'video'])->name('video.chat');
    Route::post('/video/chat/request', [ChatController::class, 'sendcall'])->name('video.sendcall');

    Route::get('/newchat/index', [ChatController::class, 'newchat'])->name('new.chat');
    Route::post('/new/chat', [ChatController::class, 'newChatStore'])->name('new.chat.store');
    //audio call
    Route::get('/audio/call', [AudioCallController::class, 'index'])->name('audio.call');
    Route::post('/chat/call/notify', [AudioCallController::class, 'sendCall'])->name('send.call');
});
Route::post('/chat/send-message', [ChatController::class, 'store'])->name('chat.send');

Route::get('/chat/messages/{id}', [ChatController::class, 'chatMessages'])->name('chat.messages');