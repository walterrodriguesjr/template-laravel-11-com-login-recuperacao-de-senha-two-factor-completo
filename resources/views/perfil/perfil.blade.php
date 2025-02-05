@extends('layouts.main')

@section('title', 'Perfil')

@section('content')
    <div class="col-md-12">
        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header d-flex align-items-center" data-card-widget="collapse">
                <h3 class="card-title m-0">Dados Pessoais</h3>
                <div class="card-tools ml-auto">
                    <button type="button" class="btn btn-tool">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
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
                                <input type="text" class="form-control" id="celularUsuario" name="celular_usuario"
                                    placeholder="(00) 00000-0000">
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
                                <select class="form-control" id="estadoUsuario" name="estado_usuario" style="width: 100%;">
                                    <option value="">Selecione um estado</option>
                                </select>
                            </div>
                        </div>
                        <!-- Cidade -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cidadeUsuario">Cidade</label>
                                <select class="form-control" id="cidadeUsuario" name="cidade_usuario" style="width: 100%;">
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

                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-success" id="buttonSalvarDadosUsuarios">
                                <i class="fas fa-edit"></i> Atualizar Dados
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Atualizar Senha -->
    <div class="col-md-12">
        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header d-flex align-items-center" data-card-widget="collapse">
                <h3 class="card-title m-0">Alterar Senha</h3>
                <div class="card-tools ml-auto">
                    <button type="button" class="btn btn-tool">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="alterar-senha-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="senhaAtual">Senha Atual</label>
                        <input type="password" class="form-control" id="senhaAtual" name="senha_atual" required
                            placeholder="Digite sua senha atual">
                    </div>
                    <div class="form-group">
                        <label for="novaSenha">Nova Senha</label>
                        <input type="password" class="form-control" id="novaSenha" name="nova_senha" required
                            placeholder="Digite sua nova senha">
                    </div>
                    <div class="form-group">
                        <label for="confirmarSenha">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirmarSenha" name="nova_senha_confirmation"
                            required placeholder="Confirme sua nova senha">
                    </div>
                    <button type="button" class="btn btn-success float-right" id="buttonAlterarSenha"><i
                            class="fas fa-save"></i> Alterar
                        Senha</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Autenticação de Dois Fatores -->
    <div class="col-md-12">
        <div class="card card-outline card-primary collapsed-card">
            <div class="card-header d-flex align-items-center" data-card-widget="collapse">
                <h3 class="card-title m-0">Autenticação de Dois Fatores</h3>
                <div class="card-tools ml-auto">
                    <button type="button" class="btn btn-tool">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="formAutenticacaoDoisFatores">
                    @csrf

                    <!-- Mensagem condicional -->
                    <div id="mensagem2FA"
                        class="alert {{ Auth::user()->two_factor_enabled ? 'alert-info' : 'alert-danger' }}">
                        {{ Auth::user()->two_factor_enabled
                            ? 'Sua autenticação de dois fatores já está ativa. Caso queira desabilitar, clique no botão abaixo.'
                            : 'Clique para habilitar sua autenticação de dois fatores.' }}
                    </div>

                    <!-- Opção para ativar/desativar (Switch estilo Apple) -->
                    <div class="form-group">
                        <label for="switch2FA">Ativar Autenticação de Dois Fatores?</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="switch2FA" name="dois_fatores"
                                {{ Auth::user()->two_factor_enabled ? 'checked' : '' }}>
                            <label class="custom-control-label" for="switch2FA"></label>
                        </div>
                    </div>

                    <!-- Seleção do método (somente aparece se 2FA estiver ativado) -->
                    <div class="form-group" id="metodoAutenticacao"
                        style="display: {{ Auth::user()->two_factor_enabled ? 'block' : 'none' }};">
                        <label>Escolha o método de autenticação</label>
                        <select class="form-control select2" id="tipoAutenticacao" name="tipo_autenticacao"
                            style="width: 100%;">
                            <option value="email" {{ Auth::user()->two_factor_type == 'email' ? 'selected' : '' }}>E-mail
                            </option>
                            {{-- <option value="sms" {{ Auth::user()->two_factor_type == 'sms' ? 'selected' : '' }}>SMS
                            </option> --}}
                            {{-- <option value="app" {{ Auth::user()->two_factor_type == 'app' ? 'selected' : '' }}>Google
                                Authenticator</option> --}}
                        </select>
                    </div>

                    <button type="button" class="btn btn-success float-right"
                        id="buttonAlterarLaterarAutenticaoDoisFatores">
                        <i class="fas fa-lock"></i> Atualizar Segurança
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    {{-- Scripts Meus Dados --}}
    <script src="{{ asset('js/perfil/meus-dados/perfil-form-update.js') }}"></script>

    {{-- Scripts Alterar Senha --}}
    <script src="{{ asset('js/perfil/alterar-senha/alterar-senha-form-update.js') }}"></script>

    {{-- Scripts Alterar Autenticacao de dois fatores --}}
    <script
        src="{{ asset('js/perfil/alterar-autenticacao-dois-fatores/alterar-autenticacao-dois-fatores-form-update.js') }}">
    </script>

    <script>
        const userId = "{{ Auth::id() }}"; // Armazena o ID do usuário logado
        const perfilUpdateUrl = "{{ route('perfil.update', ['perfil' => Auth::id()]) }}";
        const perfilShowUrl = "{{ route('perfil.show', ['perfil' => ':id']) }}".replace(':id', userId); // URL dinâmica
        const csrfToken = "{{ csrf_token() }}";
    </script>
@endpush
