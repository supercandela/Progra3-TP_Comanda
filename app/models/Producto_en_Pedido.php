<?php

class Producto_en_Pedido
{
    public $id;
    public $id_pedido;
    public $producto;
    public $cantidad;
    public $usuario_preparacion;
    public $estado_pedido;
    public $tiempo_preparacion_prod;
    public $sector;



    public static function crearProductoEnPedido($id_pedido, $id_producto, $cantidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos_en_pedido (id_pedido, id_producto, cantidad, id_usuario_preparacion, id_estado_pedido) VALUES (:id_pedido, :id_producto, :cantidad, 1000, 1)");

        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerProductosPorIdDePedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT prod.nombre as producto, pp.cantidad as cantidad, u.nombre_usuario as usuario_preparacion, e.estado as estado_pedido, pp.tiempo_preparacion as tiempo_preparacion_prod FROM productos_en_pedido as pp INNER JOIN productos as prod on pp.id_producto = prod.id INNER JOIN usuarios as u ON pp.id_usuario_preparacion = u.id INNER JOIN pedidos_estado as e ON pp.id_estado_pedido = e.id WHERE pp.id_pedido = :id_pedido;");

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
                "UPDATE productos_en_pedido SET cantidad = :cantidad, id_usuario_preparacion = :id_usuario_preparacion, id_estado_pedido = :id_estado_pedido, tiempo_preparacion = :tiempo_preparacion_prod WHERE id_pedido = :id_pedido AND id_producto = :id_producto;"
            );
        } else {
            //Insert del registro
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO productos_en_pedido (id_pedido, id_producto, cantidad, id_usuario_preparacion, id_estado_pedido, tiempo_preparacion) VALUES (:id_pedido, :id_producto, :cantidad, :id_usuario_preparacion, :id_estado_pedido, :tiempo_preparacion_prod);"
            );
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

    public static function listarProductosPorSector($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos_en_pedido.id, productos_en_pedido.id_pedido, productos.nombre as producto, sectores.descripcion as sector, productos_en_pedido.cantidad as cantidad, usuarios.nombre_usuario as usuario_preparacion, pedidos_estado.estado as estado_pedido, productos_en_pedido.tiempo_preparacion as tiempo_preparacion_prod FROM productos_en_pedido JOIN productos ON productos_en_pedido.id_producto = productos.id JOIN sectores ON productos.id_sector = sectores.id JOIN usuarios ON productos_en_pedido.id_usuario_preparacion = usuarios.id JOIN pedidos_estado ON productos_en_pedido.id_estado_pedido = pedidos_estado.id WHERE sectores.id = :sector");
        
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto_en_Pedido');
    }
}
