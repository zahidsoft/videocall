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

        MessageSent::dispatch($message);
        return response()->json(['success' => true, 'message' => "Message sent successfully"]);
    }
}
