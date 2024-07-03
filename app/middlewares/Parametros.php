<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ParametrosMiddleware
{
    public function autenticarUsuarioMW (Request $request, RequestHandler $handler)
    {
        $parametros = $request->getParsedBody();

        if (isset($parametros['usuario']) && isset($parametros['clave']))
        {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Usuario o contrasena no definidos.")));
        }
        return $response;
    }

    public function bearerTokenMW (Request $request, RequestHandler $handler) {
        $header = $request->getHeaderLine('Authorization');

        if ($header !== "")
        {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "El usuario no esta loggeado.")));
        }
        return $response;
    }

    public function crearPedidoMW (Request $request, RequestHandler $handler)
    {
        $parametros = $request->getParsedBody();

        if (isset($parametros['id_mesa']) && isset($parametros['nombre_cliente']) && isset($parametros['id_estado_pedido'])
            && isset($parametros['id_mozo']) && isset($parametros['productos_en_pedido']))
        {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Alguno de los parametros no esta seteado correctamente.")));
        }
        return $response;
    }
}
