<?php
namespace Services;

use Slim\Psr7\UploadedFile;


class LibraryService extends BaseService
{
    public function createFromUploaded(UploadedFile $file)
    {
        $video = $this->get('video')->createFromUploaded($file);
        return $this->get('db')->insert('library', [
            'user_id' => $this->getAuthenticatedUser()->id,
            'video_id' => $video->id,
            'visibility' => 'private',
            'title' => 'Moje nowe wideo'
        ]);
    }
}
