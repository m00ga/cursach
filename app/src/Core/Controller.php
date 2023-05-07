<?php

class Controller
{
    public array $models;
    protected View $view;

    function __construct(){
        $this->models = array();
        $this->view = new View();
    }
}
