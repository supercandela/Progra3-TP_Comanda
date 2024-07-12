<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_sector = $parametros['id_sector'];
        $nombre = $parametros['nombre'];
        $descripcion = $parametros['descripcion'];
        $precio = $parametros['precio'];
        $tiempo_preparacion = $parametros['tiempo_preparacion'];

        // Creamos el producto
        $prod = new Producto();
        $prod->sector = $id_sector;
        $prod->nombre = $nombre;
        $prod->descripcion = $descripcion;
        $prod->precio = $precio;
        $prod->tiempo_preparacion = $tiempo_preparacion;
        $prod->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos producto por nombre
        $prod = $args['nombreProducto'];
        $producto = Producto::obtenerProducto($prod);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        // Tomo los parámetros de la variable
        $id = $parametros['id'];
        $id_sector = $parametros['id_sector'];
        $nombre = $parametros['nombre'];
        $descripcion = $parametros['descripcion'];
        $precio = $parametros['precio'];
        $tiempo_preparacion = $parametros['tiempo_preparacion'];

        // Creo el producto
        $prod = new Producto();
        $prod->id = $id;
        $prod->sector = $id_sector;
        $prod->nombre = $nombre;
        $prod->descripcion = $descripcion;
        $prod->precio = $precio;
        $prod->tiempo_preparacion = $tiempo_preparacion;

        $prod->modificarProducto();

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $productoId = $parametros['idProducto'];
        Producto::borrarProducto($productoId);
        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarProductosDesdeCSV ($request, $response, $args) {
        $parametros = $request->getUploadedFiles();
        $csvFile = $parametros['csv'];
        if ($csvFile->getError() === UPLOAD_ERR_OK) {
            Producto::cargarDesdeCSV($csvFile);
            $payload = json_encode(array("mensaje" => "Archivo cargado con exito"));
            $response->withStatus(200);
        } else {
            $payload = json_encode(array("mensaje" => "Error al cargar el archivo."));
            $response->withStatus(400);
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function DescargarProductosACSV ($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();

        // Crea un archivo temporal para el CSV
        $archivoTemp = tmpfile();
        $metaData = stream_get_meta_data($archivoTemp);
        $filePath = $metaData['uri'];

        // Escribe los datos en el archivo temporal
        foreach ($lista as $linea) {
            fputcsv($archivoTemp, (array) $linea);
        }

        // Coloca el cursor al principio del archivo
        rewind($archivoTemp);

        // Establece las cabeceras para la descarga
        $response = $response->withHeader('Content-Type', 'aplication/csv')->withHeader('Content-Disposition', 'attachment; filename="productos.csv"');

        // Lee el contenido del archivo y escribe en el cuerpo de la respuesta
        $response->getBody()->write(stream_get_contents($archivoTemp));

        // Cierra el archivo temporal
        fclose($archivoTemp);

        return $response;
    }
}
