<?php

namespace App\Services;

use App\Models\Video;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VideoService
{
    public function getVideoFile($fileId): File
    {
        return new File(Storage::path('videos/'.$fileId));
    }

    public function saveUploadedFile(UploadedFile $file)
    {
        $id = self::fileid();
        Storage::putFileAs('videos', $file, $id);
        return $id;
    }

    public function createFromUploaded(UploadedFile $file)
    {
        if ($file->getError())
            throw new \InvalidArgumentException("Uploading error");

        $processed = self::processBlobToContainerIfNeeded($file);
        if (!$processed || !self::isValidMedia($processed))
            throw new \InvalidArgumentException("Invalid media file.");

        $fileId = $this->saveUploadedFile($processed);
        return $this->createFromStorageFile($fileId);
    }

    public function createFromStorageFile(string $fileId)
    {
        $file = $this->getVideoFile($fileId);
        return Video::create(array_merge(['vid' => $fileId], self::getMediaAttributes($file)));
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

    static public function fileid(): string
    {
        return substr(str_shuffle('adcdefghjkmnoprsquwxyz123456789_-ADCDEFGHJKMNPRSQUWXYZ'), 0, 12);
    }
}
