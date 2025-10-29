<?php declare(strict_types = 1);

use Core\App;
use Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

$load = Dotenv::createImmutable(__DIR__.'/../');
$vars = $load->load();

$container = require __DIR__.'/dependencies.php';

App::configure(
    set_static_dir: '/static', 
    use_api_strategy: true,
    set_container: $container
);

/** Se recupera en el JS con `getCookie('API_URL') ` */
setcookie('API_URL', env('API_URL', ''), [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);

?>