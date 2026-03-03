<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        return Message::where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id())
                ->with(['sender', 'receiver'])
                ->latest('sent_at')
                ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:2000',
        ]);

        $data['sender_id'] = Auth::id(); // Ajout automatique de l'expéditeur connecté

        $message = Message::create($data);

        return response()->json($message, 201);
    }

}
