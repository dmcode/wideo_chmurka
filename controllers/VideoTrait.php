<?php
namespace Controllers;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;


trait VideoTrait
{
    public function getVideo($slug)
    {
        try {
            $entity = $this->library()->getEntity($slug);
            if (!$entity)
                throw new \InvalidArgumentException("Niepoprawny identyfikator wideo.");
            $user = $this->getAuthenticatedUser();
            if (!$user || $entity->user_id != $user->id)
                throw new \InvalidArgumentException("Nie posiadasz uprawnień do tego obiektu.");
            return $entity;
        }
        catch (\InvalidArgumentException) {
            throw new HttpNotFoundException($request);
        }
    }

    public function setContentType($entity, \SplFileObject$file, Response $response): Response
    {
        $response = $response->withHeader('Content-Length', filesize($file->getPathname()));
        if (str_contains($entity->format_name, 'webm'))
            return $response->withHeader('Content-Type', 'video/webm');
        return $response->withHeader('Content-Type', 'application/octet-stream');
    }
}
