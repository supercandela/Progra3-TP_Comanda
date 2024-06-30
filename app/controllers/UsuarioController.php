<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre_usuario = $parametros['nombre_usuario'];
        $clave = $parametros['clave'];
        $rol = $parametros['id_tipo'];
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $fecha_alta = $parametros['fecha_alta'];
        $fecha_baja = $parametros['fecha_baja'];
        $estado = $parametros['id_estado'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre_usuario = $nombre_usuario;
        $usr->clave = $clave;
        $usr->rol = $rol;
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->fecha_alta = $fecha_alta;
        $usr->fecha_baja = $fecha_baja;
        $usr->estado = $estado;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuarios" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['nombreUsuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        // Tomo los parámetros de la variable
        $nombre_usuario = $parametros['nombre_usuario'];
        $tipo = $parametros['id_tipo'];
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $fecha_alta = $parametros['fecha_alta'];
        $fecha_baja = $parametros['fecha_baja'];
        $estado = $parametros['id_estado'];

        // Creo el usuario
        $usr = new Usuario();
        $usr->nombre_usuario = $nombre_usuario;
        $usr->rol = $tipo;
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->fecha_alta = $fecha_alta;
        $usr->fecha_baja = $fecha_baja;
        $usr->estado = $estado;

        $usr->modificarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuarioId = $parametros['idUsuario'];
        Usuario::borrarUsuario($usuarioId);
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}