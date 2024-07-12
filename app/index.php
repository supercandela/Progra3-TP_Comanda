<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/Logger.php';
require_once './middlewares/Parametros.php';
require_once './middlewares/Roles.php';

require_once './utils/AutentificadorJWT.php';

require_once './controllers/AutentificadorController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductosEnPedidoController.php';
require_once './controllers/EncuestaController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{nombreUsuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('[/]', \UsuarioController::class . ':ModificarUno');
  $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
})
    ->add(new RolesMiddleware([1])) //Roles: 1 - Socio
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{nombreProducto}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
  $group->put('[/]', \ProductoController::class . ':ModificarUno');
  $group->delete('[/]', \ProductoController::class . ':BorrarUno');
  $group->post('/cargarCSV', \ProductoController::class . ':CargarProductosDesdeCSV');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos')
    ->add(new RolesMiddleware([1, 5])) //Roles: 1 - Socio / 5 - Mozo
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/{idMesa}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->put('[/]', \MesaController::class . ':ModificarUno');
  $group->delete('[/]', \MesaController::class . ':BorrarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('/estadoPedido', \PedidoController::class . ':ChequearEstadoPedido');

  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{idPedido}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno')
    ->add(\ParametrosMiddleware::class . ':crearPedidoMW')
    ->add(new RolesMiddleware([1, 5])) //Roles: 1 - Socio / 5 - Mozo
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->delete('[/]', \PedidoController::class . ':BorrarUno');

  $group->put('/modificarEstado', \PedidoController::class . ':ModificarEstadoPedido')
    ->add(new RolesMiddleware([1, 5])) //Roles: 1 - Socio / 5 - Mozo
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');

  $group->put('/cerrar', \PedidoController::class . ':CerrarPedido')
    ->add(new RolesMiddleware([1])) //Roles: 1 - Socio
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->post('/descargarCuentaEnPDF', \PedidoController::class . ':descargarCuentaEnPDF')
    ->add(new RolesMiddleware([1])) //Roles: 1 - Socio
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
});

$app->group('/productosEnPedido', function (RouteCollectorProxy $group) {
  $group->get('/cocina', \ProductosEnPedidoController::class . ':TraerTodos')
    ->add(new RolesMiddleware([1, 4])) //Roles: 1 - Socio / 4 - Cocinero
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/candybar', \ProductosEnPedidoController::class . ':TraerTodos')
    ->add(new RolesMiddleware([1, 6])) //Roles: 1 - Socio / 6 - Pastelero
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/cerveceria', \ProductosEnPedidoController::class . ':TraerTodos')
    ->add(new RolesMiddleware([1, 3])) //Roles: 1 - Socio / 3 - Cervecero
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/barra', \ProductosEnPedidoController::class . ':TraerTodos')
    ->add(new RolesMiddleware([1, 2])) //Roles: 1 - Socio / 2 - Bartender
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->put('/productos', \ProductosEnPedidoController::class . ':ModificarUno')
    ->add(new RolesMiddleware([1, 2, 3, 4, 5, 6])) //Roles: 1 - Socio / 2 - Bartender / 3 - Cervecero / 4 - Cocinero / 5 - Mozo / 6 - Pastelero
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/en-cola', \ProductosEnPedidoController::class . ':TraerTodosPorEstado')
    ->add(new RolesMiddleware([1])) //Roles: 1 - Socio 
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/en-preparacion', \ProductosEnPedidoController::class . ':TraerTodosPorEstado')
    ->add(new RolesMiddleware([1])) //Roles: 1 - Socio 
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/listo-servir', \ProductosEnPedidoController::class . ':TraerTodosPorEstado')
    ->add(new RolesMiddleware([1, 5])) //Roles: 1 - Socio / 5 - Mozo
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');
  $group->get('/entregado', \ProductosEnPedidoController::class . ':TraerTodosPorEstado')
    ->add(new RolesMiddleware([1, 5])) //Roles: 1 - Socio / 5 - Mozo
    ->add(\ParametrosMiddleware::class . ':bearerTokenMW');

});

$app->group('/encuestas', function (RouteCollectorProxy $group) {
  $group->put('[/]', \EncuestaController::class . ':ModificarUno');
});

$app->group('/auth', function (RouteCollectorProxy $group) {
  $group->post('[/]', \AutentificadorController::class . ':LoginUsuario')
    ->add(\ParametrosMiddleware::class . ':autenticarUsuarioMW');
});

$app->run();
