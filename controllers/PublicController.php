<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;


class PublicController extends BaseController
{
    use VideoTrait;

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
        try{
            $video = $this->getVideoByArgs($args);
            $this->session()->set($video->slug, true);
            return $this->render($response, 'publicvideo.html.twig', ['video' => $video]);
        }
        catch (\InvalidArgumentException) {
            throw new HttpNotFoundException($request);
        }
    }
}
