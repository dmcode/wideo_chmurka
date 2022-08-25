<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;


class StreamController extends BaseController
{
    use VideoTrait;

    public function video(Request $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            set_time_limit(0);
            $video = $this->getVideo($args['video_slug']);
            $file = $this->storage()->open($video->slug);
            $contentType = str_contains($video->format_name, 'webm') ? 'video/webm' : 'application/octet-stream';
            return $this->streamResponse($file, $contentType);
        }
        catch (\InvalidArgumentException) {
            throw new HttpNotFoundException($request);
        }
    }

    public function thumb(Request $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            set_time_limit(0);
            $file = $this->storage()->open($args['thumb_id']);
            return $this->streamResponse($file, 'image/jpeg');
        }
        catch (\InvalidArgumentException) {
            throw new HttpNotFoundException($request);
        }
    }

    protected function streamResponse(\SplFileObject $file, $contentType): ResponseInterface
    {
        $response = new Response();
        return $response
            ->withHeader('Content-Length', filesize($file->getPathname()))
            ->withHeader('Content-Type', $contentType)
            ->withBody(new Stream(fopen($file->getPathname(), 'r')));
    }
}
