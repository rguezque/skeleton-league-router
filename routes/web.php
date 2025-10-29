<?php declare(strict_types = 1);

use App\Controllers\TestController;
use Project\App;

$app = App::getApplication();

$app->get('/', [TestController::class, 'indexJsonAction']);

return $app;

?>