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

require __DIR__ . '/../vendor/autoload.php';

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->add(
    new Session([
        'name' => 'SESSID',
        'autorefresh' => true,
        'lifetime' => '20 minutes',
        'httponly' => true
    ])
);


$container->set('view', function (ContainerInterface $container) {
    return Twig::create(APP_ROOT.'/templates', ['cache' => false]);
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
