<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDO;

class CategoriaController extends Controller
{
    // Solo el bibliotecario puede gestionar categorías
    public function index()
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $categorias = DB::select('SELECT * FROM categorias');
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN SP_REGISTRAR_CATEGORIA(:nombre, :mensaje); END;");
        $nombre = $request->input('nombre');
        $mensaje = '';
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('categorias.index')->with('success', $mensaje);
    }

    public function edit($id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $categoria = DB::table('categorias')->where('id_categoria', $id)->first();
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN SP_EDITAR_CATEGORIA(:id_categoria, :nombre, :mensaje); END;");
        $nombre = $request->input('nombre');
        $mensaje = '';
        $stmt->bindParam(':id_categoria', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('categorias.index')->with('success', $mensaje);
    }

    public function destroy($id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN SP_ELIMINAR_CATEGORIA(:id_categoria, :mensaje); END;");
        $mensaje = '';
        $stmt->bindParam(':id_categoria', $id);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('categorias.index')->with('success', $mensaje);
    }
}
