<?php

namespace App\Http\Controllers;

use App\Services\LibraryService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;


class StreamController extends Controller
{
    use VideoTrait;

    public function thumb(Request $request, $lid, LibraryService $library)
    {
        try {
            set_time_limit(0);
            $video = $this->getVideo($lid);
            $file = $library->getThumbFile($video);
            $contentType = str_contains($video->format_name, 'webm') ? 'video/webm' : 'application/octet-stream';
            return $this->streamResponse($request, $file, $contentType);
        }
        catch (\InvalidArgumentException) {
            abort(404);
        }
    }
    
    protected function streamResponse(Request $request, File $file, $contentType)
    {
        list($start, $end, $size) = $this->range($request, $file);
        $response = new StreamedResponse(function() use ($file, $start, $size) {
            $handle = fopen($file, 'r');
            fseek($handle, $start);
            while (!feof($handle) && $size > 0) {
                $read = min(1024, $size);
                echo fread($handle, $read);
                flush();
                $size -= $read;
            }
            fclose($handle);
        });
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Length', $size);
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Content-Range', "bytes $start-$end/$size");
        return $response;
    }

    private function range(Request $request, File $file)
    {
        $size = filesize($file->getPathname());
        $range = [0, $size, $size];
        $header = $request->header('Range');
        if (empty($header))
            $header = ['bytes=0-'];
        $matches = [];
        if (preg_match('/^bytes=(\d+-(?:\d+)?)$/', $header[0], $matches)) {
            $data = explode('-', $matches[1]);
            if (isset($data[0]) && is_numeric($data[0]))
                $range[0] = intval($data[0]);
            if (isset($data[1]) && is_numeric($data[1]))
                $range[1] = intval($data[1]);
        }
        return $range;
    }
}
