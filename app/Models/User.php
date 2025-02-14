<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_enabled',
        'two_factor_type',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code', // Esconde o código de 2FA
    ];

    protected $casts = [
        'password' => 'hashed',
        'two_factor_expires_at' => 'datetime',
    ];

    public function userData()
    {
        return $this->hasOne(UserData::class);
    }

    public function escritorio()
    {
        return $this->hasOne(Escritorio::class, 'user_id');
    }

    // Retorna todas as sessões ativas do usuário
    public function activeSessions()
    {
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->orderBy('last_activity', 'desc')
            ->get();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function generateTwoFactorCode()
    {
        $this->forceFill([
            'two_factor_code' => random_int(100000, 999999), // Código de 6 dígitos
            'two_factor_expires_at' => now()->addMinutes(10), // Expiração de 10 minutos
        ])->save();
    }

    public function sendTwoFactorCode()
    {
        if ($this->two_factor_type === 'email') {
            Mail::to($this->email)->send(new \App\Mail\TwoFactorCodeMail($this));
        } elseif ($this->two_factor_type === 'sms') {
            // Integração com serviço de SMS, como Twilio
        }
    }
}
