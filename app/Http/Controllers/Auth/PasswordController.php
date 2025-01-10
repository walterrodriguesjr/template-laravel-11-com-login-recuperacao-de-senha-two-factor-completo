<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => 'Enviamos um link para redefinir sua senha. Verifique seu email.'])
                    : back()->withErrors(['email' => __($status)]);
    }
    


    public function resetPassword(Request $request)
{
    $validated = $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => [
            'required',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ],
    ], [
        'password.regex' => 'A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial.',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->forceFill([
                'password' => Hash::make($request->password),
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Senha redefinida com sucesso.')
        : back()->withErrors(['email' => __($status)]);
}


    public function showResetForm(Request $request, $token)
{
    return view('password.reset-password', [
        'token' => $token,
        'email' => $request->email, // Adicione o email se necessário na view
    ]);
}

}
