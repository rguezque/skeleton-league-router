<?php declare(strict_types = 1);

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;

if(file_exists($maintenance = __DIR__.'/templates/site/maintenance.php')) {
    require $maintenance;
    exit;
}

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../bootstrap/app.php';
/** @var Router Definición de rutas */
$app = require __DIR__.'/../routes/web.php';


try {
    $request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    $response = $app->dispatch($request);
} catch(Exception $e) {
    $response = new JsonResponse(
        ['error' => $e->getMessage()],
        500
    );
}

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

?>