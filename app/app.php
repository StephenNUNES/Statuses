<?php

namespace app;

require __DIR__ . '/../vendor/autoload.php';

use Http\Request;
use Http\Response;
use Http\JsonResponse;
use Controller\ControllerStatuses;
use Controller\ControllerUsers;
use Exception\HttpException;
use Model\Connection;


// Config
$debug = true;

$dsn = 'mysql:host=localhost;dbname=uframework';
$connection = new Connection($dsn, 'uframework', 'passw0rd', null);

$app = new \App(new \View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

/**
 * Index
 */
 // Contrôleur sur la route d'entrée de l'application
$app->get('/', function () use ($app) {
    return $app->render('index.php');
});


$controllerStatuses = new ControllerStatuses($connection);


// Présence de tous les contrôleurs de l'application sous forme de closure
// Controleur pour obtenir (GET) tous les status
$app->get('/statuses', function (Request $request) use ($app, $controllerStatuses) {
    $statuses = $controllerStatuses->getStatuses($request);
    $parameters = [];
    $parameters['statuses'] = $statuses;
    
    if (isset($_SESSION['is_authenticated']) && true === $_SESSION['is_authenticated']) {
        
        $parameters['login'] = $_SESSION['login'];
    }
    if ("json" === $request->guessBestFormat())
    {
        $response = new JsonResponse($parameters, 200);
    }
    else
    {
        $response = new Response($app->render('statuses.php', $parameters), 200);
    }
    return $response->getContent();
});

//Action de récupérer un statuses par id
$app->get('/statuses/(\d+)', function (Request $request, $idStatuses) use ($app, $controllerStatuses) {
    try {
        $parameters = [];
        $parameters['id'] = $idStatuses;
        $parameters['status'] = $controllerStatuses->getStatusById($idStatuses);
        if (isset($_SESSION['is_authenticated']) && true === $_SESSION['is_authenticated']) {
            
            $parameters['login'] = $_SESSION['login'];
        }
        
        if ("json" === $request->guessBestFormat()) 
        {
            $response = new JsonResponse([$parameters['status']], 200);
        }
        else
        {
            $response = new Response($app->render('statusById.php', $parameters), 200);  
        }
        
    } catch (HttpException $he)
    {
        $response = new Response($app->render('httpResultError.php', ['httpException' => $he]), 404); 
    }
    return $response->getContent();
    
    
});

$app->post('/statuses', function (Request $request) use ($app, $controllerStatuses) {
    if ("json" === $request->guessBestFormat()) 
    {
        $controllerStatuses->postStatus($request->getParameter('username'), $request->getParameter('message'));
        $response = new JsonResponse("Request post was good", 201);
        return $response->getContent();
    }
        
    $controllerStatuses->postStatus($request->getParameter('username'), $request->getParameter('message'));
    $app->redirect("/statuses", 201);
});

$app->delete('/statuses/(\d+)', function (Request $request, $id) use ($app, $controllerStatuses) {
    $controllerStatuses->deleteStatus($id);
    
    $app->redirect("/statuses", 204);
   
});

$controllerUsers = new ControllerUsers($connection);

$app->get('/login', function (Request $request) use ($app) {
   
    $response = new Response($app->render('login.php'), 200);
    
    return $response->getContent();
});

$app->post('/login', function (Request $request) use ($app, $controllerUsers) {
    $login = $request->getParameter('login');
    $password = $request->getParameter('password');

    if ($controllerUsers->verifyLoginPasswordToLogin($login, $password)) {
        session_start();
        $_SESSION['is_authenticated'] = true;
        $_SESSION['login'] = $login;
        $app->redirect('/statuses');
        
    }
    $parameters = [];
    $parameters['error'] = "login and/or password were incorect";
    
    $response = new Response($app->render('login.php', $parameters), 200);
    
    return $response->getContent();
});

$app->get('/register', function (Request $request) use ($app) {
   
    $response = new Response($app->render('register.php'), 200);
    
    return $response->getContent();
});

$app->post('/register', function (Request $request) use ($app, $controllerUsers) {
    $login = $request->getParameter('login');
    
    $hashedPassword = password_hash($request->getParameter('password'), PASSWORD_DEFAULT);
    $controllerUsers->postUser($login, $hashedPassword);
    
    $app->redirect("/statuses");
});

$app->post('/logout', function (Request $request) use ($app) {
    
    session_destroy();
    $app->redirect("/statuses");
});

$app->addListener('process.before', function(Request $req) use ($app) {
    session_start();

    $allowed = [
        '/login' => [ Request::GET, Request::POST ],
        '/statuses' => [ Request::GET, Request::POST ],
        '/statuses/(\d+)' => [ Request::GET ],
        '/register' => [ Request::GET, Request::POST ],
    ];

    if (isset($_SESSION['is_authenticated'])
        && true === $_SESSION['is_authenticated']) {
        return;
    }

    foreach ($allowed as $pattern => $methods) {
        if (preg_match(sprintf('#^%s$#', $pattern), $req->getUri())
            && in_array($req->getMethod(), $methods)) {
            return;
        }
    }

    switch ($req->guessBestFormat()) {
        case 'json':
            throw new HttpException(401);
    }

    return $app->redirect('/login');
});

return $app;
