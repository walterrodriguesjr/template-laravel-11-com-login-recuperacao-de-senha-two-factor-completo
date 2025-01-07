<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Principal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Inclua os estilos e scripts compilados -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-primary text-white vh-100 p-3" style="width: 250px;">
        <h4 class="text-center mb-4">Gestão Jurídica</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="fas fa-home me-2"></i>Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="fas fa-user me-2"></i>Clientes</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="fas fa-gavel me-2"></i>Processos</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="fas fa-file-alt me-2"></i>Documentos</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white"><i class="fas fa-cogs me-2"></i>Configurações</a>
            </li>
            <li class="nav-item mt-4">
                <a href="{{ route('logout') }}" class="nav-link text-white"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
            </li>
        </ul>
    </nav>

    <!-- Conteúdo principal -->
    <main class="flex-grow-1 p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1>Bem-vindo ao Painel</h1>
            <div>
                <span class="me-3">Olá, {{ Auth::user()->name }}</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Sair</button>
                </form>
            </div>
        </header>

        <div class="content">
            <p>Selecione uma opção no menu lateral para começar.</p>
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
