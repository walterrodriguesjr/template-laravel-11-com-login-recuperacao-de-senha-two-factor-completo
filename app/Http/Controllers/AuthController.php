<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|c onfirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
{
    // Validação básica
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Verifica se o e-mail existe
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return back()->withErrors(['email' => 'E-mail não cadastrado.'])->withInput();
    }

    // Verifica credenciais
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['password' => 'Senha incorreta.'])->withInput();
    }

    // Login bem-sucedido
    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    // Redireciona para o dashboard ou outra página
    return redirect()->route('dashboard')->with('access_token', $token);
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
