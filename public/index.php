<?php
use DI\Container;
use Controllers\IndexController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\Session;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\AppExtension;

require __DIR__ . '/../vendor/autoload.php';

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

$container = new Container();
$app = AppFactory::createFromContainer($container);

$app->add(
    new Session([
        'name' => 'SESSID',
        'autorefresh' => true,
        'lifetime' => '20 minutes',
        'httponly' => true
    ])
);

$container->set('route.parser', function (ContainerInterface $container) use ($app) {
    return $app->getRouteCollector()->getRouteParser();
});

$container->set('view', function (ContainerInterface $container) {
    $twig = Twig::create(APP_ROOT.'/templates', ['cache' => false]);
    $twig->getEnvironment()->addExtension(new AppExtension($container));
    return $twig;
});
$app->add(TwigMiddleware::createFromContainer($app));


$container->set('IndexController', function (ContainerInterface $container) {
    $view = $container->get('view');
    return new IndexController($view);
});


// Routing
$app->get('/', 'IndexController:index')->setName('index');



$app->get('/hello/{name}', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'layout.html.twig', [
        'name' => $args['name']
    ]);
})->setName('profile');

$app->run();
