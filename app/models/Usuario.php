<?php

class Usuario
{
    public $id;
    public $nombre_usuario;
    public $clave;
    // public $id_tipo;
    public $rol;
    public $nombre;
    public $apellido;
    public $fecha_alta;
    public $fecha_baja;
    public $estado;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre_usuario, clave, id_tipo, nombre, apellido, fecha_alta, fecha_baja, id_estado) VALUES (:nombre_usuario, :clave, :id_tipo, :nombre, :apellido, :fecha_alta, :fecha_baja, :id_estado)");

        $consulta->bindValue(':nombre_usuario', $this->nombre_usuario, PDO::PARAM_STR);
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':id_tipo', $this->rol, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', $this->fecha_alta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_baja', $this->fecha_baja, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $this->estado, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.nombre_usuario, usuarios.clave, usuarios.nombre, usuarios.apellido, tipo.rol, usuarios.fecha_alta, usuarios.fecha_baja, estado.descripcion as estado FROM usuarios as usuarios INNER JOIN usuarios_tipo as tipo ON usuarios.id_tipo = tipo.id INNER JOIN usuarios_estado as estado ON usuarios.id_estado = estado.id;");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($nombreUsuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE nombre_usuario = :usuario");
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.nombre_usuario, usuarios.clave, usuarios.nombre, usuarios.apellido, tipo.rol, usuarios.fecha_alta, usuarios.fecha_baja, estado.descripcion as estado FROM usuarios as usuarios INNER JOIN usuarios_tipo as tipo ON usuarios.id_tipo = tipo.id INNER JOIN usuarios_estado as estado ON usuarios.id_estado = estado.id WHERE usuarios.nombre_usuario = :usuario;");
        $consulta->bindValue(':usuario', $nombreUsuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function obtenerUsuarioParaLogin($nombreUsuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.nombre_usuario, usuarios.clave, usuarios.id_tipo as rol FROM usuarios WHERE usuarios.nombre_usuario = :usuario;");
        $consulta->bindValue(':usuario', $nombreUsuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET id_tipo = :id_tipo, nombre = :nombre, apellido = :apellido, fecha_alta = :fecha_alta, fecha_baja = :fecha_baja, id_estado = :id_estado WHERE nombre_usuario = :nombre_usuario");

        $consulta->bindValue(':id_tipo', $this->rol, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', $this->fecha_alta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_baja', $this->fecha_baja, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $this->estado, PDO::PARAM_INT);
        $consulta->bindValue(':nombre_usuario', $this->nombre_usuario, PDO::PARAM_STR);

        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(" DELETE FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}