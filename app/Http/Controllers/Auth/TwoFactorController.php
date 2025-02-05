<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Auth;


class TwoFactorController extends RoutingController
{
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = Auth::user();

        if ($user->two_factor_code !== $request->code || now()->greaterThan($user->two_factor_expires_at)) {
            return back()->withErrors(['code' => 'Código inválido ou expirado.']);
        }

        // Limpa o código após uso
        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();

        return redirect()->route('main');
    }

    public function showTwoFactorForm()
{
    return view('two-factor.two-factor');
}

public function resendTwoFactorCode(Request $request)
{
    $user = Auth::user();

    // Gera e envia um novo código de 2FA 
    $user->generateTwoFactorCode();
    $user->sendTwoFactorCode();

    return back()->with('status', 'Um novo código foi enviado para seu ' . ($user->two_factor_type === 'sms' ? 'número de telefone' : 'e-mail') . '.');
}

}
