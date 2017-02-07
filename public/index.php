<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$configs = include('../lib/config.php');

$app = new \Slim\App(["settings" => $configs]);

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../templates', [
      'cache' => false
  ]);
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$app->get('/', function ($request, $response, $args) {
    
    return $this->view->render($response, 'home.twig');
});

$app->get('/about', function ($request, $response, $args) {
    
    return $this->view->render($response, 'about.twig');
});

// Run app
$app->run();