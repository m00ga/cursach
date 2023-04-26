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
