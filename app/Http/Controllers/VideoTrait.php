<?php

namespace App\Http\Controllers;

use App\Services\LibraryService;
use Illuminate\Support\Facades\App;

trait VideoTrait
{
    public function getVideoByArgs($args)
    {
        if (!isset($args['video_slug']) || !is_string($args['video_slug']))
            throw new \InvalidArgumentException("Niepoprawny identyfikator wideo.");
        return $this->getVideo($args['video_slug']);
    }

    public function getVideo($lid, $onlyOwner=false)
    {
        $entity = App::make(LibraryService::class)->getEntity($lid);
        if (!$entity)
            throw new \InvalidArgumentException("Niepoprawny identyfikator wideo.");
        $user = auth()->user();
        if (($onlyOwner && $this->notOwner($user, $entity))
            || ($entity->visibility == 'private' && $this->notOwner($user, $entity)))
            throw new \InvalidArgumentException("Nie posiadasz uprawnień do tego obiektu.");
        return $entity;
    }

    private function notOwner($user, $entity)
    {
        return !$user || $entity->user_id != $user->id;
    }
}
