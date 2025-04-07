<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class BaseDatos {
    public function iniciarTransaccion() {
        return DB::beginTransaction();
    }

    public function finalizarTransaccion() {
        return DB::commit();
    }

    public function cancelarTransaccion() {
        return DB::rollBack();
    }

    public function guardarObjeto($objeto) {
        return $objeto->save();
    }

    public function updateUsuario($usuario) {
        DB::table('users')->where('email', $usuario->getEmail())->update([
            'ultimo_acceso' => $usuario->getUltimoAcceso(),
            'numero_intentos' => $usuario->getNumeroIntentos(),
            'bloqueado' => $usuario->estaBloqueado(),
        ]);
    }

    public function getUsuario($Correo)
    {
        $usuario = $this->getUsuarioAuth($Correo);

        if ($usuario == null) {
            return null;//No exite el usuario con ese PK
        }

        return new Usuario(
            $usuario->id,
            $usuario->name,
            $usuario->email,
            $usuario->password,
            $usuario->ultimo_acceso,
            $usuario->numero_intentos,
            $usuario->bloqueado,
        );
    }

    public function getUsuarioAuth($Correo) {
        return User::where('email', $Correo)->first();
    }

    public function crearUsuario($nombre, $correo, $nip)
    {
        if (User::where('email', $correo)->exists()) {
            return null;
        }

        $user = User::create([
            'name' => $nombre,
            'email' => $correo,
            'password' => $nip,
        ]);
        return $user;
    }

    public function existeSesion($idUsuario) {
        $sesion = DB::table('sessions')->where('user_id', $idUsuario)->first();
        return $sesion != null;
    }

    public function crearSemaforoUsuario($idUsuario) {
        DB::table('usuarios_concurrencia')->insert([
            'user_id' => $idUsuario,
            'semaforo' => false,
        ]);
    }

    public function iniciarSemaforoUsiario($correo) {
        $tupla = DB::table('usuarios_concurrencia')->where('email', $correo)->lockForUpdate()->first();
        return $tupla != null;
    }
}