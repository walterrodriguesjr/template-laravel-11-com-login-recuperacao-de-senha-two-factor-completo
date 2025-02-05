<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('perfil.perfil');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    try {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'dados' => [
                'nome_usuario' => $user->name,
                'email_usuario' => $user->email,
                'cpf_usuario' => $user->userData->cpf ?? null,
                'celular_usuario' => $user->userData->celular ?? null,
                'data_nascimento_usuario' => $user->userData->data_nascimento ?? null,
                'estado_usuario' => $user->userData->estado ?? null,
                'cidade_usuario' => $user->userData->cidade ?? null,
                'oab_usuario' => $user->userData->oab ?? null,
                'estado_oab_usuario' => $user->userData->estado_oab ?? null,
            ],
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Usuário não encontrado.',
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar os dados do usuário.',
            'error' => $e->getMessage(), // Remova em produção
        ], 500);
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            // Busca o usuário pelo ID
            $user = User::findOrFail($id);

            // Verifica se userData existe
            $userData = $user->userData;
            $userDataId = $userData ? $userData->id : null;

            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'nome_usuario' => 'required|string|min:3|max:255',
                'email_usuario' => 'required|email|max:255|unique:users,email,' . $user->id,
                'cpf_usuario' => [
                    'required',
                    'string',
                    'size:14',
                    Rule::unique('user_data', 'cpf')->ignore($userDataId),
                ],
                'celular_usuario' => 'required|string|size:14',
                'data_nascimento_usuario' => 'required|date|before:today',
                'estado_usuario' => 'required|size:2',
                'cidade_usuario' => 'required|string|max:255',
                'oab_usuario' => 'nullable|numeric|digits_between:1,8',
                'estado_oab_usuario' => 'nullable|string|size:2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Atualiza os dados do usuário
            $user->update([
                'name' => $request->input('nome_usuario'),
                'email' => $request->input('email_usuario'),
            ]);

            // Cria ou atualiza userData
            if (!$userData) {
                $userData = new UserData();
                $userData->user_id = $user->id;
            }

            $userData->fill([
                'cpf' => $request->input('cpf_usuario'),
                'celular' => $request->input('celular_usuario'),
                'data_nascimento' => $request->input('data_nascimento_usuario'),
                'estado' => $request->input('estado_usuario'),
                'cidade' => $request->input('cidade_usuario'),
                'oab' => $request->input('oab_usuario'),
                'estado_oab' => $request->input('estado_oab_usuario')
            ])->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dados atualizados com sucesso!',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Usuário não encontrado.',
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar os dados. Tente novamente mais tarde.',
                'error' => $e->getMessage(), // Apenas para debug, remova em produção
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
