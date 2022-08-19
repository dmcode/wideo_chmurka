<?php
namespace Services;

use Psr\Container\ContainerInterface;


class DatabaseService extends BaseService
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($this->container);
    }
}
