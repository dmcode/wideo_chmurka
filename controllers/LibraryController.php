<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;


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
        try {
            return $this->render($response, 'library/video.html.twig', [
                'video' => $this->getVideoByArgs($args)
            ]);
        }
        catch (\InvalidArgumentException) {
            throw new HttpNotFoundException($request);
        }
    }

    public function registerVideoView(Request $request, Response $response, $args): Response
    {
        try {
            $video = $this->getVideoByArgs($args);
            $key = "viewed_$video->slug";
            if (!$this->session()->exists($video->slug) || $this->session()->exists($key))
                throw new \InvalidArgumentException("Upss! Nie da się.");
            $this->library()->registerView($video);
            $this->session()->set($key, true);
            return $response;
        }
        catch (\InvalidArgumentException) {
            throw new HttpNotFoundException($request);
        }
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
