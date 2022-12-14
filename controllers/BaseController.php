<?php
namespace Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;


class BaseController
{
    public function __construct(protected ContainerInterface $container) {}

    public function render(Response $response, $template, $args=[]): Response
    {
        $view = $this->get('view');
        if (!$view)
            throw new \RuntimeException("Twig service does not exists");
        return $view->render($response, $template, $args);
    }

    public function get($service)
    {
        return $this->container->get($service);
    }

    public function redirect(Response $response, $name)
    {
        if (str_starts_with($name, '/'))
            $url = $name;
        else
            $url = $this->get('route.parser')->urlFor($name, []);
        return $response
            ->withHeader('Location', $url)
            ->withStatus(302);
    }

    protected function setFlash($key, $message): bool
    {
        if (!is_string($message))
            return false;
        $this->session()->set($key, $message);
        return true;
    }

    protected function getFlash($key): ?string
    {
        if (!$this->session()->exists($key))
            return null;
        $message = $this->session()->get($key);
        $this->session()->delete($key);
        return $message;
    }

    public function getAuthenticatedUser()
    {
        return $this->get('auth')->getAuthenticatedUser();
    }

    protected function db()
    {
        return $this->get('db');
    }

    protected function library()
    {
        return $this->get('library');
    }

    protected function storage()
    {
        return $this->get('storage');
    }

    protected function session()
    {
        return $this->get('session');
    }
}
