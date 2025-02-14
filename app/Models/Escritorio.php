<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escritorio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nome_escritorio',
        'cnpj_escritorio',
        'telefone_escritorio',
        'celular_escritorio',
        'email_escritorio',
        'cep_escritorio',
        'logradouro_escritorio',
        'numero_escritorio',
        'bairro_escritorio',
        'estado_escritorio',
        'cidade_escritorio'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
