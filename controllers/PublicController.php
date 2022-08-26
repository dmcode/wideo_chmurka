<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PublicController extends BaseController
{
    public function index(Request $request, Response $response, $args): Response
    {
        $recent = $this->library()->findMostRecent();
        $popular = $this->library()->findPopular();
        return $this->render($response, 'index.html.twig', [
            'recent' => $recent,
            'popular' => $popular
        ]);
    }

    public function video(Request $request, Response $response, $args): Response
    {
        return $this->render($response, 'publicvideo.html.twig');
    }
}
