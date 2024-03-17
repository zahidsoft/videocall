<?php

namespace App\Http\Controllers\Chat;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Chat/index');
    }
}
