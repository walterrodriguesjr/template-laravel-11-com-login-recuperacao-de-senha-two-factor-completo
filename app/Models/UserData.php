<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $fillable = [
        'cpf',
        'telefone',
        'celular',
        'cidade',
        'estado',
        'data_nascimento',
        'foto'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
