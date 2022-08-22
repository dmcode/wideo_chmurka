<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LibraryController extends BaseController
{
    public function index(Request $request, Response $response, $args): Response
    {
        return $response;
    }

    public function uploadBlobVideo(Request $request, Response $response, $args): Response
    {
        $files = $request->getUploadedFiles();
        if (empty($files))
            throw new \InvalidArgumentException("The video data does not exists.");

        $video = $this->get('video');
        if (!$video)
            throw new \RuntimeException("The video service does not exists.");

        foreach ($files as $file) {
            $video->createFromUploaded($file);
        }

        return $response;
    }
}
