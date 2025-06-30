@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Categorías</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('rol_id') == 1)
        <a href="{{ route('categorias.create') }}" class="btn btn-primary mb-3">Registrar Categoría</a>
    @endif
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                @if(session('rol_id') == 1)
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    @if(session('rol_id') == 1)
                        <td>
                            <a href="{{ url('/categorias/'.$categoria->id.'/edit') }}" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
