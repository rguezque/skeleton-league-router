<?php declare(strict_types = 1);

use League\Container\Container;

$container = new Container();
$container->add(PDO::class)->addArguments([
    env('DB_DSN'),
    env('DB_USER'),
    env('DB_PASSWORD'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
]);

return $container;

?>