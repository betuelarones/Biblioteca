@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Detalle del Usuario</h2>
    @if($usuario)
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $usuario->NOMBRE ?? $usuario->nombre }}</h4>
            <p class="card-text"><strong>Correo:</strong> {{ $usuario->CORREO ?? $usuario->correo }}</p>
            <p class="card-text"><strong>Rol:</strong> {{ ($usuario->ROL_ID ?? $usuario->rol_id) == 1 ? 'Bibliotecario' : 'Usuario' }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ url('/usuarios/'.($usuario->ID_USUARIO ?? $usuario->id_usuario).'/edit') }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
    @else
        <div class="alert alert-danger">Usuario no encontrado.</div>
    @endif
</div>
@endsection
