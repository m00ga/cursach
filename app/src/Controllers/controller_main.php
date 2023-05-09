<?php

class Controller_Main extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->models = array(
        "model_main",
        "model_manufactors",
        "model_types",
        "model_sizes"
        );
    }

    function index($params)
    {
        if(!isset($_SESSION['gender'])) {
            if($params['type'] != "") {
                $_SESSION['gender'] = $params['type'];
            }else{
                $_SESSION['gender'] = "boys";
            }
        }else if($_SESSION['gender'] != $params['type']) {
            if($params['type'] != "") {
                $_SESSION['gender'] = $params['type'];
            }
        }
        setcookie("gender", $_SESSION['gender']);
        $args = array();
        $man = new Model_Manufactor();
        $args['man'] = array(
        $man->getAll(),
        "Виробники"
        );
        $typ = new Model_Type();
        $args['typ'] = array(
        $typ->getAll(),
        "Типи"
        );
        $size = new Model_Size();
        $args["size"] = array(
        $size->getAll(),
        "Розмір"
        );
        $this->view->generate("index_view", "basic_view.php", $args);
    }
}
