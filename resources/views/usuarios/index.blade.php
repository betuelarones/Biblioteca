@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Lista de Usuarios</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('rol_id') == 1)
        <a href="{{ url('/usuarios/create') }}" class="btn btn-primary mb-3">Registrar Usuario</a>
    @endif
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @php
                $correosMostrados = [];
            @endphp
            @foreach($usuarios as $usuario)
                @php
                    $correo = $usuario['CORREO'] ?? $usuario['correo'];
                    $id_usuario = $usuario['ID_USUARIO'] ?? $usuario['id_usuario'];
                @endphp
                @if(!in_array($correo, $correosMostrados) && $id_usuario != session('usuario_id'))
                    <tr>
                        <td>{{ $id_usuario }}</td>
                        <td>{{ $usuario['NOMBRE'] ?? $usuario['nombre'] }}</td>
                        <td>{{ $correo }}</td>
                        <td>{{ $usuario['ROL'] ?? $usuario['rol'] }}</td>
                        <td>
                            @if(session('rol_id') == 1)
                                <a href="{{ url('/usuarios/'.$id_usuario.'/edit') }}" class="btn btn-warning btn-sm">Editar</a>
                                <a href="{{ url('/usuarios/'.$id_usuario) }}" class="btn btn-info btn-sm">Ver</a>
                                <form action="{{ route('usuarios.destroy', $id_usuario) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                                </form>
                            @else
                                <a href="{{ url('/usuarios/'.$id_usuario) }}" class="btn btn-info btn-sm">Ver</a>
                            @endif
                        </td>
                    </tr>
                    @php $correosMostrados[] = $correo; @endphp
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
