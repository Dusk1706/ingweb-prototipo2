<?php

namespace App\Models;

use Hash;
use Illuminate\Support\Facades\Auth;
use Log;

class Modelo
{
    private static $instancia;
    private $baseDatos;

    private function __construct() {
        $this->baseDatos = new BaseDatos();
    }

    public static function getInstancia()
    {
        if (is_null(self::$instancia)) {
            self::$instancia = new Modelo();
        }

        return self::$instancia;
    }

    public function registrarUsuario($nombre, $correo, $nip) {
        try {

            $this->baseDatos->iniciarTransaccion();
            $nipEncriptado = Hash::make($nip);
            $usuario = $this->baseDatos->crearUsuario($nombre, $correo, $nipEncriptado);
            if ($usuario == null) {
                $this->baseDatos->cancelarTransaccion();
                return [
                    'valido' => false,
                    'message' => 'Error al registrar el usuario'
                ];
            }
            $this->baseDatos->crearSemaforoUsuario($correo);
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
                'message' => 'Error al registrar el usuario: ' . $e->getMessage()
            ];
        }
    }

    public function autenticar($correo, $nip) {
        try {
            $this->baseDatos->iniciarTransaccion();
            $semaforo = $this->baseDatos->iniciarSemaforoUsiario($correo);

            if(!$semaforo)  {
                $this->baseDatos->cancelarTransaccion();
                return [
                    'valido' => false,
                    'message' => 'Error al iniciar Sesion'
                ];
            }

            $usuario = $this->baseDatos->getUsuario($correo);
            $intentos = $usuario->getNumeroIntentos();

            if ($this->baseDatos->existeSesion($usuario->getId())) {
                $this->baseDatos->cancelarTransaccion();
                $usuario->setIntentos($intentos + 1);
                $this->baseDatos->updateUsuario($usuario);
                $this->baseDatos->finalizarTransaccion();
                return [
                    'valido' => false,
                    'message' => 'Usuario activo en otra sesion'
                ];
            }

            if ($usuario->estaBloqueado()) {
                $tiempoBloqueo = (time() - $usuario->getUltimoAcceso()) / 60;
                $minutosBloqueado = 1;
                if ($tiempoBloqueo < $minutosBloqueado) {
                    $this->baseDatos->cancelarTransaccion();
                    return [
                        'valido' => false,
                        'message' => 'Cuenta bloqueada, inténtelo más tarde'
                    ];
                }
                $usuario->setIntentos(0);
                $usuario->setBloqueado(0);
                $this->baseDatos->updateUsuario($usuario);
            }

            $intentos = $usuario->getNumeroIntentos();
            $contraseñaCorrecta = Hash::check($nip, $usuario->getPassword());
            
            if (!$contraseñaCorrecta) {
                $usuario->setIntentos($intentos + 1);
                $mensaje = 'Correo o contraseña incorrectos';
                if ($usuario->getNumeroIntentos() >= 3) {
                    $usuario->setUltimoAcceso(time());
                    $usuario->setBloqueado(1);
                    
                    $mensaje = 'Cuenta bloqueada por demasiados intentos';
                }
                
                $this->baseDatos->updateUsuario($usuario);
                
                $this->baseDatos->finalizarTransaccion();
                return [ 'valido' => false, 'message' => $mensaje ];
            }
            $usuario->setIntentos(0);
            $this->baseDatos->updateUsuario($usuario);

            $this->baseDatos->finalizarTransaccion();
            return [
                'valido' => true,
                'usuario' => $this->baseDatos->getUsuarioAuth($correo),
                'message' => 'Autenticación exitosa'
            ];
        } catch (\Exception $e) {
            $this->baseDatos->cancelarTransaccion();
            return [
                'valido' => false,
                'message' => 'Error durante la autenticación: ' . $e->getMessage()
            ];
        }
    }

}
