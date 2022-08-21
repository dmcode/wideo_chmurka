<?php
use Controllers\AuthController;
use Controllers\IndexController;
use Controllers\LibraryController;
use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\AccountService;
use Services\DatabaseService;
use Services\StorageService;
use Services\VideoService;
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


// Services
$container->set('db', function (ContainerInterface $container) {
    return new DatabaseService($container);
});

$container->set('account', function (ContainerInterface $container) {
    return new AccountService($container);
});

$container->set('storage', function (ContainerInterface $container) {
    return new StorageService($container);
});

$container->set('video', function (ContainerInterface $container) {
    return new VideoService($container);
});


// Controllers
$container->set('IndexController', function (ContainerInterface $container) {
    return new IndexController($container);
});

$container->set('AuthController', function (ContainerInterface $container) {
    return new AuthController($container);
});

$container->set('LibraryController', function (ContainerInterface $container) {
    return new LibraryController($container);
});


// Routing
$app->get('/', 'IndexController:index')->setName('index');
$app->get('/account/login', 'AuthController:login')->setName('account_login');
$app->get('/singup', 'AuthController:singup')->setName('singup');
$app->post('/singup', 'AuthController:singupSubmit')->setName('singup_submit');
$app->post('/api/upload_blob', 'LibraryController:uploadBlobVideo')->setName('upload_blob');



$app->get('/hello/{name}', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'layout.html.twig', [
        'name' => $args['name']
    ]);
})->setName('profile');

$app->run();
