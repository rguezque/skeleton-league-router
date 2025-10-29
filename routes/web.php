<?php declare(strict_types = 1);

use App\Controllers\DbTestController;
use App\Controllers\TestController;
use Core\App;

$app = App::getApplication();

$app->get('/', [TestController::class, 'indexJsonAction']);
$app->get('/db-test', [DbTestController::class, 'indexAction']);

return $app;

?>