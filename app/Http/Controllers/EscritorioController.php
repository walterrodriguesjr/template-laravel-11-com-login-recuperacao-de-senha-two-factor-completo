<?php

namespace App\Http\Controllers;

use App\Models\Escritorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EscritorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('escritorio.escritorio');
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
    try {
        DB::beginTransaction();

        $user = Auth::user();

        // Verifica se o usuário já possui um escritório
        if ($user->escritorio) {
            return response()->json([
                'success' => false,
                'message' => 'Você já possui um escritório cadastrado. Apenas atualizações são permitidas.'
            ], 400);
        }

        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'nome_escritorio' => 'required|string|max:255',
            'cnpj_escritorio' => 'nullable|string|max:18',
            'telefone_escritorio' => 'nullable|string|max:15',
            'celular_escritorio' => 'required|string|max:15',
            'email_escritorio' => 'required|email|max:255',
            'cep_escritorio' => 'nullable|string|max:9',
            'logradouro_escritorio' => 'nullable|string|max:255',
            'numero_escritorio' => 'nullable|string|max:10',
            'bairro_escritorio' => 'nullable|string|max:255',
            'estado_escritorio' => 'nullable|string|max:2',
            'cidade_escritorio' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Criação do escritório
        $escritorio = Escritorio::create($request->all() + ['user_id' => $user->id]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Escritório cadastrado com sucesso!',
            'dados' => $escritorio
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Erro ao cadastrar o escritório.',
            'error' => $e->getMessage() // Para debug, remova em produção
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show()
{
    try {
        $user = Auth::user();

        // Verifica se o usuário possui um escritório cadastrado
        $escritorio = Escritorio::where('user_id', $user->id)->first();

        if (!$escritorio) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum escritório cadastrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'dados' => $escritorio
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao carregar os dados do escritório.',
            'error' => $e->getMessage() // Remova em produção
        ], 500);
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Escritorio $escritorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Escritorio $escritorio)
{
    try {
        DB::beginTransaction();

        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'nome_escritorio' => 'required|string|max:255',
            'cnpj_escritorio' => 'nullable|string|max:18',
            'telefone_escritorio' => 'nullable|string|max:15',
            'celular_escritorio' => 'required|string|max:15',
            'email_escritorio' => 'required|email|max:255',
            'cep_escritorio' => 'nullable|string|max:9',
            'logradouro_escritorio' => 'nullable|string|max:255',
            'numero_escritorio' => 'nullable|string|max:10',
            'bairro_escritorio' => 'nullable|string|max:255',
            'estado_escritorio' => 'nullable|string|max:2',
            'cidade_escritorio' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Atualiza os dados do escritório
        $escritorio->update($request->all());

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Dados do escritório atualizados com sucesso!',
            'dados' => $escritorio
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Erro ao atualizar os dados do escritório.',
            'error' => $e->getMessage()
        ], 500);
    }
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Escritorio $escritorio)
    {
        //
    }
}
