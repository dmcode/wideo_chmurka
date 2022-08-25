<?php
namespace Controllers;


trait VideoTrait
{
    public function getVideo($slug)
    {
        $entity = $this->library()->getEntity($slug);
        if (!$entity)
            throw new \InvalidArgumentException("Niepoprawny identyfikator wideo.");
        $user = $this->getAuthenticatedUser();
        if (!$user || $entity->user_id != $user->id)
            throw new \InvalidArgumentException("Nie posiadasz uprawnień do tego obiektu.");
        return $entity;
    }
}
