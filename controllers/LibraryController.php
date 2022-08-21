<?php

namespace Controllers;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LibraryController extends BaseController
{
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



//        print_r($files);
//        $file = array_pop($files);

//        $ffmpeg = FFMpeg::create();
//        $video = $ffmpeg->open($file->getFilePath());

//        $ffprobe = FFProbe::create();
//        $data = $ffprobe->format($file->getFilePath());
//        print_r($data);
//        print_r($ffprobe->isValid($file->getFilePath()) ? 'valid' : 'not valid');


//        $ffprobe = FFProbe::create();
//
//        print_r($ffprobe
//            ->streams($file->getFilePath()) // extracts streams informations
//            ->videos());
//
//        $video = $ffprobe
//            ->streams($file->getFilePath()) // extracts streams informations
//            ->videos()                      // filters video streams
//            ->first()                       // returns the first video stream
//         ;
//        print_r($video);

//            ->get('duration');
//        return $response;
    }
}
