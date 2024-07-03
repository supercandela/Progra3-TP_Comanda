<?php

class Producto_en_Pedido
{
    public $producto;
    public $cantidad;
    public $usuario_preparacion;
    public $estado_pedido;
    public $tiempo_preparacion_prod;

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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT prod.nombre as producto, pp.cantidad as cantidad, u.nombre as usuario_preparacion, e.estado as estado_pedido, pp.tiempo_preparacion as tiempo_preparacion_prod FROM productos_en_pedido as pp INNER JOIN productos as prod on pp.id_producto = prod.id INNER JOIN usuarios as u ON pp.id_usuario_preparacion = u.id INNER JOIN pedidos_estado as e ON pp.id_estado_pedido = e.id WHERE pp.id_pedido = :id_pedido;");
        
        $consulta->bindValue(':id_pedido', $idPedido, PDO::PARAM_STR);
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto_en_Pedido');
    }

    public static function modificarProductoEnPedido($id_pedido, $id_producto, $cantidad, $id_usuario_preparacion, $id_estado_pedido, $tiempo_preparacion_prod)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) AS cnt FROM productos_en_pedido WHERE id_pedido = :id_pedido AND id_producto = :id_producto;");

        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->execute();
        $data = $consulta->fetchColumn();

        if ($data) {
            //Update del registro
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE productos_en_pedido SET cantidad = :cantidad, id_usuario_preparacion = :id_usuario_preparacion, id_estado_pedido = :id_estado_pedido, tiempo_preparacion = :tiempo_preparacion_prod WHERE id_pedido = :id_pedido AND id_producto = :id_producto;");
        } else {
            //Insert del registro
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO productos_en_pedido (id_pedido, id_producto, cantidad, id_usuario_preparacion, id_estado_pedido, tiempo_preparacion) VALUES (:id_pedido, :id_producto, :cantidad, :id_usuario_preparacion, :id_estado_pedido, :tiempo_preparacion_prod);");
        }

        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':id_usuario_preparacion', $id_usuario_preparacion, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado_pedido', $id_estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_preparacion_prod', $tiempo_preparacion_prod, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProductosEnPedido($id_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM productos_en_pedido WHERE id_pedido = :id_pedido");
        
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->execute();
    }
}
