<?php

require "Route.php";

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
