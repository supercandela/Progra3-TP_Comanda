<?php

require_once 'Producto_en_Pedido.php';

class Pedido
{
    public $id;
    public $id_mesa;
    public $cliente_nombre;
    public $estado_pedido;
    public $inicio_preparacion;
    public $hora_entrega;
    public $mozo;
    public $precio_final;
    public $productos_en_pedido;

    private static function generarIdAleatorio($longitud = 5) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $idAleatorio = '';
        $maxIndex = strlen($caracteres) - 1;
    
        for ($i = 0; $i < $longitud; $i++) {
            $idAleatorio .= $caracteres[rand(0, $maxIndex)];
        }
    
        return $idAleatorio;
    }
    
    public function crearPedido()
    {
        do {
            //CREAR ID ALEATORIO
            $idAleatorio = Pedido::generarIdAleatorio();
            //CHEQUEAR QUE NO EXISTE EN LA DB
            $listaIds = Pedido::obtenerIdsDeTodos();
            $existe = in_array($idAleatorio, $listaIds);
            // SI EXISTE CREAR OTRO
        } while($existe);
        //GUARDAR EN LA DB
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (id, id_mesa, cliente_nombre, id_estado_pedido, inicio_preparacion, id_mozo) VALUES (:id, :id_mesa, :cliente_nombre, :id_estado_pedido, :inicio_preparacion, :id_mozo)");
        
        $consulta->bindValue(':id', $idAleatorio, PDO::PARAM_STR);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':cliente_nombre', $this->cliente_nombre, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado_pedido', $this->estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':inicio_preparacion', $this->inicio_preparacion, PDO::PARAM_STR);
        $consulta->bindValue(':id_mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->execute();

        //Guardo cada producto del pedido
        foreach ($this->productos_en_pedido as $item) {
            //Lo guardo en su tabla
            Producto_en_Pedido::crearProductoEnPedido($idAleatorio, $item["id_producto"], $item["cantidad"]);
        }
    } 

    public static function obtenerIdsDeTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id FROM pedidos");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.id_mesa, p.cliente_nombre, e.estado as estado_pedido, p.inicio_preparacion, p.hora_entrega, u.nombre as mozo, p.precio_final FROM pedidos as p INNER JOIN pedidos_estado as e ON p.id_estado_pedido = e.id INNER JOIN usuarios as u ON p.id_mozo = u.id");
        $consulta->execute();
    
        $data = $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');

        //CON LA LISTA DE PEDIDOS, TOMAR CADA PEDIDO
        //PASARLO A LA TABLA DE PRODUCTOS EN PEDIDO
        foreach ($data as $pedido) {
            //TRAER TODOS LOS QUE COINCIDAN CON EL ID DEL PEDIDO
            $prod = Producto_en_Pedido::obtenerProductosPorIdDePedido($pedido->id);
            //AGREGARLOS A PRODUCTOS EN PEDIDO EN EL OBJETO
            if (!is_bool($prod)) {
                $pedido->productos_en_pedido = $prod;
            }
        }
        //RETORNAR EL OBJETO PEDIDO CON TODOS LOS PRODUCTOS ASOCIADOS
        return $data;
    }
    
    public static function obtenerPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.id_mesa, p.cliente_nombre, e.estado as estado_pedido, p.inicio_preparacion, p.hora_entrega, u.nombre as mozo, p.precio_final FROM pedidos as p INNER JOIN pedidos_estado as e ON p.id_estado_pedido = e.id INNER JOIN usuarios as u ON p.id_mozo = u.id WHERE p.id = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
        $consulta->execute();
    
        $data =  $consulta->fetchObject('Pedido');

        //TRAER TODOS LOS QUE COINCIDAN CON EL ID DEL PEDIDO
        $prod = Producto_en_Pedido::obtenerProductosPorIdDePedido($data->id);
        //AGREGARLOS A PRODUCTOS EN PEDIDO EN EL OBJETO
        if (!is_bool($prod)) {
            $data->productos_en_pedido = $prod;
        }
        return $data;
    }
    
    public function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET id_mesa = :id_mesa, cliente_nombre = :cliente_nombre, id_estado_pedido = :id_estado_pedido, hora_entrega = :hora_entrega, id_mozo = :id_mozo WHERE id = :id");
        
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':cliente_nombre', $this->cliente_nombre, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado_pedido', $this->estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':hora_entrega', $this->hora_entrega, PDO::PARAM_STR);
        $consulta->bindValue(':id_mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->execute();

        //Actualizo cada producto del pedido
        foreach ($this->productos_en_pedido as $item) {
            //Lo guardo en su tabla
            Producto_en_Pedido::modificarProductoEnPedido($this->id, $item["id_producto"], $item["cantidad"], $item["id_usuario_preparacion"], $item["id_estado_pedido"]);
        }
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(" DELETE FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        Producto_en_Pedido::borrarProductosEnPedido($id);
    }
}