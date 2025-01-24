@extends('layouts.main')

@section('title', 'Meus Dados')



@section('content')
    <div class="content p-0">
        <div class="container-fluid p-0">
            <div class="container p-2" id="meus-dados">

                <div class="card col-md-12">
                    <h2 class="card-header bg-primary text-white text-center">Meus Dados</h2>
                    <div class="card-body">
                        <form id="meus-dados-form" method="POST" novalidate>
                            @csrf
                            <!-- Nome -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nomeUsuario">Nome</label>
                                    <input type="text" class="form-control" id="nomeUsuario" name="nome_usuario"
                                        value="{{ Auth::user()->name }}" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emailUsuario">Email</label>
                                    <input type="email" class="form-control" id="emailUsuario" name="email_usuario"
                                        value="{{ Auth::user()->email }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- CPF -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cpfUsuario">CPF</label>
                                        <input type="text" class="form-control" id="cpfUsuario" name="cpf_usuario"
                                            placeholder="Digite seu CPF">
                                    </div>
                                </div>

                                <!-- Celular -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="celularUsuario">Celular</label>
                                        <input type="text" class="form-control" id="celularUsuario"
                                            name="celular_usuario" placeholder="(00) 00000-0000">
                                    </div>
                                </div>

                                <!-- Data de Nascimento -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dataNascimentoUsuario">Data de Nascimento</label>
                                        <input type="date" class="form-control" id="dataNascimentoUsuario"
                                            name="data_nascimento_usuario">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Estado -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estadoUsuario">Estado</label>
                                        <select class="form-control" id="estadoUsuario" name="estado_usuario"
                                            style="width: 100%;">
                                            <option value="">Selecione um estado</option>

                                        </select>
                                    </div>
                                </div>
                                <!-- Cidade -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cidadeUsuario">Cidade</label>
                                        <select class="form-control" id="cidadeUsuario" name="cidade_usuario"
                                            style="width: 100%;">
                                            <option value="">Selecione uma cidade</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- OAB -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="oabUsuario">OAB</label>
                                        <input type="text" class="form-control" id="oabUsuario" name="oab_usuario"
                                            placeholder="Número da OAB">
                                    </div>
                                </div>

                                <!-- Estado OAB -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estadoOabUsuario">Estado OAB</label>
                                        <select class="form-control" id="estadoOabUsuario" name="estado_oab_usuario"
                                            style="width: 100%;">
                                            <option value="">Selecione um estado da OAB</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 float-right">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary" id="buttonSalvarDadosUsuarios">
                                        <i class="fas fa-save"></i> Salvar Dados
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/perfil/perfil-form.js') }}"></script>
    <script>
         const userId = "{{ Auth::id() }}"; // Armazena o ID do usuário logado
        const perfilUpdateUrl = "{{ route('perfil.update', ['perfil' => Auth::id()]) }}";
        const perfilShowUrl = "{{ route('perfil.show', ['perfil' => ':id']) }}".replace(':id', userId); // URL dinâmica
        const csrfToken = "{{ csrf_token() }}";
    </script>
@endpush
