<?php
namespace Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;


class BaseController
{
    public function __construct(protected ContainerInterface $container) {}

    public function render($response, $template, $args=[]): Response
    {
        $view = $this->container->get('view');
        if (!$view)
            throw new \RuntimeException("Twig service does not exists");
        return $view->render($response, $template, $args);
    }
}
