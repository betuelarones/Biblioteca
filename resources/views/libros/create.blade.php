@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Registrar Libro</h2>
    <form action="{{ route('libros.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label class="form-label">Título:</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Autor:</label>
            <select name="autor_id" class="form-select" required>
                <option value="">Selecciona un autor</option>
                @foreach($autores as $autor)
                    <option value="{{ $autor->id_autor }}">{{ $autor->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoría:</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Año de Publicación:</label>
            <input type="number" name="anio_publicacion" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="{{ route('libros.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
