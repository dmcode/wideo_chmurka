<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LibraryController extends BaseController
{
    use VideoTrait;

    public function index(Request $request, Response $response, $args): Response
    {
        $entities = $this->library()->findEntities($this->getAuthenticatedUser());
        return $this->render($response, 'library.html.twig', ['entities' => $entities]);
    }

    public function video(Request $request, Response $response, $args): Response
    {
        $video = $this->getVideo($args['video_slug']);
        return $this->render($response, 'library/video.html.twig', ['video' => $video]);
    }

    public function uploadBlobVideo(Request $request, Response $response, $args): Response
    {
        $files = $request->getUploadedFiles();
        if (empty($files))
            throw new \InvalidArgumentException("The video data does not exists.");

        $library = $this->library();
        foreach ($files as $file)
            $library->createFromUploaded($file);

        return $response;
    }
}
