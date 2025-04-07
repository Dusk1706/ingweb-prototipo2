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

            $usuario = $this->baseDatos->crearUsuario($nombre, $correo, Hash::make($nip));
            if ($usuario == null) {
                $this->baseDatos->cancelarTransaccion();
                return [
                    'valido' => false,
                    'message' => 'Error al registrar el usuario'
                ];
            }
            $this->baseDatos->crearSemaforoUsuario($usuario->getEmail());
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

    public function cerrarSesion($usuario) {
        $this->baseDatos->iniciarTransaccion();
        $usuarioModel = $this->baseDatos->getUsuario($usuario->email);

        $this->baseDatos->updateUsuario($usuarioModel);
        $this->baseDatos->finalizarTransaccion();
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

            if ($this->baseDatos->existeSesion($usuario->getId())) {
                $this->baseDatos->cancelarTransaccion();

                return [
                    'valido' => false,
                    'message' => 'Usuario activo en otra sesion'
                ];
            }

            if ($usuario->estaBloqueado()) {
                $tiempoBloqueo = (time() - $usuario->getUltimoAcceso()) / 60;
                $minutosBloqueado = 1;
                if ($tiempoBloqueo < $minutosBloqueado) {
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
            Log::info('Error en la autenticación: ' . $e->getMessage());
            return [
                'valido' => false,
                'message' => 'Error durante la autenticación: ' . $e->getMessage()
            ];
        }
    }

}
