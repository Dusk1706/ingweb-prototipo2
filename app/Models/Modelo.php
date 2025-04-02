<?php

namespace App\Models;

use Hash;
use Illuminate\Support\Facades\Auth;

class Modelo
{
    private $baseDatos;
    public function __construct()
    {
        $this->baseDatos = new BaseDatos();
    }

    public function autenticar($correo, $nip)
    {
        $this->baseDatos->iniciarTransaccion();
        $usuario = $this->baseDatos->getUsuario($correo);
       
        if ($usuario == null) {
            return "error";
        }

        if ($usuario->getBloqueado() == 1) {

            if ($usuario->getUltimoAcceso() < 30) {

                return "error falta x tiempo";
            }
            $usuario->setIntentos(0);
            $usuario->setBloqueado(0);
        }

        $intentos = $usuario->getNumeroIntentos();
        if (!Hash::check($nip, $usuario->getPassword())) {
            $usuario->setIntentos($intentos + 1);

            if ($usuario->getNumeroIntentos() >= 3) {
                $usuario->setUltimoAcceso(time());
                $usuario->setBloqueado(1);
                return "error";
            }
            return "error";//aumentar contador de intentos si llega a tres se bloquea
        }
        
        if( Auth::check()) {
            return "error ya existe una sesion activa";
        }

        $this->baseDatos->finalizarTransaccion();
        return "ok";
    }

}
