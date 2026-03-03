<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::with(['services', 'reviews','messagesReceived.sender'])->find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Append photo URL if exists
        $user->photo_url = $user->photo 
        ? asset('storage/photos/' . $user->photo) 
        : null;

        

        return response()->json($user);
    }
}
