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
}
