<?php

class Producto_en_Pedido
{
    public $id_producto;
    public $cantidad;
    public $id_usuario_preparacion;
    public $id_estado_pedido;

    public static function crearProductoEnPedido($id_pedido, $id_producto, $cantidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos_en_pedido (id_pedido, id_producto, cantidad) VALUES (:id_pedido, :id_producto, :cantidad)");
        
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);            
        $consulta->execute();
    
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerProductosPorIdDePedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_producto, cantidad, id_usuario_preparacion, id_estado_pedido FROM productos_en_pedido WHERE id_pedido = :id_pedido");
        
        $consulta->bindValue(':id_pedido', $idPedido, PDO::PARAM_STR);
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto_en_Pedido');
    }
}