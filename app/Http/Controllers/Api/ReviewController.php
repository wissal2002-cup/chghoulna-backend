<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::with(['user', 'service'])->latest()->get();
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $data['reviewer_id'] = Auth::id();

        $review = Review::create($data);

        return response()->json($review, 201);

        if ($request->user_id == Auth::id()) {
    return response()->json(['error' => 'Vous ne pouvez pas vous noter vous-même.'], 403);
}

$exists = Review::where('reviewer_id', Auth::id())
                ->where('user_id', $request->user_id)
                ->first();

if ($exists) {
    return response()->json(['error' => 'Vous avez déjà laissé un avis.'], 422);
}
$averageRating = $user->reviews()->avg('rating');
return response()->json([
    'user' => $user,
    'average_rating' => round($averageRating, 1),
]);

   dd(Auth::id());
    }
    
    
}
