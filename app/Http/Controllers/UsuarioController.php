<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Modelo;
use Log;

class UsuarioController extends Controller
{
    private $modelo;
    public function __construct() {
        $this->modelo = Modelo::getInstancia();
    }

    public function VistaRegistro(): View {
        return view('auth.register');
    }

    public function VistaLogin(): View {
        return view('auth.login');
    }

    public function IniciarSesion(Request $request): RedirectResponse  {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $resultado = $this->modelo->autenticar($request->email, $request->password);

        if (!$resultado['valido']) {
            return back()->withErrors([
                'error' => $resultado['message'],
            ]);
        }
        
        Auth::login($resultado['usuario']);
        return redirect(route('dashboard', absolute: false));
    }

    public function RegitrarUsuario(Request $request): RedirectResponse {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[A-Za-z0-9._%+-]+@ranch.horse.com$/',
            ],
            'password' => [
                'required',
                'min:8',
                'regex:/^[A-Z]{5}[A-Za-z0-9]*horse$/',
            ],
        ]);

        $resultado = $this->modelo->registrarUsuario($request->name, $request->email, $request->password);
        if (!$resultado['valido']) {
            return back()->withErrors([
                'error' => $resultado['message'],
            ]);
        }

        Auth::login($resultado['usuario']);
        return redirect(route('dashboard', absolute: false));
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        return redirect('/');
    }
}
