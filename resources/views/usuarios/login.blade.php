@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 400px;">
    <h2 class="mb-4">Iniciar Sesión</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Correo:</label>
            <input type="email" name="correo" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
</div>
@endsection
