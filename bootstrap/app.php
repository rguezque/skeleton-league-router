<?php declare(strict_types = 1);

use Core\App;
use Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

/** @var Dotenv\Dotenv Instancia inmutable de repositorio de variables de entorno */
$dotenv = Dotenv::createImmutable(__DIR__.'/../');
/** @var array Variables de entorno cargadas */
$vars = $dotenv->load();

/** @var League\Container\Container Contenedor de dependencias y controladores */
$container = require __DIR__.'/dependencies.php';


App::configure(
    set_static_dir: '/static', 
    use_api_strategy: false,
    set_container: $container
);

/** Se recupera en el JS con `getCookie('API_URL') ` */
setcookie('API_URL', $vars['API_URL'] ?? '', [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);

?>