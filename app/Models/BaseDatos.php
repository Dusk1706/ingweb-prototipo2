<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class BaseDatos 
{
    public function iniciarTransaccion()
    {
        return DB::beginTransaction();
    }

    public function finalizarTransaccion()
    {
        return DB::commit();
    }

    public function cancelarTransaccion()
    {
        return DB::rollBack();
    }

    public function guardarObjeto($objeto)
    {
        return $objeto->save();
    }

public function getUsuario($Correo)
{
    $usuario = DB::table('users')->lockForUpdate()->where('email', $Correo)->first();
    
    if ($usuario == null) {
        return null;//No exite el usuario con ese PK
    }
    
    return new Usuario(
        $usuario->name,
        $usuario->email,
        $usuario->password,
        $usuario->ultimo_acceso,
        $usuario->numero_intentos,
        $usuario->bloqueado
    );
}

}