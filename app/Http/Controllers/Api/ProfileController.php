<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function me()
{
    $user = Auth::user();

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->prenom . ' ' . $user->nom,
            'rating' => $user->rating ?? 4.5,
            'experience' => $user->bio ?? '',
            'picture' => $user->photo ?? null,
        ],
        'services' => $user->services, // Assumes relation: hasMany(Service::class)
        'reviews' => $user->receivedReviews // Assumes relation: hasMany(Review::class, 'user_id')
    ]);
}
}
