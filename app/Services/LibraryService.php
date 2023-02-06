<?php

namespace App\Services;

use App\Models\Library;
use Illuminate\Http\UploadedFile;

class LibraryService
{
    public function __construct(protected VideoService $videoService) {}

    public function createFromUploaded(UploadedFile $file)
    {
        $video = $this->videoService->createFromUploaded($file);
        $thumb = $this->videoService->createThumbnail($video);
        $user = auth()->user();
        var_dump($user);
        $library = new Library([
            'lid' => self::generateId(),
            'visibility' => 'private',
            'title' => 'Moje nowe wideo',
            'thumb' => $thumb
        ]);
        $library->user()->associate($user);
        $library->save();
        $library->video()->save($video);
        return $library;
    }

    static public function generateId(): string
    {
        return substr(str_shuffle('adcdefghjkmnoprsquwxyz123456789_-ADCDEFGHJKMNPRSQUWXYZ'), 0, 12);
    }
}
