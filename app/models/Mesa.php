<?php

class Mesa
{
    public $id;
    public $estado;
    
    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (id, id_estado) VALUES (:id, :id_estado)");
        
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado', $this->estado, PDO::PARAM_INT);
        $consulta->execute();
    
        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT m.id, e.estado FROM mesas AS m INNER JOIN mesas_estado as e ON m.id_estado = e.id");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    
    public static function obtenerMesa($idMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT m.id, e.estado FROM mesas AS m INNER JOIN mesas_estado as e ON m.id_estado = e.id WHERE m.id = :idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
    
        return $consulta->fetchObject('Mesa');
    }

    public function modificarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET id_estado = :id_estado WHERE id = :id");
        
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado', $this->estado, PDO::PARAM_INT);

        $consulta->execute();
    }
    
    public static function borrarMesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(" DELETE FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerMesaMasUsada ()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_mesa, COUNT(*) as num_pedidos FROM pedidos GROUP BY id_mesa ORDER BY num_pedidos DESC LIMIT 1;");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_NAMED);
    }
}