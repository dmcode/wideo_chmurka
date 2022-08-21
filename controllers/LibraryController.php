<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LibraryController extends BaseController
{
    public function uploadBlobVideo(Request $request, Response $response, $args): Response
    {
        $files = $request->getUploadedFiles();
        print_r($files);
        return $response;
    }
}
