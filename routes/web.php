<?php declare(strict_types = 1);

use App\Controllers\DbTestController;
use App\Controllers\TestController;
use App\Controllers\ViewTestController;
use Core\App;

$app = App::getApplication();

$app->get('/', [TestController::class, 'indexJsonAction']);
$app->get('/db-test', [DbTestController::class, 'indexAction']);
$app->get('/welcome', [ViewTestController::class, 'indexAction']);

return $app;

?>