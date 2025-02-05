@extends('layouts.main')

@section('title', 'Dados do Escritório')

@section('content')
<div class="col-md-12">
    <div class="card card-outline card-primary collapsed-card">
        <div class="card-header d-flex align-items-center" data-card-widget="collapse">
            <h3 class="card-title mb-0">Dados do Escritório</h3>
            <div class="card-tools ml-auto">
                <button type="button" class="btn btn-tool">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="dados-escritorio-form" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nomeEscritorio">Nome do Escritório</label>
                    <input type="text" class="form-control" id="nomeEscritorio" name="nome_escritorio"
                        placeholder="Digite o nome do escritório" required>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cnpjEscritorio">CNPJ</label>
                            <input type="text" class="form-control" id="cnpjEscritorio"
                                name="cnpj_escritorio" placeholder="Digite o CNPJ">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telefoneEscritorio">Telefone</label>
                            <input type="text" class="form-control" id="telefoneEscritorio"
                                name="telefone_escritorio" placeholder="(00) 0000-0000">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="celularEscritorio">Celular</label>
                            <input type="text" class="form-control" id="celularEscritorio"
                                name="celular_escritorio" placeholder="(00) 00000-0000">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="emailEscritorio">Email</label>
                            <input type="email" class="form-control" id="emailEscritorio"
                                name="email_escritorio" placeholder="Digite o email do escritório">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cepEscritorio">CEP</label>
                            <input type="text" class="form-control" id="cepEscritorio"
                                name="cep_escritorio" placeholder="Digite o CEP">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logradouroEscritorio">Logradouro</label>
                            <input type="text" class="form-control" id="logradouroEscritorio"
                                name="logradouro_escritorio" placeholder="Digite o logradouro">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="numeroEscritorio">Número</label>
                            <input type="text" class="form-control" id="numeroEscritorio"
                                name="numero_escritorio" placeholder="Número">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bairroEscritorio">Bairro</label>
                            <input type="text" class="form-control" id="bairroEscritorio"
                                name="bairro_escritorio" placeholder="Digite o bairro">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estadoEscritorio">Estado</label>
                            <select class="form-control" id="estadoEscritorio" name="estado_escritorio">
                                <option value="">Selecione um estado</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cidadeEscritorio">Cidade</label>
                            <select class="form-control" id="cidadeEscritorio" name="cidade_escritorio">
                                <option value="">Selecione uma cidade</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12 text-right">
                        @if (!Auth::user()->escritorio)
                            <button type="button" class="btn btn-primary" id="buttonSalvarDadosEscritorio">
                                <i class="fas fa-save"></i> Cadastrar Escritório
                            </button>
                        @else
                            <button type="button" class="btn btn-success" id="buttonAtualizarDadosEscritorio">
                                <i class="fas fa-edit"></i> Atualizar Escritório
                            </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const userId = "{{ Auth::id() }}"; // ID do usuário logado
        const escritorioId = "{{ Auth::user()->escritorio->id ?? '' }}"; // Verifica se já existe um escritório
        // Define URL para atualização se existir um escritório
        const escritorioUpdateUrl = escritorioId ?
            "{{ route('escritorio.update', ':id') }}".replace(':id', escritorioId) :
            null;

        // Define URL para criação de um novo escritório
        const escritorioStoreUrl = "{{ route('escritorio.store') }}";
        const escritorioShowUrl = escritorioId ? "{{ route('escritorio.show', ':id') }}".replace(':id', escritorioId) :
        null; // Apenas define se existir
        const csrfToken = "{{ csrf_token() }}";
    </script>

    
    <script src="{{ asset('js/escritorio/escritorio-form-show.js') }}"></script>
    <script src="{{ asset('js/escritorio/escritorio-form-store.js') }}"></script>
    <script src="{{ asset('js/escritorio/escritorio-form-update.js') }}"></script>
@endpush
