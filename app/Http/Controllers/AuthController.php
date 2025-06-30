<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDO;

class AuthController extends Controller
{
    // Solo el bibliotecario puede ver usuarios
    public function index()
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        // Aquí podrías agregar un procedimiento almacenado para listar usuarios si lo deseas
        return view('usuarios.index', ['usuarios' => []]);
    }

    public function registrar(Request $request)
    {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("BEGIN registrar_usuario(:nombre, :correo, :password, :rol_nombre, :mensaje); END;");
        $nombre = $request->input('nombre');
        $correo = $request->input('correo');
        $password = $request->input('password');
        // Determinar el nombre del rol según el id
        $rol_id = $request->input('rol_id');
        $rol_nombre = $rol_id == 1 ? 'Bibliotecario' : 'Usuario';
        $mensaje = '';
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol_nombre', $rol_nombre);
        $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        if (str_contains($mensaje, 'correctamente')) {
            return redirect()->route('login')->with('success', $mensaje);
        } else {
            return redirect()->back()->withInput()->with('error', $mensaje);
        }
    }

    public function login(Request $request)
    {
        $correo = $request->input('correo');
        $password = $request->input('password');
        $pdo = DB::connection()->getPdo();
        $sql = "BEGIN :resultado := autenticar_usuario(:correo, :password); END;";
        $resultado = null;
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':resultado', $resultado, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();
        if ($resultado > 0) {
            $usuario = DB::selectOne('SELECT * FROM usuarios WHERE id = ?', [$resultado]);
            session(['usuario_id' => $usuario->id, 'rol_id' => $usuario->rol_id]);
            return redirect()->route('libros.index')->with('success', 'Bienvenido');
        } else {
            return redirect()->back()->withInput()->with('error', 'Credenciales incorrectas');
        }
    }

    public function vistaRegistro()
    {
        return view('usuarios.create');
    }

    public function vistaLogin()
    {
        return view('usuarios.login');
    }
}

