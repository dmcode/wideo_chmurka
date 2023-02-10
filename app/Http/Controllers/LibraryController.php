<?php

namespace App\Http\Controllers;

use App\Services\LibraryService;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;


class LibraryController extends BaseController
{
    use VideoTrait;

    public function index(Request $request, LibraryService $library)
    {
        $entities = $library->findEntities(auth()->user());
        return view('library.library', ['entities' => $entities]);
    }

    public function video(Request $request, $lid, LibraryService $library)
    {
        try {
            $entity = $this->getVideo($lid);
            return view('library.video', ['item' => $entity]);
        }
        catch (\InvalidArgumentException) {
            abort(404);
        }
    }

    public function updateVideoData(Request $request, LibraryService $library)
    {
        try {
            $data = $request->all();
            $entity = $this->getVideo($data['lid'], true);
            $library->updateData($entity, $data);
            return response()->json(['status' => 'SUCCESS']);
        }
        catch (\InvalidArgumentException) {
            abort(404);
        }
    }

    public function registerVideoView(Request $request, $lid, LibraryService $library)
    {
        try {
            $entity = $this->getVideo($lid);
            $key = "viewed_$entity->slug";
            if (!Session::has($entity->lid) || Session::has($key))
                throw new \InvalidArgumentException("Upss! Nie da się.");
            $library->registerView($entity);
            Session::put($key, true);
            return response()->json(['status' => 'SUCCESS']);
        }
        catch (\InvalidArgumentException) {
            abort(404);
        }
    }

    public function uploadBlobVideo(Request $request, LibraryService $library)
    {
        $files = $request->allFiles();
        if (empty($files))
            throw new \InvalidArgumentException("The video data does not exists.");
        foreach ($files as $file) {
            $library->createFromUploaded($file);
        }
        return response()->json(['uploaded' => 'SUCCESS']);
    }

    public function deleteVideo(Request $request, LibraryService $library)
    {
        try {
            $data = $request->all();
            $entity = $this->getVideo($data['lid'], true);
            $library->deleteVideo($entity);
            return response()->json(['status' => 'SUCCESS']);
        }
        catch (\InvalidArgumentException) {
            abort(404);
        }
    }
}
