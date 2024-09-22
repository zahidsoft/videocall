<?php

namespace App\Http\Controllers;

use App\Events\audioCallEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AudioCallController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        $authUser = Auth::user();
        return Inertia::render('Chat/audioCall', ['users' => $users, 'authUser' => $authUser]);
    }

    public function sendCall(Request $request)
    {
        // return $request;
        $caller_id = $request->caller_id;
        $receiver_id = $request->receiver_id;
        $caller_peer_id = $request->caller_peer_id;
        Broadcast(new audioCallEvent($caller_id, $receiver_id, $caller_peer_id))->toOthers();
        // audioCallEvent::dispatch($caller_id, $receiver_id);
        return response()->jsone(['message:' => "call send successfully"]);
    }

}
