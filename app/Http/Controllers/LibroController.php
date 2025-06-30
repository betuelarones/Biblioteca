<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDO;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Consulta directa equivalente al procedimiento listar_libros
        $libros = DB::select('SELECT l.id, l.titulo, l.anio_publicacion, a.nombre as autor, c.nombre as categoria
                              FROM libros l
                              JOIN autores a ON l.autor_id = a.id
                              JOIN categorias c ON l.categoria_id = c.id');
        return view('libros.index', compact('libros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $autores = DB::select('SELECT * FROM autores');
        $categorias = DB::select('SELECT * FROM categorias');
        return view('libros.create', compact('autores', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN crear_libro(:titulo, :autor_id, :categoria_id, :anio_publicacion, :mensaje); END;");
        $titulo = $request->input('titulo');
        $autor_id = $request->input('autor_id');
        $categoria_id = $request->input('categoria_id');
        $anio_publicacion = $request->input('anio_publicacion');
        $mensaje = '';
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':autor_id', $autor_id);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':anio_publicacion', $anio_publicacion);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('libros.index')->with('success', $mensaje);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $libro = DB::table('libros')
            ->leftJoin('autores', 'libros.autor_id', '=', 'autores.id')
            ->leftJoin('categorias', 'libros.categoria_id', '=', 'categorias.id')
            ->select('libros.*', 'autores.nombre as autor', 'categorias.nombre as categoria')
            ->where('libros.id', $id)
            ->first();
        if (!$libro) {
            abort(404, 'Libro no encontrado');
        }
        return view('libros.show', compact('libro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $libro = DB::table('libros')->where('id', $id)->first();
        $autores = DB::select('SELECT * FROM autores');
        $categorias = DB::select('SELECT * FROM categorias');
        return view('libros.edit', compact('libro', 'autores', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN actualizar_libro(:id, :titulo, :autor_id, :categoria_id, :anio_publicacion, :mensaje); END;");
        $titulo = $request->input('titulo');
        $autor_id = $request->input('autor_id');
        $categoria_id = $request->input('categoria_id');
        $anio_publicacion = $request->input('anio_publicacion');
        $mensaje = '';
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':autor_id', $autor_id);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':anio_publicacion', $anio_publicacion);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('libros.index')->with('success', $mensaje);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN eliminar_libro(:id, :mensaje); END;");
        $mensaje = '';
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('libros.index')->with('success', $mensaje);
    }
}
