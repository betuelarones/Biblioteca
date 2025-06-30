<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDO;

class AutorController extends Controller
{
    // Solo el bibliotecario puede gestionar autores
    public function index()
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $autores = DB::select('SELECT * FROM autores');
        return view('autores.index', compact('autores'));
    }

    public function create()
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        return view('autores.create');
    }

    public function store(Request $request)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN crear_autor(:nombre, :mensaje); END;");
        $nombre = $request->input('nombre');
        $mensaje = '';
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('autores.index')->with('success', $mensaje);
    }

    public function edit($id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $autor = DB::table('autores')->where('id', $id)->first();
        return view('autores.edit', compact('autor'));
    }

    public function update(Request $request, $id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN actualizar_autor(:id, :nombre, :mensaje); END;");
        $nombre = $request->input('nombre');
        $mensaje = '';
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('autores.index')->with('success', $mensaje);
    }

    public function destroy($id)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN eliminar_autor(:id, :mensaje); END;");
        $mensaje = '';
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return redirect()->route('autores.index')->with('success', $mensaje);
    }
}
