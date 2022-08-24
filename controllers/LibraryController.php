<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;


class LibraryController extends BaseController
{
    public function index(Request $request, Response $response, $args): Response
    {
        return $this->render($response, 'library.html.twig');
    }

    public function video(Request $request, Response $response, $args): Response
    {
        try {
            $slug = $args['video_slug'];
            $entity = $this->library()->getEntity($slug);
            if (!$entity)
                throw new \InvalidArgumentException("Niepoprawny identyfikator wideo.");
            $user = $this->getAuthenticatedUser();
            if (!$user || $entity->user_id != $user->id)
                throw new \InvalidArgumentException("Nie posiadasz uprawnień do tego obiektu.");
            return $this->render($response, 'library/video.html.twig', ['video_slug' => $slug]);
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
