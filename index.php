<?php
session_start();
require 'vendor/autoload.php';

use App\Controllers\HomeController;
use App\Redirect;
use App\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/', [HomeController::class, 'home']);

    $r->addRoute('POST', '/bookmarks', [HomeController::class, 'saveArticles']);
    $r->addRoute('GET', '/bookmarks', [HomeController::class, 'displaySavedArticles']);

    $r->addRoute('GET', '/bookmarks/{id:\d+}', [HomeController::class, 'displayByChannelId']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:

        $handler = $routeInfo[1][0];
        $controller = $handler[0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        /** @var View $response */
        $response = (new $handler)->$method($vars);

        $loader = new FilesystemLoader('app/View');
        $twig = new Environment($loader);

        if ($response instanceof View) {
            echo $twig->render($response->getPath(), $response->getVariables());
            break;
        }

        if ($response instanceof Redirect) {
            header('Location: ' . $response->getLocation());
            exit;
        }


        break;
}

