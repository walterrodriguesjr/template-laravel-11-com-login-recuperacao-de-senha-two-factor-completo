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
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

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
                'cpf_usuario' => $user->userData ? Crypt::decryptString($user->userData->cpf) : null,
                'celular_usuario' => $user->userData ? Crypt::decryptString($user->userData->celular) : null,
                'data_nascimento_usuario' => $user->userData->data_nascimento ?? null, // Sem descriptografar
                'estado_usuario' => $user->userData->estado ?? null,
                'cidade_usuario' => $user->userData->cidade ?? null,
                'oab_usuario' => $user->userData ? Crypt::decryptString($user->userData->oab) : null,
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
            'cpf' => Crypt::encryptString($request->input('cpf_usuario')),
            'celular' => Crypt::encryptString($request->input('celular_usuario')),
            'data_nascimento' => $request->input('data_nascimento_usuario'), // Sem criptografia
            'estado' => $request->input('estado_usuario'),
            'cidade' => $request->input('cidade_usuario'),
            'oab' => Crypt::encryptString($request->input('oab_usuario')),
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

    /**
     * Exporta os dados do usuário logado em JSON ou CSV
     */
    public function exportarDados(Request $request)
    {
        try {
            DB::beginTransaction(); // Inicia a transação para garantir consistência
    
            $user = Auth::user();
    
            // Garante que o usuário esteja autenticado
            abort_if(!$user, 403, 'Acesso negado.');
    
            $userData = $user->userData; // Dados adicionais do usuário
            $escritorio = $user->escritorio; // Dados do escritório, se houver
    
            // Estrutura básica dos dados do usuário
            $dadosUsuario = [
                'ID' => $user->id,
                'Nome' => $user->name,
                'E-mail' => $user->email,
                'Autenticação de Dois Fatores' => $user->two_factor_enabled ? 'Ativada' : 'Desativada',
                'Método de 2FA' => $user->two_factor_type ?? 'Não configurado',
                'Data de Criação' => $user->created_at->format('d/m/Y H:i:s'),
                'Última Atualização' => $user->updated_at->format('d/m/Y H:i:s'),
            ];
    
            // Adiciona os dados adicionais do usuário, descriptografando os campos necessários
            if ($userData) {
                $dadosUsuario = array_merge($dadosUsuario, [
                    'CPF' => $userData->cpf ? Crypt::decryptString($userData->cpf) : 'Não informado',
                    'Telefone' => $userData->telefone ? Crypt::decryptString($userData->telefone) : 'Não informado',
                    'Celular' => $userData->celular ? Crypt::decryptString($userData->celular) : 'Não informado',
                    'Cidade' => $userData->cidade ?? 'Não informado',
                    'Estado' => $userData->estado ?? 'Não informado',
                    'OAB' => $userData->oab ? Crypt::decryptString($userData->oab) : 'Não informado',
                    'Estado da OAB' => $userData->estado_oab ?? 'Não informado',
                    'Data de Nascimento' => $userData->data_nascimento ? date('d/m/Y', strtotime($userData->data_nascimento)) : 'Não informado',
                ]);
            }
    
            // Adiciona os dados do escritório, se existirem
            $dadosEscritorio = [];
            if ($escritorio) {
                $dadosEscritorio = [
                    'Nome do Escritório' => $escritorio->nome ?? 'Não informado',
                    'CNPJ' => $escritorio->cnpj ?? 'Não informado',
                    'Telefone' => $escritorio->telefone ?? 'Não informado',
                    'Celular' => $escritorio->celular ?? 'Não informado',
                    'E-mail' => $escritorio->email ?? 'Não informado',
                    'CEP' => $escritorio->cep ?? 'Não informado',
                    'Endereço' => $escritorio->logradouro ?? 'Não informado',
                    'Número' => $escritorio->numero ?? 'Não informado',
                    'Bairro' => $escritorio->bairro ?? 'Não informado',
                    'Cidade' => $escritorio->cidade ?? 'Não informado',
                    'Estado' => $escritorio->estado ?? 'Não informado',
                ];
            }
    
            // Captura o histórico de logins do usuário
            $historicoLogins = DB::table('sessions')->where('user_id', $user->id)->get()->map(function ($session) {
                return [
                    'IP' => $session->ip_address,
                    'Navegador' => $session->user_agent,
                    'Última Ação' => date('d/m/Y H:i:s', $session->last_activity),
                ];
            });
    
            DB::commit(); // Confirma a transação
    
            // Captura o formato desejado (JSON ou CSV)
            $formato = strtolower($request->query('formato', 'json'));
            abort_if(!in_array($formato, ['json', 'csv']), 400, 'Formato inválido.');
    
            if ($formato === 'csv') {
                return $this->exportarComoCSV($dadosUsuario, $dadosEscritorio, $historicoLogins);
            }
    
            // Retorna os dados em JSON
            return response()->json([
                'dados_usuario' => $dadosUsuario,
                'dados_escritorio' => $dadosEscritorio,
                'historico_logins' => $historicoLogins
            ], 200, [
                'Content-Disposition' => 'attachment; filename="meus-dados.json"',
                'Content-Type' => 'application/json'
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Desfaz a transação em caso de erro
            Log::error("Erro ao exportar dados: " . $e->getMessage());
    
            return response()->json(['message' => 'Erro ao exportar os dados.'], 500);
        }
    }
    


    /**
     * Exporta os dados como CSV
     */
    private function exportarComoCSV($dadosUsuario, $dadosEscritorio, $historicoLogins)
{
    try {
        $response = new StreamedResponse(function () use ($dadosUsuario, $dadosEscritorio, $historicoLogins) {
            $handle = fopen('php://output', 'w');

            // Escreve cabeçalhos do CSV
            fputcsv($handle, ['Campo', 'Valor']);

            // Escreve os dados do usuário
            foreach ($dadosUsuario as $campo => $valor) {
                fputcsv($handle, [$campo, $valor]);
            }

            fputcsv($handle, ['']); // Linha em branco para separação

            // Escreve os dados do escritório, se houver
            if (!empty($dadosEscritorio)) {
                fputcsv($handle, ['Dados do Escritório']);
                foreach ($dadosEscritorio as $campo => $valor) {
                    fputcsv($handle, [$campo, $valor]);
                }
                fputcsv($handle, ['']); // Linha em branco para separação
            }

            // Escreve o histórico de logins
            fputcsv($handle, ['Histórico de Logins']);
            fputcsv($handle, ['IP', 'Navegador', 'Última Ação']);
            foreach ($historicoLogins as $login) {
                fputcsv($handle, [$login['IP'], $login['Navegador'], $login['Última Ação']]);
            }

            fclose($handle);
        });

        // Define headers apropriados
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="meus-dados.csv"');

        return $response;
    } catch (\Exception $e) {
        Log::error("Erro ao exportar CSV: " . $e->getMessage());

        return response()->json(['message' => 'Erro ao gerar o CSV.'], 500);
    }
}

}
