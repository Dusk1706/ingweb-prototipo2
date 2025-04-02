<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Modelo;

class UsuarioController extends Controller
{
    private $modelo;
    public function __construct()
    {
        $this->modelo = Modelo::getInstancia();
    }
    public function VistaRegistro(): View
    {
        return view('auth.register');
    }

    public function VistaLogin(): View
    {
        return view('auth.login');
    }



    public function IniciarSesion(Request $request): RedirectResponse
    {

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $resultado = $this->modelo->autenticar($request->email, $request->password);

        if (!$resultado['valido']) {
            return back()->withErrors([
                'email' => $resultado['message'],
            ]);
        }
        
        $usuario = $resultado['usuario'];
        Auth::login($usuario);
        return redirect(route('dashboard', absolute: false));
    }



    public function RegitrarUsuario(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.com$/',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
               // 'regex:/^(?=.{8,})(?=.*[!@#$%^&*(),.?":{}|<>])[A-Z].*$/'
            ],
        ]);
        $resultado = $this->modelo->registrarUsuario($request->name, $request->email, $request->password);
        if (!$resultado['valido']) {
            return back()->withErrors([
                'email' => $resultado['message'],
            ]);
        }

        Auth::login($resultado['usuario']);

        return redirect(route('dashboard', absolute: false));
    }
}
