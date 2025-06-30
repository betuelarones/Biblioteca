@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Usuario</h2>
    <form action="{{ url('/usuarios/' . ($usuario->ID_USUARIO ?? $usuario->id_usuario)) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" value="{{ $usuario->NOMBRE ?? $usuario->nombre }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo:</label>
            <input type="email" name="correo" value="{{ $usuario->CORREO ?? $usuario->correo }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rol:</label>
            <select name="rol_id" class="form-select" required>
                <option value="1" {{ ($usuario->ROL_ID ?? $usuario->rol_id) == 1 ? 'selected' : '' }}>Bibliotecario</option>
                <option value="2" {{ ($usuario->ROL_ID ?? $usuario->rol_id) == 2 ? 'selected' : '' }}>Usuario</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
