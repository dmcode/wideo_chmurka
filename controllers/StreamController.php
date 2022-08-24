<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse;
use Slim\Psr7\Stream;


class StreamController extends BaseController
{
    use VideoTrait;

    public function video(Request $request, Response $response, $args): Response
    {
        set_time_limit(0);
        $video = $this->getVideo($args['video_slug']);
        $file = $this->storage()->open($video->slug);
        $response = $this->setContentType($video, $file, new SlimResponse());
        return $response->withBody(new Stream(fopen($file->getPathname(), 'r')));
    }
}
