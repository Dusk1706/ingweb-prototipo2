<?php

namespace App\Models;

class Usuario
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $ultimo_acceso;
    private $numero_intentos;
    private $bloqueado;
    
    public function __construct($id, $name, $email, $password, $ultimo_acceso, $numero_intentos, $bloqueado) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->ultimo_acceso = $ultimo_acceso;
        $this->numero_intentos = $numero_intentos;
        $this->bloqueado = $bloqueado;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUltimoAcceso() {
        return $this->ultimo_acceso;
    }

    public function getNumeroIntentos() {
        return $this->numero_intentos;
    }

    public function getBloqueado() {
        return $this->bloqueado;
    }

    public function setIntentos($intentos) {
        $this->numero_intentos = $intentos;
    }

    public function setUltimoAcceso($ultimo_acceso) {
        $this->ultimo_acceso = $ultimo_acceso;
    }

    public function setBloqueado($bloqueado) {
        $this->bloqueado = $bloqueado;
    }
}