<?php declare(strict_types = 1);

use Project\App;

require __DIR__.'/../vendor/autoload.php';

$container = require __DIR__.'/dependencies.php';

App::configure(
    set_static_dir: '/static', 
    use_api_strategy: true,
    set_container: $container
);

?>