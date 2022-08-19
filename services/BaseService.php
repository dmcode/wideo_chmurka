<?php
namespace Services;

use Psr\Container\ContainerInterface;

class BaseService
{
    public function __construct(protected ContainerInterface $container) {}
}
