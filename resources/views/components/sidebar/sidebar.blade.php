<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Gestão Jurídica</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <a href="{{ route('perfil.index') }}"
            class="d-flex align-items-center flex-column text-white text-decoration-none user-panel mt-3 pb-3 mb-3">
            <!-- "Meus Dados" visível apenas no sidebar expandido -->
            <span class="fw-bold text-sm text-expanded mb-2 ml-2">Perfil</span>

            <!-- Foto do usuário -->
            <div class="image">
                <img src="{{ Auth::user()->userData && Auth::user()->userData->foto 
                    ? asset('storage/foto-perfil/' . Auth::user()->userData->foto) 
                    : asset('storage/foto-perfil/sem-foto.jpg') }}" 
                    class="img-circle elevation-2" alt="User Image">
            </div>
            

            <!-- Nome do usuário -->
            <div class="info">
                <span class="d-block">{{ Auth::user()->name }}</span>
            </div>
        </a>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('main') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('escritorio.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Escritório</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Clientes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-gavel"></i>
                        <p>Processos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Documentos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Configurações</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Sair</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
