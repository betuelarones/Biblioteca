@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Libro</h2>
    <form action="{{ url('/libros/' . $libro->id_libro) }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Título:</label>
            <input type="text" name="titulo" value="{{ $libro->titulo }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Autor:</label>
            <select name="autor_id" class="form-select" required>
                @foreach($autores as $autor)
                    <option value="{{ $autor->id_autor }}" {{ $libro->id_autor == $autor->id_autor ? 'selected' : '' }}>{{ $autor->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoría:</label>
            <select name="categoria_id" class="form-select" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}" {{ $libro->id_categoria == $categoria->id_categoria ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Año de Publicación:</label>
            <input type="number" name="anio_publicacion" value="{{ $libro->anio_publicacion }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('libros.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
