<?php

namespace App\Services;

use App\Models\Video;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VideoService
{
    const VIDEO_STORAGE = 'videos';
    const THUMBS_STORAGE = 'thumbs';

    public function getVideoFile($filePath): File
    {
        return new File(Storage::path($filePath));
    }

    public function saveUploadedVideo(UploadedFile $file)
    {
        return Storage::putFile(self::VIDEO_STORAGE, $file);
    }

    public function getThumbFile($filePath): File
    {
        return new File(Storage::path($filePath));
    }

    public function saveVideoThumb(File $file)
    {
        return Storage::putFile(self::THUMBS_STORAGE, $file);
    }

    public function createFromUploaded(UploadedFile $file): Video
    {
        if ($file->getError())
            throw new \InvalidArgumentException("Uploading error");

        $processed = self::processBlobToContainerIfNeeded($file);
        if (!$processed || !self::isValidMedia($processed))
            throw new \InvalidArgumentException("Invalid media file.");

        $filePath = $this->saveUploadedVideo($processed);
        return $this->createFromStorageFile($filePath);
    }

    public function createFromStorageFile(string $filePath): Video
    {
        $file = $this->getVideoFile($filePath);
        return new Video(array_merge(['file' => $filePath], self::getMediaAttributes($file)));
    }

    public function createThumbnail($video): string
    {
        if ($video->duration < 1)
            return false;
        $sec = $video->duration >= 10 ? 10 : ceil($video->duration / 2);
        $frame = $this->getFrame($video->file, $sec);
        $framePath = '/tmp/'.bin2hex(random_bytes(10));
        $frame->save($framePath);
        $file = new File($framePath);
        return $this->saveVideoThumb($file);
    }

    static public function getMediaAttributes(File $file): array
    {
        $ffprobe = FFProbe::create();
        $v_stream = $ffprobe->streams($file->getPathname())->videos()->first();
        $v_format = $ffprobe->format($file->getPathname());
        return [
            'duration' => $v_format->get('duration', 0),
            'res_w' => $v_stream->get('width', 0),
            'res_h' => $v_stream->get('height', 0),
            'size' => $v_format->get('size', 0),
            'codec_name' => $v_stream->get('codec_name'),
            'format_name' => $v_format->get('format_name'),
        ];
    }

    static public function isValidMedia($file)
    {
        return FFProbe::create()->isValid($file->getPathname());
    }

    static protected function processBlobToContainerIfNeeded(UploadedFile $file): UploadedFile
    {
        if (self::isValidMedia($file))
            return $file;
        // ffmpeg nie odczytuje czasu trwania nagrania z bloba od MediaRecorderAPI (brak nagłówka webm),
        // w konsekwecji nie waliduje pliku jako media,
        // robimy konwersje do nowego pliku
        $path = $file->getPathname().'.webm';
        FFMpeg::create()->getFFMpegDriver()->command([
            '-i', $file->getPathname(),
            '-vcodec', 'copy',
            '-acodec', 'copy',
            $path
        ]);
        return new UploadedFile(
            $path,
            $file->getClientOriginalName(),
            $file->getClientMimeType(),
            $file->getError(),
            false
        );
    }

    public function getFrame($video, $quantity)
    {
        $path = null;
        if (is_string($video))
            $video = $this->getVideoFile($video);
        if ($video instanceof File)
            $path = $video->getPathname();
        if (!$path)
            throw new \InvalidArgumentException("The video file does not exists..");
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($path);
        return $video->frame(TimeCode::fromSeconds($quantity));
    }
}
