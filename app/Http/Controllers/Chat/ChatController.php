<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat;
use App\Models\User;
use Inertia\Inertia;
use App\Events\SendCall;
use App\Events\ChatEvent;
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
        return Inertia::render('Chat/index', ['users' => $users, 'authUserId' => $authUserId]);
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
        return Inertia::render('Chat/video', ['users' => $users, 'authUserId' => $authUserId]);
    }
    public function sendcall(Request $request)
    {
        $peer_id = $request->peer_id;
        $receiver_id = $request->receiver_id;
        SendCall::dispatch($peer_id, $receiver_id);
        return response()->json(['success' => true, 'message' => "Message sent successfully"]);
    }

    //new chat system one to one store. 

    // chat window opening 
    public function newchat()
    {
        $users = User::where('id', '!=', Auth::user()->id)->get();
        $authUser = Auth::user();
        return Inertia::render('Chat/newchat', ['users' => $users, 'authUser' => $authUser]);
    }
    //show messages
    public function chatMessages($receiver_id)
    {
        // $sender_id = 1;
        $sender_id = Auth::user()->id;
        $messages = Chat::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', $sender_id);
        })
            ->with('sender:id,name', 'receiver:id,name')
            ->get();
        return response()->json(compact('messages'));

    }
    // sote message 
    public function newChatStore(Request $request)
    {
        $chatMessage = new Chat();
        $chatMessage->sender_id = Auth::user()->id;
        $chatMessage->receiver_id = $request->receiver_id;
        $chatMessage->message = $request->message;
        $chatMessage->save();
        Broadcast(new ChatEvent($chatMessage))->toOthers();
        return response()->json(['success' => true, 'message' => "message sent successfully"]);
    }

}
