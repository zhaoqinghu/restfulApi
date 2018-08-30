<?php
ini_set("display_errors", 0);
header('Content-Type: application/json');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';
require_once "inc/lib.php";
$app = new \Slim\App();
$request_app = "";
$is_controller = "";

$app->add(function ($request, $response, $next) {
	$request_url = $request->getUri()->getPath();
	$request_arr = explode('/',$request_url);
	if($request_arr[0] != 'swagger.json' && $request_arr[0] != ""){
		$is_controller = include_once('src/controllers/'.$request_arr[0].'.php');
		if(!$is_controller){
			$error = new stdClass();
			$error->code = 1001;
			$error->message = "The controller doesn't exist";
			$response = $response->withJson($error,404, JSON_PRETTY_PRINT);
			return $response;
		}
		$is_model = include_once('src/models/'.$request_arr[0].'_model.php');
		if(!$is_model){
			$error = new stdClass();
			$error->code = 1002;
			$error->message = "The model doesn't exist";
			$response = $response->withJson($error,404, JSON_PRETTY_PRINT);
			return $response;
		}
	}
	//$response->getBody()->write('BEFORE');
	$response = $next($request, $response);
	//$response->getBody()->write('AFTER');

	return $response;
});
/*foreach (glob("./src/controllers/*.php") as $filename) {
    include $filename;
}
*/
/*foreach (glob("./src/models/*.php") as $filename) {
    include $filename;
}*/
foreach (glob("./src/routes/*.php") as $filename) {
    include_once $filename;
}

//$app->get('/domain/{domainId}', get_domain_list);
//$app->get('/domain/{domainId}', "\DomainController:get_domain_list");
/*
$app->get('/hello/{name}', function ($request,$response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});*/

$app->get('/swagger.json', function ($request, $response) {
    $swagger = \Swagger\scan('./src');
    $response->getBody()->write($swagger);
    return $response;
});
$app->run();

