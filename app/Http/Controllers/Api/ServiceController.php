<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::with('user')
        ->whereNotNull('type')
        ->latest()
        ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'required',
            'city' => 'required',
            'price' => 'required|numeric',
            'type'=>'required|in:Offre,Demande'
        ]);

        $validated['user_id'] = Auth::id();

        $service = Service::create($validated);

        return response()->json($service, 201);
    }

    public function show(Service $service)
    {
        return $service->load('user');
    }

    public function update(Request $request, Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $service->update($request->only(['title', 'description', 'category', 'city', 'price', 'status']));
        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $service->delete();
        return response()->json(['message' => 'Supprimé']);
    }
}
