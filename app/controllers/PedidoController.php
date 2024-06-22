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

        $pedido = new Pedido();
        $pedido->id_mesa = $id_mesa;
        $pedido->cliente_nombre = $nombre_cliente;
        $pedido->id_estado_pedido = $id_estado_pedido;
        $pedido->inicio_preparacion = $hora_inicio_prep;
        $pedido->id_mozo = $id_mozo;
        $pedido->productos_en_pedido = $productos_en_pedido;
        $pedido->crearPedido();

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
        // // Buscamos mesa por id
        // $idMesa = $args['idMesa'];
        // $mesa = Mesa::obtenerMesa($idMesa);
        // $payload = json_encode($mesa);

        // $response->getBody()->write($payload);
        // return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        // $parametros = $request->getParsedBody();
        // // Tomo los parámetros de la variable
        // $id = $parametros['id'];
        // $id_estado = $parametros['id_estado'];

        // $mesa = new Mesa();
        // $mesa->id = $id;
        // $mesa->id_estado = $id_estado;

        // $mesa->modificarMesa();

        // $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

        // $response->getBody()->write($payload);
        // return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        // $parametros = $request->getParsedBody();
        // $mesaId = $parametros['idMesa'];
        // Mesa::borrarMesa($mesaId);
        // $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
        // $response->getBody()->write($payload);
        // return $response->withHeader('Content-Type', 'application/json');
    }
}
