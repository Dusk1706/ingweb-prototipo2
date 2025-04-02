<?php

namespace App\Models;

use Hash;
use Illuminate\Support\Facades\Auth;

class Modelo
{
    private static $instancia;
    private $baseDatos;

    private function __construct()
    {
        $this->baseDatos = new BaseDatos();
    }

    public static function getInstancia()
    {
        if (is_null(self::$instancia)) {
            self::$instancia = new Modelo();
        }

        return self::$instancia;
    }

    public function registrarUsuario($nombre, $correo, $nip)
    {
        try {

            $this->baseDatos->iniciarTransaccion();

            $usuario = $this->baseDatos->crearUsuario($nombre, $correo, Hash::make($nip));

            $this->baseDatos->finalizarTransaccion();
            return [
                'valido' => true,
                'message' => 'Usuario registrado exitosamente',
                'usuario' => $usuario
            ];

        } catch (\Exception $e) {
            $this->baseDatos->cancelarTransaccion();
            return [
                'valido' => false,
                'message' => 'Error al iniciar la transacción'
            ];
        }

    }




    public function autenticar($correo, $nip)
    {
        $this->baseDatos->iniciarTransaccion();
        $usuario = $this->baseDatos->getUsuario($correo);

        if ($usuario == null) {
            return [
                'valido' => false,
                'message' => 'Usuario no encontrado'
            ];
        }

        if ($usuario->getBloqueado() == 1) {
            if ($usuario->getUltimoAcceso() < 30) {
                return [
                    'valido' => false,
                    'message' => 'Cuenta bloqueada, inténtelo más tarde'
                ];
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
                return [
                    'valido' => false,
                    'message' => 'Cuenta bloqueada por demasiados intentos'
                ];
            }
            return [
                'valido' => false,
                'message' => 'Credenciales incorrectas'
            ];
        }

        if (Auth::check()) {
            return [
                'valido' => false,
                'message' => 'Ya existe una sesión activa'
            ];
        }

        $this->baseDatos->finalizarTransaccion();
        return [
            'valido' => true,
            'usuario' => $this->baseDatos->getUsuarioAuth($correo),
            'message' => 'Autenticación exitosa'
        ];
    }

}
