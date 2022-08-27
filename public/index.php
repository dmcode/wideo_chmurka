<?php
use Controllers\AuthController;
use Controllers\PublicController;
use Controllers\LibraryController;
use Controllers\StreamController;
use DI\Container;
use Middleware\AuthMiddleware;
use Middleware\LoginRequired;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Services\AuthService;
use Services\DatabaseService;
use Services\LibraryService;
use Services\StorageService;
use Services\VideoService;
use Slim\Factory\AppFactory;
use Slim\Middleware\Session;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use SlimSession\Helper;
use Twig\AppExtension;

require __DIR__ . '/../vendor/autoload.php';

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

$container = new Container();
$app = AppFactory::createFromContainer($container);

$app->add((function () use ($container) {
    return new AuthMiddleware($container);
})());

$app->add(
    new Session([
        'name' => 'SESSID',
        'autorefresh' => true,
        'lifetime' => '20 minutes',
        'httponly' => true
    ])
);


$container->set('session', function () {
    return new Helper();
});

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

$container->set('auth', function (ContainerInterface $container) {
    return new AuthService($container);
});

$container->set('storage', function (ContainerInterface $container) {
    return new StorageService($container);
});

$container->set('video', function (ContainerInterface $container) {
    return new VideoService($container);
});

$container->set('library', function (ContainerInterface $container) {
    return new LibraryService($container);
});


// Controllers
$container->set('PublicController', function (ContainerInterface $container) {
    return new PublicController($container);
});

$container->set('AuthController', function (ContainerInterface $container) {
    return new AuthController($container);
});

$container->set('LibraryController', function (ContainerInterface $container) {
    return new LibraryController($container);
});

$container->set('StreamController', function (ContainerInterface $container) {
    return new StreamController($container);
});


// Routing
$app->get('/', 'PublicController:index')->setName('index');

$app->get('/login', 'AuthController:login')->setName('login');
$app->post('/login', 'AuthController:loginSubmit')->setName('login_submit');

$app->get('/logout', 'AuthController:logout')->setName('logout');

$app->get('/singup', 'AuthController:singup')->setName('singup');
$app->post('/singup', 'AuthController:singupSubmit')->setName('singup_submit');

$app->get('/library', 'LibraryController:index')
    ->add((function() use ($container) { return new LoginRequired($container); })())
    ->setName('library');

$app->get('/library/{video_slug}', 'LibraryController:video')
    ->add((function() use ($container) { return new LoginRequired($container); })())
    ->setName('library_video');

$app->get('/stream/{video_slug}', 'StreamController:video')
    ->setName('stream_video');

$app->post('/api/view/{video_slug}', 'LibraryController:registerVideoView')
    ->setName('register_view');

$app->post('/api/upload_blob', 'LibraryController:uploadBlobVideo')
    ->add((function() use ($container) { return new LoginRequired($container); })())
    ->setName('upload_blob');

$app->post('/api/video_data', 'LibraryController:updateVideoData')
    ->add((function() use ($container) { return new LoginRequired($container); })())
    ->setName('video_data');

$app->get('/thumb/{thumb_id}', 'StreamController:thumb')
    ->setName('stream_thumb');

$app->get('/{video_slug}', 'PublicController:video')
    ->setName('public_video');

$app->addBodyParsingMiddleware();
$app->run();
