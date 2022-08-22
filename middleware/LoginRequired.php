<?php
namespace Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;


class LoginRequired implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->container->get('auth')->getAuthenticatedUser();
        if ($user)
            return $handler->handle($request);

        if (in_array(strtolower($request->getMethod()), ['post', 'put'])) {
            $response = new Response();
            $response = $response->withStatus(403);
            $response->getBody()->write('Wymagane logowanie!');
            return $response;
        }
        else {
            $path = $request->getUri()->getPath();
            $parser = $this->container->get('route.parser');
            $url = $parser->urlFor('login', [], ['next' => $path]);
            return $handler->handle($request)
                ->withHeader('Location', $url)
                ->withStatus(302);
        }
    }
}
