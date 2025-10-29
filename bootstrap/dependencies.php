<?php declare(strict_types = 1);

use App\Controllers\DbTestController;
use App\Controllers\ViewTestController;
use League\Container\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$container = new Container();
$container->add(PDO::class)->addArguments([
    env('DB_DSN'),
    env('DB_USER'),
    env('DB_PASSWORD'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
]);
$container->add(DbTestController::class)->addArgument(PDO::class);
$container->add(ViewTestController::class)->addArgument('Twig');
$container->add('Twig', function() {
    $loader = new FilesystemLoader(__DIR__.'/../templates');
    return new Environment($loader, [
        'cache' => __DIR__.'/../templates/cache',
    ]);
});


return $container;

?>