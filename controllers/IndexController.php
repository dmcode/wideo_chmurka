<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class IndexController
{
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function index(Request $request, Response $response, $args): Response
    {
        return $this->view->render($response, 'index.html.twig', [
            'name' => "JAZDAAAAA!!!!"
        ]);
    }
}
