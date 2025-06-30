@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Registrar Categoría</h2>
    <form action="{{ route('categorias.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
