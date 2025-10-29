<?php declare(strict_types = 1);

namespace App\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

class ViewTestController
{
    private $view;

    public function __construct(Environment $twig)
    {
        $this->view = $twig;
    }
    public function indexAction(RequestInterface $request): ResponseInterface {
        $render = $this->view->render('index.html.twig', ['name' => 'Luis Rodriguez']);
        $response = new HtmlResponse($render);
        return $response;
    }
}

?>