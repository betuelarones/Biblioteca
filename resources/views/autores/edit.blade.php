@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Autor</h2>
    <form action="{{ url('/autores/' . ($autor->ID ?? $autor->id)) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" value="{{ $autor->NOMBRE ?? $autor->nombre }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('autores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
