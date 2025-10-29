<?php declare(strict_types = 1);

namespace App\Controllers;

use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TestController
{
    public function indexAction(RequestInterface $request): ResponseInterface {
        return new Response('Hello, World!');
    }

    public function indexJsonAction(): array {
        return ['message' => 'Hello world'];
    }
}

?>