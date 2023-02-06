<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class LibraryService
{
    public function __construct(protected VideoService $videoService) {}

    public function createFromUploaded(UploadedFile $file)
    {
        $video = $this->videoService->createFromUploaded($file);
    }
}
