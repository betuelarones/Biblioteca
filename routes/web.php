<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AutorController;

// Registro y login
Route::get('/login', [AuthController::class, 'vistaLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/usuarios/create', [AuthController::class, 'vistaRegistro'])->name('usuarios.create');
Route::post('/usuarios', [AuthController::class, 'registrar'])->name('usuarios.store');

// Logout
Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('login');
})->name('logout');

Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');

// Solo bibliotecario puede gestionar libros, autores y categorías
Route::middleware(['web', 'solo.bibliotecario'])->group(function () {
    Route::get('/libros/create', [LibroController::class, 'create'])->name('libros.create');
    Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
    Route::get('/libros/{id}/edit', [LibroController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{id}', [LibroController::class, 'update'])->name('libros.update');
    Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('libros.destroy');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    Route::get('/autores', [AutorController::class, 'index'])->name('autores.index');
    Route::get('/autores/create', [AutorController::class, 'create'])->name('autores.create');
    Route::post('/autores', [AutorController::class, 'store'])->name('autores.store');
    Route::get('/autores/{id}/edit', [AutorController::class, 'edit'])->name('autores.edit');
    Route::put('/autores/{id}', [AutorController::class, 'update'])->name('autores.update');
    Route::delete('/autores/{id}', [AutorController::class, 'destroy'])->name('autores.destroy');
});

// Ruta por defecto: siempre redirige al login principal
Route::get('/', function () {
    return redirect()->route('login');
});
