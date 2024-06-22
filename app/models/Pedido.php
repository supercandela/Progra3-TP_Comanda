<?php

require_once 'Producto_en_Pedido.php';

class Pedido
{
    public $id;
    public $id_mesa;
    public $cliente_nombre;
    public $id_estado_pedido;
    public $inicio_preparacion;
    public $hora_entrega;
    public $id_mozo;
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
        $consulta->bindValue(':id_estado_pedido', $this->id_estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':inicio_preparacion', $this->inicio_preparacion, PDO::PARAM_STR);
        $consulta->bindValue(':id_mozo', $this->id_mozo, PDO::PARAM_INT);
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
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
    
    // public static function obtenerPedido($nombreProducto)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE nombre = :nombreProducto");
    //     $consulta->bindValue(':nombreProducto', $nombreProducto, PDO::PARAM_STR);
    //     $consulta->execute();
    
    //     return $consulta->fetchObject('Producto');
    // }



    /*
    

    
    
    public function modificarProducto()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET id_sector = :id_sector, nombre = :nombre, descripcion = :descripcion, precio = :precio, tiempo_preparacion = :tiempo_preparacion WHERE id = :id");
        
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_INT);
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

    */
}