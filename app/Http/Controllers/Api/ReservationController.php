<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        return Reservation::with(['user', 'service'])->where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'status' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $reservation = Reservation::create($data);

        return response()->json($reservation, 201);
    }

    public function show(Reservation $reservation)
    {
        return $reservation->load('service', 'user');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reservation->delete();
        return response()->json(['message' => 'Annulée']);
    }
}
