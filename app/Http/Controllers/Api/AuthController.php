<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{
    //Inscription
   public function register(Request $request)
{
    $request->validate([
        'prenom' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
         'city'=>'nullable|string|max:100',
         'bio'=>'nullable|string|max:2048',
         'photo'=>'nullable|image|max:2048',
        'password' => 'required|string|min:6|confirmed',
    ]);
// 📸 Upload de l'image si présente
        $photoPath = null;
if ($request->hasFile('photo')) {
    $uploaded = cloudinary()->upload($request->file('photo')->getRealPath());
    $photoPath = $uploaded->getSecurePath();
}

    $user = User::create([
        'prenom' => $request->prenom,
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'city' => $request->city,
        'bio' => $request->bio,
        'photo' => $photoPath,
        'password' => bcrypt($request->password),
    ]);

$token = $user->createToken('auth_token')->plainTextToken;


    return response()->json([
        'message' => 'Utilisateur enregistré avec succès',
        'user' => $user,
        'token' => $token,
    ]);
}

      //Connexion
      public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token,
        ]);
    }

    //utilisateur connecter
      public function me(Request $request)
    {
        return response()->json($request->user());
    }

    //  Déconnexion
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnecté avec succès',
        ]);
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Check if authenticated user is updating their own profile
    if ($request->user()->id !== $user->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $data = $request->validate([
        'prenom' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
         'city'=>'nullable|string',
         'bio'=>'nullable|string',
         'photo'=>'nullable|image|max:2048',
    ]);

    // Handle image if present
if ($request->hasFile('photo')) {
    $uploaded = cloudinary()->upload($request->file('photo')->getRealPath());
    $data['photo'] = $uploaded->getSecurePath();
}

    $user->update($data);

    return response()->json(['message' => 'Profil mis à jour', 'user' => $user]);
}

public function show($id)
{
    $user = User::with([
    'services',
    'reviews.reviewer',
    'messagesReceived.sender'
    ])->findOrFail($id);
    
    return response()->json($user);
}


}

