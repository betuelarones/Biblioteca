@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Categoría</h2>
    <form action="{{ url('/categorias/' . ($categoria->ID ?? $categoria->id)) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" value="{{ $categoria->NOMBRE ?? $categoria->nombre }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
