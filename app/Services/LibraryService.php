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
        $library = new Library([
            'lid' => self::generateId(),
            'visibility' => 'private',
            'title' => 'Moje nowe wideo',
            'thumb' => $thumb,
            'number_views' => 0,
            'published_at' => null,
            'description' => null,
        ]);
        $library->user()->associate(auth()->user());
        $library->save();
        $library->video()->save($video);
        return $library;
    }

    public function findMostRecent($limit=12)
    {
        return Library::where('visibility', 'public')->orderBy('created_at', 'desc')->get();
    }

    static public function generateId(): string
    {
        return substr(str_shuffle('adcdefghjkmnoprsquwxyz123456789_-ADCDEFGHJKMNPRSQUWXYZ'), 0, 12);
    }
}
