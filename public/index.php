<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Tuupola\Middleware\CorsMiddleware;

$psr17Factory = new Psr17Factory();
AppFactory::setResponseFactory($psr17Factory);

$app = AppFactory::create();

// Middleware de CORS
$app->add(new CorsMiddleware([
    "origin" => ["http://127.0.0.1:5500"], // Reemplaza con tu dominio de frontend
    "methods" => ["GET", "OPTIONS"],  // Métodos permitidos
    "headers.allow" => ["Content-Type", "X-Requested-With"],
    "headers.expose" => [],
    "credentials" => true,
    "cache" => 0,
]));

// Middleware de enrutamiento
$app->addRoutingMiddleware();

// Middleware de manejo de errores
$app->addErrorMiddleware(true, true, true);

$app->get('/menu', function (Request $request, Response $response, $args) {
    $data = [
        "menu" => [
            "id" => "file",
            "value" => "File",
            "popup" => [
                "menuitem" => [
                    ["value" => "New", "onclick" => "CreateNewDoc()"],
                    ["value" => "Open", "onclick" => "OpenDoc()"],
                    ["value" => "Close", "onclick" => "CloseDoc()"]
                ]
            ]
        ]
    ];

    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
