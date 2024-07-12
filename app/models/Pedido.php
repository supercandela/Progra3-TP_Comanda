<?php

use Dompdf\Dompdf;

require_once 'Producto_en_Pedido.php';
require_once 'Mesa.php';

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

    private static function generarIdAleatorio($longitud = 5)
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $idAleatorio = '';
        $maxIndex = strlen($caracteres) - 1;

        for ($i = 0; $i < $longitud; $i++) {
            $idAleatorio .= $caracteres[rand(0, $maxIndex)];
        }

        return $idAleatorio;
    }

    public function crearPedido($imagen, $extension)
    {
        do {
            //CREAR ID ALEATORIO
            $idAleatorio = Pedido::generarIdAleatorio();
            //CHEQUEAR QUE NO EXISTE EN LA DB
            $listaIds = Pedido::obtenerIdsDeTodos();
            $existe = in_array($idAleatorio, $listaIds);
            // SI EXISTE CREAR OTRO
        } while ($existe);
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

        Pedido::GuardarFoto($imagen, $idAleatorio, $this->id_mesa, $extension);

        $mesa = new Mesa();
        $mesa->id = $this->id_mesa;
        $mesa->estado = 1;
        $mesa->modificarMesa();
    }

    public static function GuardarFoto($foto, $idPedido, $idMesa, $tipo_archivo)
    {
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesPedidos/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $idPedido . "-" . $idMesa . "." . $tipo_archivo;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            echo "La imagen fue guardada exitosamente.\n\n";
        } else {
            echo "La foto no pudo ser guardada.\n\n";
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
            Producto_en_Pedido::modificarProductoEnPedido($this->id, $item["id_producto"], $item["cantidad"], $item["id_usuario_preparacion"], $item["id_estado_pedido"], $item["tiempo_preparacion_prod"]);
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


    public function modificarEstados($id_estado_mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET id_estado_pedido = :id_estado_pedido, hora_entrega = :hora_entrega WHERE id = :id");

        $consulta->bindValue(':id_estado_pedido', $this->estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':hora_entrega', $this->hora_entrega, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->execute();

        $mesa = new Mesa();
        $mesa->id = $this->id_mesa;
        $mesa->estado = $id_estado_mesa;
        $mesa->modificarMesa();
    }

    public static function calcularPrecioFinal ($id_pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        //Traer todos los productos del pedido
        $consulta = $objAccesoDato->prepararConsulta("SELECT productos.nombre, productos_en_pedido.cantidad, productos.precio FROM productos_en_pedido JOIN productos ON productos_en_pedido.id_producto = productos.id WHERE productos_en_pedido.id_pedido = :idPedido");
        $consulta->bindValue(':idPedido', $id_pedido, PDO::PARAM_STR);
        $consulta->execute();
        $data = $consulta->fetchAll(PDO::FETCH_NAMED);
        $total = 0;

        //Calcular precio del producto * cantidad
        //Sumar los valores individuales
        foreach ($data as $producto) {
            $total += $producto["cantidad"] * $producto["precio"];
        }

        $payload = json_encode(array("Precio Final" => $total, "Detalle" => $data));
        return $payload;
    }

    public function calcularPrecioPedidoYCerrarMesa($id_estado_mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        //calcular precio en pedido

        //Traer todos los productos del pedido
        $consulta = $objAccesoDato->prepararConsulta("SELECT productos.nombre, productos_en_pedido.cantidad, productos.precio FROM productos_en_pedido JOIN productos ON productos_en_pedido.id_producto = productos.id WHERE productos_en_pedido.id_pedido = :idPedido");
        $consulta->bindValue(':idPedido', $this->id, PDO::PARAM_STR);
        $consulta->execute();

        $data = $consulta->fetchAll(PDO::FETCH_NAMED);
        $total = 0;

        //Calcular precio del producto * cantidad
        //Sumar los valores individuales
        foreach ($data as $producto) {
            $total += $producto["cantidad"] * $producto["precio"];
        }

        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET precio_final = :precio WHERE id = :id");

        //Actualizo precio en pedido
        $consulta->bindValue(':precio', $total, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
        $consulta->execute();

        $payload = json_encode(array("Precio Final" => $total, "Detalle" => $data));

        //cambiar mesa a cerrada
        $mesa = new Mesa();
        $mesa->id = $this->id_mesa;
        $mesa->estado = $id_estado_mesa;
        $mesa->modificarMesa();

        //habilitar encuesta
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO encuestas (id_mesa, id_pedido) VALUES (:id_mesa, :id_pedido)");

        //Actualizo precio en pedido
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':id_pedido', $this->id, PDO::PARAM_STR);
        $consulta->execute();

        return $payload;
    }

    public static function verEstadoPedido($pedido, $mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDato->prepararConsulta("SELECT pedidos.id, pedidos.id_mesa, pedidos_estado.estado FROM pedidos JOIN pedidos_estado ON pedidos.id_estado_pedido = pedidos_estado.id WHERE pedidos.id = :pedido AND pedidos.id_mesa = :mesa");
        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $mesa, PDO::PARAM_INT);
        $consulta->execute();

        $data = $consulta->fetchAll(PDO::FETCH_NAMED);
        return json_encode($data);
    }

    public static function DescargarCuenta($id_pedido)
    {
        $dompdf = new Dompdf();
        $imagen = file_get_contents('./db/logo.png');
        $base64Image = base64_encode($imagen);
        $data = Pedido::CalcularPrecioFinal($id_pedido);
        $cuenta = json_decode($data, true);
        $html = '
            <html>
            <head>
                <style>
                    body { font-family: DejaVu Sans, sans-serif; }
                    .content { text-align: center; }
                    img { max-width: 30%; height: auto; display: inline-block; }
                    h1 {display: inline-block; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    table, th, td { border: 1px solid black; }
                    th, td { padding: 10px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <div class="content">
                    <img src="data:image/png;base64,' . $base64Image . '" />
                    <h1>Sick Sad World</h1>
                </div>
                <div>
                    <h4>Detalle del pedido</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>';

                            foreach ($cuenta['Detalle'] as $row) {
                                $html .= '
                                        <tr>
                                            <td>' . $row['cantidad'] . '</td>
                                            <td>' . $row['nombre'] . '</td>
                                            <td>$' . number_format($row['precio'], 2) . '</td>
                                        </tr>';
                            }

                            $html .=
                             '
                        </tbody>
                    </table>
                </div>
                <div>
                <h5>Total: $ ' . number_format($cuenta['Precio Final'], 2) . '</h5>
                </div>
            </body>
            </html>';

        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }
}
