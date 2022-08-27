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

    public function updateData($video, $data)
    {
        $visibility = $data['visibility'] ? 'public' : 'private';
        return $this->db()->update(
            'library',
            ['title' => $data['title'], 'description' => $data['description'], 'visibility' => $visibility],
            ['id' => $video->id],
            1
        );
    }

    public function registerView($video, $number=1)
    {
        return $this->db()->call('register_view', ['video_slug' => $video->slug, 'number' => $number]);
    }

    public function findEntities($user)
    {
        return $this->db()->find('library l left join video v on (l.video_id=v.id)', ['user_id' => $user->id], ['*'], ['created_at DESC']);
    }

    public function getEntity($slug)
    {
        return $this->db()->fetch('library l left join video v on (l.video_id=v.id)', ['slug' => $slug]);
    }

    public function findMostRecent($limit=12)
    {
        return $this->db()->find(
            'library l left join video v on (l.video_id=v.id)',
            ['visibility' => 'public'], ['*'],
            ['created_at DESC'],
            $limit
        );
    }

    public function findPopular($limit=12)
    {
        return $this->db()->find(
            'library l left join video v on (l.video_id=v.id)',
            ['visibility' => 'public'], ['*'],
            ['number_views DESC', 'created_at DESC'],
            $limit
        );
    }

    protected function video()
    {
        return $this->get('video');
    }
}
