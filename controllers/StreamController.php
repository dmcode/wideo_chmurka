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
            return $this->streamResponse($request, $file, $contentType);
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
            return $this->streamResponse($request, $file, 'image/jpeg');
        }
        catch (\InvalidArgumentException) {
            throw new HttpNotFoundException($request);
        }
    }

    protected function streamResponse(Request $request, \SplFileObject $file, $contentType): ResponseInterface
    {
        list($start, $end, $size) = $this->range($request, $file);
        $response = new Response();
        $stream = new Stream(fopen($file->getPathname(), 'r'));
        if ($start > 0)
            $stream->seek($start);
        return $response
            ->withHeader('Content-Type', $contentType)
            ->withHeader('Content-Length', $size)
            ->withHeader('Accept-Ranges', 'bytes')
            ->withHeader('Content-Range', "bytes $start-$end/$size")
            ->withBody($stream);
    }

    private function range(Request $request, \SplFileObject $file)
    {
        $size = filesize($file->getPathname());
        $range = [0, $size, $size];
        $header = $request->getHeader('Range');
        if (empty($header))
            $header = ['bytes=0-'];
        $matches = [];
        if (preg_match('/^bytes=(\d+-(?:\d+)?)$/', $header[0], $matches)) {
            $data = explode('-', $matches[1]);
            if (isset($data[0]) && is_numeric($data[0]))
                $range[0] = intval($data[0]);
            if (isset($data[1]) && is_numeric($data[1]))
                $range[1] = intval($data[1]);
        }
        return $range;
    }
}
