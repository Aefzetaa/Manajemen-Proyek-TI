<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = $request->user()->messages()->latest()->get();

        return view('messages.index', compact('messages'));
    }

    public function read(Request $request, \App\Models\Message $message): \Illuminate\Http\RedirectResponse
    {
        abort_unless($message->user_id === $request->user()->id, 403);
        $message->update(['is_read' => true]);
        
        return redirect($message->body ?? route('messages.index'));
    }
}
