<?php
require_once './models/Producto_en_Pedido.php';
require_once './interfaces/IApiUsable.php';

class ProductosEnPedidoController extends Producto_en_Pedido
{
    public function TraerTodos($request, $response, $args)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        $sector = str_replace("/productosEnPedido/", "", $path);
        switch ($sector) {
            case "cocina":
                $sectorAListar = 1;
                break;
            case "candybar":
                $sectorAListar = 2;
                break;
            case "cerveceria":
                $sectorAListar = 3;
                break;
            case "barra":
                $sectorAListar = 4;
                break;
            default:
                $sectorAListar = 1; //Cocina
                break;
        }
        $lista = Producto_en_Pedido::listarProductosPorSector($sectorAListar);
        $payload = json_encode(array("listaPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno ($request, $response, $args) 
    {
        $parametros = $request->getParsedBody();
        // Tomo los parámetros de la variable

        $idProductoEnPedido = $parametros['id'];
        $usuario_preparacion = $parametros['usuario_preparacion'];
        $estado_pedido = $parametros['estado_pedido'];
        $tiempo_preparacion_prod = $parametros['tiempo_preparacion_prod'];

        Producto_en_Pedido::cambiarEstadoProductoEnPedidoPorId($idProductoEnPedido, $usuario_preparacion, $estado_pedido, $tiempo_preparacion_prod);

        $payload = json_encode(array("mensaje" => "Producto en pedido modificado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorEstado($request, $response, $args)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        $estado = str_replace("/productosEnPedido/", "", $path);
        switch ($estado) {
            case "en-cola":
                $estadoAListar = 1;
                break;
            case "en-preparacion":
                $estadoAListar = 2;
                break;
            case "listo-servir":
                $estadoAListar = 3;
                break;
            case "entregado":
                $estadoAListar = 4;
                break;
            default:
                $estadoAListar = 1; //en-cola
                break;
        }
        $lista = Producto_en_Pedido::listarProductosPorEstado($estadoAListar);
        $payload = json_encode(array("Lista" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
