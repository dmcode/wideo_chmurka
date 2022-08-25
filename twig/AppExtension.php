<?php
namespace Twig;

use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;


class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $container;
    private $visibilityDict = [
        'private' => 'prywatny', 'protected' => 'niepubliczny', 'public' => 'publiczny'
    ];

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
            new TwigFunction('url_css', [$this, 'url_css']),
            new TwigFunction('authenticated', [$this, 'authenticated']),
            new TwigFunction('username', [$this, 'username']),
            new TwigFunction('visibility', [$this, 'visibility']),
            new TwigFunction('duration', [$this, 'duration'])
        ];
    }

    public function url_for($name, $queryParams=[])
    {
        $parser = $this->container->get('route.parser');
        echo $parser->urlFor($name, $queryParams);
    }

    public function url_css($path)
    {
        if (!str_ends_with($path, '.css'))
            $path = $path . '.css';
        return '/css/'.$path;
    }

    public function authenticated(): bool
    {
        return $this->container->get('auth')->isAuthenticated();
    }

    public function username(): string
    {
        $user = $this->container->get('auth')->getAuthenticatedUser();
        return $user->email;
    }

    public function visibility($visibility): string
    {
        return isset($this->visibilityDict[$visibility]) ? $this->visibilityDict[$visibility] : $visibility;
    }

    public function duration($duration): string
    {
        $min = floor($duration / 60);
        if ($min < 10)
            $min = '0'.$min;
        $sec = $duration - ($min * 60);
        if ($sec < 10)
            $sec = '0'.$sec;
        return "$min:$sec";
    }
}
