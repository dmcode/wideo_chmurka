<?php

namespace App\Http\Controllers;

use App\Services\LibraryService;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;


class LibraryController extends BaseController
{
    /**
     * @return View
     */
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
}
