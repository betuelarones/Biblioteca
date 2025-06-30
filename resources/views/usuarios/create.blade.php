@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Registrar Usuario</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('usuarios.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo:</label>
            <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rol:</label>
            <select name="rol_id" class="form-select" required>
                <option value="">Selecciona un rol</option>
                <option value="1" {{ old('rol_id') == 1 ? 'selected' : '' }}>Bibliotecario</option>
                <option value="2" {{ old('rol_id') == 2 ? 'selected' : '' }}>Usuario</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="{{ route('login') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
