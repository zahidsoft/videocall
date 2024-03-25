<?php

namespace App\Http\Controllers\Chat;

use App\Models\User;
use Inertia\Inertia;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Inertia::render('Chat/index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        $message = $request->input('message');
        $receiver_id = $request->input('receiver_id');

        MessageSent::dispatch($message, $receiver_id);
        return response()->json(['success' => true, 'message' => "$receiver_id Message sent successfully"]);
    }
}
