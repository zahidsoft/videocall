<?php

namespace App\Http\Controllers\Chat;

use Inertia\Inertia;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Chat/index');
    }

    public function store(Request $request)
    {
        $message = $request->input('message');

        MessageSent::dispatch($message);
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }
}
