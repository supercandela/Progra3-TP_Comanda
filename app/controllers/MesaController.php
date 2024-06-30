<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $id_estado = $parametros['id_estado'];

        // Creamos el producto
        $mesa = new Mesa();
        $mesa->id = $id;
        $mesa->estado = $id_estado;
        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos mesa por id
        $idMesa = $args['idMesa'];
        $mesa = Mesa::obtenerMesa($idMesa);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        // Tomo los parámetros de la variable
        $id = $parametros['id'];
        $id_estado = $parametros['id_estado'];

        $mesa = new Mesa();
        $mesa->id = $id;
        $mesa->estado = $id_estado;

        $mesa->modificarMesa();

        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $mesaId = $parametros['idMesa'];
        Mesa::borrarMesa($mesaId);
        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
