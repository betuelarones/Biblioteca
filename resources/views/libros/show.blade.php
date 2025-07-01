@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Detalle del Libro</h2>
    @if($libro)
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $libro->titulo }}</h4>
            <p class="card-text"><strong>Autor:</strong> {{ $libro->autor }}</p>
            <p class="card-text"><strong>Categoría:</strong> {{ $libro->categoria }}</p>
            <p class="card-text"><strong>Año de Publicación:</strong> {{ $libro->anio_publicacion }}</p>
        </div>
        <div class="card-footer">
            @if(session('rol_id') == 1)
                <a href="{{ route('libros.edit', $libro->id_libro) }}" class="btn btn-warning">Editar</a>
            @endif
            <a href="{{ route('libros.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
    @else
        <div class="alert alert-danger">Libro no encontrado.</div>
    @endif
</div>
@endsection
