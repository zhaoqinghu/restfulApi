<?php
 header('Content-Type: application/json');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';
$app = new \Slim\App();
$app->add(function ($request, $response, $next) {
	//var_dump($request);die;
	//$response->getBody()->write('BEFORE');
	$response = $next($request, $response);
	//$response->getBody()->write('AFTER');

	return $response;
});
$app->get('/hello/{name}', function ($request,$response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->get('/domain/{domainId}', function ($request,$response, array $args) {
	var_dump(22222222);die;
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->get('/swagger.json', function ($request, $response) {
    $swagger = \Swagger\scan('./src');
    $response->getBody()->write($swagger);
    return $response;
});
$app->run();

