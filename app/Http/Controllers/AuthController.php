<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\RateLimiter;


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
        try {
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
    
            // Redireciona para a rota "main"
            return redirect()->route('main')->with('success', 'Bem-vindo, ' . $user->name);
        } catch (ThrottleRequestsException $e) {
            // Redireciona com mensagem amigável
            return back()->withErrors(['throttle' => 'Muitas tentativas de login. Tente novamente em 1 minuto.'])->withInput();
        }
    }
    



public function logout(Request $request)
{
    Auth::logout(); // Realiza logout da sessão
    $request->session()->invalidate(); // Invalida a sessão atual
    $request->session()->regenerateToken(); // Gera um novo token CSRF

    return redirect()->route('login')->with('success', 'Você foi desconectado com sucesso.');
}

}
