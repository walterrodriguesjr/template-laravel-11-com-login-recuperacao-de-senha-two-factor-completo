@extends('layouts.main')

@section('title', 'Perfil do Usuário')

@section('content')
<header class="d-flex justify-content-between align-items-center mb-4">
    <h1>Perfil do Usuário</h1>
</header>

<div class="content">
    <h2>Detalhes do Usuário</h2>
    <ul>
        <li><strong>Nome:</strong> {{ Auth::user()->name }}</li>
        <li><strong>Email:</strong> {{ Auth::user()->email }}</li>
    </ul>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/perfil/perfil.js') }}"></script>
@endpush
