<?php

class Producto
{
    public $id;
    public $sector;
    public $nombre;
    public $descripcion;
    public $precio;
    public $tiempo_preparacion;
    
    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (id_sector, nombre, descripcion, precio, tiempo_preparacion) VALUES (:id_sector, :nombre, :descripcion, :precio, :tiempo_preparacion)");
              
        $consulta->bindValue(':id_sector', $this->sector, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
        $consulta->execute();
    
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta = $objAccesoDatos->prepararConsulta("SELECT prod.id, prod.nombre, prod.descripcion, sec.descripcion as sector, prod.precio, prod.tiempo_preparacion FROM productos as prod INNER JOIN sectores as sec ON prod.id_sector = sec.id");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
    
    public static function obtenerProducto($nombreProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT prod.id, prod.nombre, prod.descripcion, sec.descripcion as sector, prod.precio, prod.tiempo_preparacion FROM productos as prod INNER JOIN sectores as sec ON prod.id_sector = sec.id WHERE prod.nombre LIKE :nombreProducto");
        $consulta->bindValue(':nombreProducto', "%". $nombreProducto . "%", PDO::PARAM_STR);
        $consulta->execute();
    
        return $consulta->fetchObject('Producto');
    }
    
    public function modificarProducto()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET id_sector = :id_sector, nombre = :nombre, descripcion = :descripcion, precio = :precio, tiempo_preparacion = :tiempo_preparacion WHERE id = :id");
        
        $consulta->bindValue(':id_sector', $this->sector, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);

        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(" DELETE FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}