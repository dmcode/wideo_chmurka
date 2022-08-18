<?php
namespace Twig;

use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;


class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getGlobals(): array
    {
        return [
            'APP_NAME' => $_ENV['APP_NAME'],
            'APP_DESCRIPTION' => $_ENV['APP_DESCRIPTION']
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('url_for', [$this, 'url_for']),
        ];
    }
    public function url_for($name, $queryParams=[])
    {
        $parser = $this->container->get('route.parser');
        echo $parser->urlFor($name, $queryParams);
    }
}