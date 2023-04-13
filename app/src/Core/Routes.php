<?php

require "Route.php";

$router = new Router();

$router->add(
    new Route(
        "/", array(
        'controller' => "Main",
        'action' => "index"
        ), []
    )
);

$router->add(
    new Route(
        "/show", array(
            'controller' => "Main",
            'action' => "show"
        ), array(
            "id" => "/[0-9]+/"
        )
    )
);
