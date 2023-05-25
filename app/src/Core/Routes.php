<?php

require "Route.php";

function makeREST(Router $router, string $name)
{
    $methods = ['GET', 'POST', 'PUT', 'DELETE'];

    foreach($methods as $method){
        $router->add(
            new Route(
                "/api/".$name."/{id}", array(
                    'controller' => "API",
                    'action' => $name
                ), [
                    "id" => "/^\d*/"
                ], $method
            )
        );
    }
}

$router = new Router();

$router->add(
    new Route(
        "/{type}", array(
        'controller' => "Main",
        'action' => "index"
        ), [
            "type" => "/^(boys|girls)?/"
        ] 
    )
);

$router->add(
    new Route(
        "/auth/login/", array(
            'controller' => "Login",
            'action' => 'login'
        ), [], "POST"
    )
);

$router->add(
    new Route(
        "/auth/register/", array(
            'controller' => "Login",
            'action' => 'register'
        ), [], "POST"
    )
);

makeREST($router, 'product');
makeREST($router, 'manufactors');
makeREST($router, 'sizes');
makeREST($router, 'types');
// makeREST($router, 'cart');
// makeREST($router, 'users');
