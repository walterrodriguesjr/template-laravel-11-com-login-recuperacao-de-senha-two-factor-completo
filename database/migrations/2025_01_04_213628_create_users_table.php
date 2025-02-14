<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken(); // Para "lembrar-me"
            $table->timestamp('email_verified_at')->nullable();

            // Campos para autenticação em duas etapas (2FA)
            $table->boolean('two_factor_enabled')->default(false); // Indica se o 2FA está ativado
            $table->string('two_factor_type')->nullable(); // Tipo de 2FA (e-mail, SMS, etc.)
            $table->string('two_factor_code')->nullable(); // Código gerado para 2FA
            $table->timestamp('two_factor_expires_at')->nullable(); // Expiração do código

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
