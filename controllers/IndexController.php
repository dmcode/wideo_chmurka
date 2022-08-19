<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class IndexController extends BaseController
{
    public function index(Request $request, Response $response, $args): Response
    {
        return $this->render($response, 'index.html.twig');
    }
}
