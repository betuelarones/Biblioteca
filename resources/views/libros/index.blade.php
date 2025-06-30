@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Lista de Libros</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('rol_id') == 1)
        <a href="{{ route('libros.create') }}" class="btn btn-primary mb-3">Registrar Libro</a>
    @endif
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Año</th>
                @if(session('rol_id') == 1)
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($libros as $libro)
                <tr>
                    <td>{{ $libro->titulo }}</td>
                    <td>{{ $libro->autor }}</td>
                    <td>{{ $libro->categoria }}</td>
                    <td>{{ $libro->anio_publicacion }}</td>
                    @if(session('rol_id') == 1)
                        <td>
                            <a href="{{ url('/libros/'.$libro->id.'/edit') }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('libros.destroy', $libro->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
