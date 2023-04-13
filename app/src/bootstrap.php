<?php

define("ROOT_DIR", __DIR__);
require "Core/Config.php";
require "Core/Routes.php";
require "Core/Model.php";
require "Core/Controller.php";
require "Core/View.php";

$router->start();
