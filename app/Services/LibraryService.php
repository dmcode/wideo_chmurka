<?php

namespace App\Services;

use App\Models\Library;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;


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

    public function registerView($entity, $number=1)
    {
        $item = $this->getEntity($entity->lid);
        $item->number_views += 1;
        $item->save();
    }

    public function getVideoFile($entity): File
    {
        return $this->videoService->getVideoFile($entity->video->file);
    }

    public function getThumbFile($entity): File
    {
        return $this->videoService->getThumbFile($entity->thumb);   
    }

    public function getEntity(string $lid)
    {
        return Library::where('lid', $lid)->first();
    }

    public function findEntities($user)
    {
        return Library::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    }
    
    public function findMostRecent($limit=12)
    {
        return Library::where('visibility', 'public')->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function findPopular($limit=12)
    {
        return Library::where('visibility', 'public')
            ->orderBy('number_views', 'desc')->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    static public function generateId(): string
    {
        return substr(str_shuffle('adcdefghjkmnoprsquwxyz123456789_-ADCDEFGHJKMNPRSQUWXYZ'), 0, 12);
    }
}
