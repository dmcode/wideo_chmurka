<?php
namespace Services;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Slim\Psr7\UploadedFile;


class VideoService extends BaseService
{
    public function createFromUploaded(UploadedFile $file): \stdClass
    {
        if ($file->getError())
            throw new \InvalidArgumentException("Uploading error");

        $processed = self::processBlobToContainerIfNeeded($file);
        if (!$processed || !self::isValidMedia($processed))
            throw new \InvalidArgumentException("Invalid media file.");

        $fileId = $this->get('storage')->save($processed);
        return $this->createFromStorageFile($fileId);
    }

    public function createFromStorageFile(string $fileId): \stdClass
    {
        $file = $this->get('storage')->open($fileId);
        $entity = $this->get('db')->insert('video', array_merge(['slug' => $fileId], self::getMediaAttributes($file)));
        return $entity;
    }

    static public function getMediaAttributes(\SplFileObject $file): array
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
        return FFProbe::create()->isValid($file->getFilePath());
    }

    static protected function processBlobToContainerIfNeeded(UploadedFile $file): UploadedFile
    {
        if (self::isValidMedia($file))
            return $file;
        // ffmpeg nie odczytuje czasu trwania nagrania z bloba od MediaRecorderAPI (brak nagłówka webm),
        // w konsekwecji nie waliduje pliku jako media,
        // robimy konwersje do nowego pliku
        $path = $file->getFilePath().'.webm';
        FFMpeg::create()->getFFMpegDriver()->command([
            '-i', $file->getFilePath(),
            '-vcodec', 'copy',
            '-acodec', 'copy',
            $path
        ]);
        return new UploadedFile(
            $path,
            $file->getClientFilename(),
            $file->getClientMediaType(),
            $file->getSize(),
            $file->getError()
        );
    }
}
