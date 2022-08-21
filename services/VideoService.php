<?php
namespace Services;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Slim\Psr7\UploadedFile;


class VideoService extends BaseService
{
    public function createFromUploaded(UploadedFile $file)
    {
        if ($file->getError())
            throw new \InvalidArgumentException("Uploading error");

        $processed = self::processBlobToContainerIfNeeded($file);
        if (!$processed || !self::isValidMedia($processed))
            throw new \InvalidArgumentException("Invalid media file.");

        $fileId = $this->get('storage')->save($processed);
        $this->createFromStorageFile($fileId);
    }

    public function createFromStorageFile($fileId)
    {
        $file = $this->get('storage')->open($fileId);
        $entity = $this->get('db')->insert('video', ['slug' => $fileId]);
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
