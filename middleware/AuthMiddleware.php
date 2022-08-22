<?php
namespace Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container) {}

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $this->container->get('auth')->getAuthenticatedUser();
        return $handler->handle($request);
    }
}
