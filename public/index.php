<?php
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

$app = AppFactory::create();

$twig = Twig::create(APP_ROOT.'/templates', ['cache' => false]);

$app->add(
    new Session([
        'name' => 'SESSID',
        'autorefresh' => true,
        'lifetime' => '20 minutes',
        'httponly' => true
    ])
);

$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/hello/{name}', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'layout.html.twig', [
        'name' => $args['name']
    ]);
})->setName('profile');

$app->run();
