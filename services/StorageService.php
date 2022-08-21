<?php
namespace Services;

use Slim\Psr7\UploadedFile;


class StorageService extends BaseService
{
    public function save(UploadedFile $file): string
    {
        $id = self::fileid();
        $file->moveTo(self::generateFilePath($id));
        return $id;
    }

    public function open(string $id): \SplFileObject
    {
        return new \SplFileObject(self::generateFilePath($id));
    }

    static public function generateFilePath(string $id): string
    {
        $saveDir = self::storageDirPath() . DIRECTORY_SEPARATOR . substr($id, 0, 2);
        if (!file_exists($saveDir))
            mkdir($saveDir, 0775, true);
        return $saveDir . DIRECTORY_SEPARATOR . $id;
    }

    static public function storageDirPath(): string
    {
        return APP_ROOT . DIRECTORY_SEPARATOR . 'storage';
    }

    static public function fileid(): string
    {
        return substr(str_shuffle('adcdefghjkmnoprsquwxyz123456789_-ADCDEFGHJKMNPRSQUWXYZ'), 0, 12);
    }
}
