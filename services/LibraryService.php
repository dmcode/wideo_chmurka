<?php
namespace Services;

use Slim\Psr7\UploadedFile;


class LibraryService extends BaseService
{
    public function createFromUploaded(UploadedFile $file)
    {
        $video = $this->video()->createFromUploaded($file);
        $thumb = $this->createThumbnail($video);
        return $this->db()->insert('library', [
            'user_id' => $this->getAuthenticatedUser()->id,
            'video_id' => $video->id,
            'visibility' => 'private',
            'thumb' => $thumb,
            'title' => 'Moje nowe wideo'
        ]);
    }

    public function createThumbnail($video)
    {
        if ($video->duration < 1)
            return false;
        $sec = $video->duration >= 10 ? 10 : ceil($video->duration / 2);
        $frame = $this->video()->getFrame($video->slug, $sec);
        $framePath = '/tmp/'.bin2hex(random_bytes(10));
        $frame->save($framePath);
        $file = new \SplFileObject($framePath);
        return $this->storage()->save($file);
    }

    protected function video()
    {
        return $this->get('video');
    }
}
