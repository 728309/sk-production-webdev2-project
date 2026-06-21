<?php

ini_set('display_errors', '0');

function sendJsonError(string $message, int $code): void
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode(['error' => $message], JSON_PRETTY_PRINT);
}

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (preg_match('/^https?:\/\/(localhost|127\.0\.0\.1|::1)(:\d+)?$/', $origin)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // Health routes
    $r->addRoute('GET', '/health', ['App\Controllers\HealthController', 'api']);
    $r->addRoute('GET', '/health/db', ['App\Controllers\HealthController', 'database']);

    // Auth routes
    $r->addRoute('POST', '/auth/register', ['App\Controllers\AuthController', 'register']);
    $r->addRoute('POST', '/auth/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/auth/me', ['App\Controllers\AuthController', 'me']);

    // Mix routes
    $r->addRoute('GET', '/mixes', ['App\Controllers\MixController', 'getAll']);
    $r->addRoute('GET', '/mixes/featured', ['App\Controllers\MixController', 'getFeatured']);
    $r->addRoute('GET', '/mixes/{id}/comments', ['App\Controllers\CommentController', 'getForMix']);
    $r->addRoute('POST', '/mixes/{id}/comments', ['App\Controllers\CommentController', 'create']);
    $r->addRoute('GET', '/mixes/{id}/votes', ['App\Controllers\VoteController', 'getForMix']);
    $r->addRoute('POST', '/mixes/{id}/votes', ['App\Controllers\VoteController', 'vote']);
    $r->addRoute('GET', '/mixes/{id}', ['App\Controllers\MixController', 'get']);
    $r->addRoute('POST', '/mixes', ['App\Controllers\MixController', 'create']);
    $r->addRoute('PUT', '/mixes/{id}', ['App\Controllers\MixController', 'adminUpdate']);
    $r->addRoute('DELETE', '/mixes/{id}', ['App\Controllers\MixController', 'adminDelete']);
    $r->addRoute('GET', '/my/mixes/summary', ['App\Controllers\MixController', 'getMyMixesSummary']);
    $r->addRoute('GET', '/my/mixes', ['App\Controllers\MixController', 'getMyMixes']);
    $r->addRoute('DELETE', '/comments/{id}', ['App\Controllers\CommentController', 'delete']);

    // Admin mix review routes
    $r->addRoute('GET', '/admin/mixes', ['App\Controllers\MixController', 'getAdminAll']);
    $r->addRoute('GET', '/admin/mixes/pending', ['App\Controllers\MixController', 'getPending']);
    $r->addRoute('PUT', '/admin/mixes/{id}/approve', ['App\Controllers\MixController', 'approve']);
    $r->addRoute('PUT', '/admin/mixes/{id}/reject', ['App\Controllers\MixController', 'reject']);
    $r->addRoute('PUT', '/admin/mixes/{id}/feature', ['App\Controllers\MixController', 'feature']);
    $r->addRoute('PUT', '/admin/mixes/{id}/unfeature', ['App\Controllers\MixController', 'unfeature']);
    $r->addRoute('PUT', '/admin/mixes/{id}', ['App\Controllers\MixController', 'adminUpdate']);
    $r->addRoute('DELETE', '/admin/mixes/{id}', ['App\Controllers\MixController', 'adminDelete']);
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        sendJsonError('Route not found', 404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        sendJsonError('Method not allowed', 405);
        break;
    case FastRoute\Dispatcher::FOUND:
        $class = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        try {
            $controller = new $class();
            $vars = $routeInfo[2];
            $controller->$method($vars);
        } catch (\Throwable $e) {
            sendJsonError('Internal server error', 500);
        }
        break;
}
