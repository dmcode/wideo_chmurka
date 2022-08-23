<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LibraryController extends BaseController
{
    public function index(Request $request, Response $response, $args): Response
    {
        return $this->render($response, 'library.html.twig');
    }

    public function uploadBlobVideo(Request $request, Response $response, $args): Response
    {
        $files = $request->getUploadedFiles();
        if (empty($files))
            throw new \InvalidArgumentException("The video data does not exists.");

        $library = $this->get('library');
        foreach ($files as $file)
            $library->createFromUploaded($file);

        return $response;
    }
}
