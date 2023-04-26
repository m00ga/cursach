<?php

class Controller
{
    protected Model $model;
    protected View $view;

    function __construct(){
        $this->view = new View();
    }
}
