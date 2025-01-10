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
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => [
            'required',
            'string',
            'min:12', // Pelo menos 12 caracteres
            'regex:/[a-z]/', // Pelo menos uma letra minúscula
            'regex:/[A-Z]/', // Pelo menos uma letra maiúscula
            'regex:/[0-9]/', // Pelo menos um número
            'regex:/[@$!%*#?&]/', // Pelo menos um caractere especial
        ],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Usuário registrado com sucesso!');
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
        
        // Verifica se o 2FA está habilitado
        if ($user->two_factor_enabled) {
            $user->generateTwoFactorCode(); // Gera o código de 2FA
            $user->sendTwoFactorCode(); // Envia o código por e-mail ou SMS

            // Redireciona para a verificação de 2FA
            return redirect()->route('two-factor.show')->with('status', 'Código de verificação enviado.');
        }
        // Se o 2FA não estiver habilitado, redireciona para a rota principal
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
