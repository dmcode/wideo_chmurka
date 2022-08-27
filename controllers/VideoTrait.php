<?php
namespace Controllers;


trait VideoTrait
{
    public function getVideoByArgs($args)
    {
        if (!isset($args['video_slug']) || !is_string($args['video_slug']))
            throw new \InvalidArgumentException("Niepoprawny identyfikator wideo.");
        return $this->getVideo($args['video_slug']);
    }

    public function getVideo($slug)
    {
        $entity = $this->library()->getEntity($slug);
        if (!$entity)
            throw new \InvalidArgumentException("Niepoprawny identyfikator wideo.");
        $user = $this->getAuthenticatedUser();
        if ($entity->visibility == 'private' && (!$user || $entity->user_id != $user->id))
            throw new \InvalidArgumentException("Nie posiadasz uprawnień do tego obiektu.");
        return $entity;
    }
}
