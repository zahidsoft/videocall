<?php

namespace App\Http\Controllers\Chat;

use App\Models\User;
use Inertia\Inertia;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();
        $authUserId = Auth::user()->id;
        return Inertia::render('Chat/index', ['users' => $users , 'authUserId' => $authUserId]);
    }

    public function store(Request $request)
    {
        $message = $request->input('message');
        $receiver_id = $request->input('receiver_id');

        MessageSent::dispatch($message, $receiver_id);
        return response()->json(['success' => true, 'message' => "$receiver_id Message sent successfully"]);
    }

    //video chat index
    public function video()
    {
        $users = User::all();
        $authUserId = Auth::user()->id;
        return Inertia::render('Chat/video', ['users' => $users , 'authUserId' => $authUserId]);
    }
}
