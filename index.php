<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use controllers\ImpiantoController;
use controllers\RilevatoreController;

require __DIR__ . '/vendor/autoload.php';

spl_autoload_register('psr4_autoloader');

function psr4_autoloader($class) {
    $class_path = str_replace('\\', '/', $class);
    $file =  __DIR__ . '/' . $class_path . '.php';
    if (file_exists($file)) {
        require $file;
    }
}

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$db = (new DB())->getInstance();

new ImpiantoController($app);
new RilevatoreController($app, 'umidita', "%");
new RilevatoreController($app, 'temperatura', "°C");

$app->run();
