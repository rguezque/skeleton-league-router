<?php declare(strict_types = 1);

namespace Project;

use Laminas\Diactoros\ResponseFactory;
use League\Container\Container;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;

class App {
    private static $app;
    private static $static_dir = '';

    public static function getApplication() {
        if (null === self::$app) {
            self::$app = new Router();
        }

        return self::$app;
    }

    public static function configure(string $set_static_dir, bool $use_api_strategy = false, ?Container $set_container = null): Router {
        self::getStaticDirectory($set_static_dir);
        $app = self::getApplication();

        if ($use_api_strategy) {
            $response_factory = new ResponseFactory();
            $strategy = new JsonStrategy($response_factory);
        } else {
            $strategy = new ApplicationStrategy();
        }

        if (null !== $set_container) {
            $strategy->setContainer($set_container);
        }

        $app->setStrategy($strategy);

        return $app;
    }

    /**
     * Establece el nombre de la carpeta estática.
     *
     * @param string $static_dir nombre del directorio estático (por ejemplo, 'static' o 'assets').
     */
    public static function setStaticDirectory(string $static_dir = '/static') {
        // Asegura que la ruta termine con una barra inclinada
        self::$static_dir = DIRECTORY_SEPARATOR . trim($static_dir, '/\\');
    }

    /**
     * Devuelve el nombre del directorio estático. Ej: 'static' o 'assets'.
     * @return string
     */
    public static function getStaticDirectory(): string {
        return self::$static_dir;
    }
    
}

?>