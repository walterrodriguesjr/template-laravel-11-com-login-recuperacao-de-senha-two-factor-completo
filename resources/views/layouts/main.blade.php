<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Principal</title>

    <!-- AdminLTE CSS via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.0.0/dist/css/adminlte.min.css">

    <!-- Font Awesome via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <!-- Botão para expandir/recolher o sidebar -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
        
            <!-- Conteúdo alinhado à direita -->
            <ul class="navbar-nav ms-auto mr-3">
                <li class="nav-item d-flex align-items-center">
                    <span class="me-3">Olá, {{ Auth::user()->name }}</span>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Sair</button>
                    </form>
                </li>
            </ul>
        </nav>
        

        <!-- Sidebar -->
        @include('components.sidebar.sidebar')

        <!-- Conteúdo principal -->
        <main class="content-wrapper p-4">
            @yield('content')
        </main>
    </div>

    <!-- AdminLTE JS via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.0.0/dist/js/adminlte.min.js"></script>

    <script>
        $(document).ready(function () {
            console.log('AdminLTE Sidebar está funcionando!');
        });
    </script>
    @stack('scripts')
</body>
</html>
