<?php
require_once './models/Encuesta.php';

class EncuestaController extends Encuesta
{
    public function ModificarUno ($request, $response, $args) 
    {
        $parametros = $request->getParsedBody();
        $id_mesa = $parametros['id_mesa'];
        $id_pedido = $parametros['id_pedido'];
        $nota_restaurante = $parametros['nota_restaurante'];
        $nota_mesa = $parametros['nota_mesa'];
        $nota_mozo = $parametros['nota_mozo'];
        $nota_cocinero = $parametros['nota_cocinero'];
        $comentarios = $parametros['comentarios'];

        $encuesta = Encuesta::traerEncuestaPorMesaYPedido($id_mesa, $id_pedido);

        if ($encuesta) {
            $encuesta->nota_restaurante = $nota_restaurante;
            $encuesta->nota_mesa = $nota_mesa;
            $encuesta->nota_mozo = $nota_mozo;
            $encuesta->nota_cocinero = $nota_cocinero;
            $encuesta->comentarios = $comentarios;
            $encuesta->completarEncuesta();
            $payload = json_encode(array("mensaje" => "Encuesta modificada con éxito."));
        } else {
            $payload = json_encode(array("mensaje" => "No existe una encuesta habilitada para este pedido."));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("Encuestas" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


}
