<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;


class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'subject'=>'required|string',
            'message' => 'required|string',
        ]);
Contact::create($data); //sauvegarde

        // Simule un envoi (tu peux configurer Mail dans .env si tu veux l'envoyer vraiment)
        return response()->json(['message' => 'Message reçu. Merci pour votre contact.'],200);
    }
}
