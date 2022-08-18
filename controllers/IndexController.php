<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class IndexController
{
    public function index(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write("NO I TO JEST TO CO WIEM ZE JEST");
        return $response;
    }
}
