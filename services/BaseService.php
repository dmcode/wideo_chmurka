<?php
namespace Services;

use Psr\Container\ContainerInterface;

class BaseService
{
    public function __construct(protected ContainerInterface $container) {}

    public function get($service)
    {
        return $this->container->get($service);
    }

    public function getAuthenticatedUser()
    {
        return $this->get('auth')->getAuthenticatedUser();
    }

    public function db()
    {
        return $this->get('db');
    }

    public function storage()
    {
        return $this->get('storage');
    }
}
