<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['id_mesa'];
        $nombre_cliente = $parametros['nombre_cliente'];
        $id_estado_pedido = $parametros['id_estado_pedido'];
        $hora_inicio_prep = new DateTime();
        $hora_inicio_prep = $hora_inicio_prep->format('Y-m-d H:i:s');
        $id_mozo = $parametros['id_mozo'];
        $productos_en_pedido = json_decode($parametros['productos_en_pedido'], true);
        //Data del archivo subido
        $tipo_archivo = $_FILES['imagen']['type'];
        $tamano_archivo = $_FILES['imagen']['size'];
        $extension = "";

        //Guardar Imagen
        if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 300000)) {

            $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
        } else {
            $payload = json_encode(array("mensaje" => "La imagen no tiene un formato o tamaño que sean admitidos."));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $pedido = new Pedido();
        $pedido->id_mesa = $id_mesa;
        $pedido->cliente_nombre = $nombre_cliente;
        $pedido->estado_pedido = $id_estado_pedido;
        $pedido->inicio_preparacion = $hora_inicio_prep;
        $pedido->mozo = $id_mozo;
        $pedido->productos_en_pedido = $productos_en_pedido;
        $pedido->crearPedido($_FILES['imagen'], $extension);

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $idPedido = $args['idPedido'];
        $pedido = Pedido::obtenerPedido($idPedido);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        // Tomo los parámetros de la variable
        $id = $parametros['id'];
        $id_mesa = $parametros['id_mesa'];
        $cliente_nombre = $parametros['cliente_nombre'];
        $id_estado_pedido = $parametros['id_estado_pedido'];
        if ($id_estado_pedido == 3) {
            $hora_entrega = new DateTime();
            $hora_entrega = $hora_entrega->format('Y-m-d H:i:s');
        }
        $id_mozo = $parametros['id_mozo'];
        $productos_en_pedido = $parametros['productos_en_pedido'];

        $pedido = new Pedido();
        $pedido->id = $id;
        $pedido->id_mesa = $id_mesa;
        $pedido->cliente_nombre = $cliente_nombre;
        $pedido->estado_pedido = $id_estado_pedido;
        if ($id_estado_pedido == 3) {
            $pedido->hora_entrega = $hora_entrega;
        }
        $pedido->mozo = $id_mozo;
        $pedido->productos_en_pedido = $productos_en_pedido;

        $pedido->modificarPedido();

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $pedidoId = $parametros['idPedido'];
        Pedido::borrarPedido($pedidoId);
        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarEstadoPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        // Tomo los parámetros de la variable
        $id = $parametros['id'];
        $id_mesa = $parametros['id_mesa'];
        $mesa_estado = $parametros['mesa_estado'];
        $estado_pedido = $parametros['estado_pedido'];
        $hora_entrega = new DateTime();
        $hora_entrega = $hora_entrega->format('Y-m-d H:i:s');

        $pedido = new Pedido();
        $pedido->id = $id;
        $pedido->id_mesa = $id_mesa;
        $pedido->estado_pedido = $estado_pedido;
        $pedido->hora_entrega = $hora_entrega;

        $pedido->modificarEstados($mesa_estado);

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Cierra mesa
     * Calcula precio final del pedido
     * Habilita la encuesta
     */
    public function CerrarPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $pedidoId = $parametros['idPedido'];
        $id_mesa = $parametros['id_mesa'];
        $mesa_estado = $parametros['estado_mesa'];

        $pedido = new Pedido();
        $pedido->id = $pedidoId;
        $pedido->id_mesa = $id_mesa;

        $payload = $pedido->calcularPrecioPedidoYCerrarMesa($mesa_estado);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ChequearEstadoPedido($request, $response, $args)
    {

        $parametros = $request->getQueryParams();
        $mesa = $parametros["id_mesa"];
        $pedido = $parametros["id_pedido"];
        $payload = Pedido::verEstadoPedido($pedido, $mesa);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function descargarCuentaEnPDF($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_pedido = $parametros['id_pedido'];

        $response->getBody()->write(Pedido::DescargarCuenta($id_pedido));
        $response = $response->withHeader('Content-Type', 'application/pdf')->withHeader('Content-Disposition', 'attachment; filename="cuenta.pdf"');
        return $response;
    }
}
