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
            "model_sizes",
            "model_users",
            "model_genders"
        );
    }

    private function translateParams($params, $model)
    {
        $translateTable = array(
        'rows' => 'limit',
        'sidx' => 'orderBy',
        'sord' => 'orderType',
        );
        $ret = [];
        foreach($params as $k=>$v){
            if(in_array($k, array_keys($model->schema))) {
                $v .= "%";
            }
            if(in_array($k, array_keys($translateTable))) {
                $ret[$translateTable[$k]] = $v;
            }else {
                $ret[$k] = $v;
            }
        }

        return $ret;
    }
    private function getModel($name)
    {
        switch($name) {
        case 'product':
            return new Model_Main();
        case "types":
            return new Model_Type();
        case "manufactors":
            return new Model_Manufactor();
        case "genders":
            return new Model_Gender();
        case "sizes":
            return new Model_Size();
        case "users":
            return new Model_User();
        default:
            return null;
        }
    }

    function jqGrid($params)
    {
        $model = $this->getModel($params['grid']);
        if($model === null) {
            http_response_code(400);
            return;
        }
        $ret = [];

        $params = $this->translateParams($_GET, $model);
        $ret['records'] = $model->count();
        $ret['total'] = ceil($ret['records'] / $_GET['rows']);
        $ret['page'] = $_GET['page'];
        $params['offset'] = ($ret['page'] - 1) * $params['limit'];

        $ret['rows'] = $model->filterBy(
            $params
        );

        echo json_encode($ret);
    }

    function jqGridEdit($params)
    {
        $model = $this->getModel($params['grid']);
        if($model === null) {
            http_response_code(400);
            return;
        }
        $oper = $_POST['oper'];
        switch($oper){
        case "add":
            $model->create($_POST);
            break;
        case 'edit':
            $id = $_POST['id'];
            unset($_POST['id']);
            $model->update($id, $_POST);
            break;
        case "del":
            $id = $_POST['id'];
            $model->delete($id);
            break;
        }
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
        setcookie("gender", $_SESSION['gender'], ['samesite' => 'Strict']);
        $args = array();
        $man = new Model_Manufactor();
        $args['menu']['manufactor'] = array(
        $man->getAll(),
        "Виробники"
        );
        $typ = new Model_Type();
        $args['menu']['type'] = array(
        $typ->getAll(),
        "Типи"
        );
        $size = new Model_Size();
        $args['menu']["size"] = array(
        $size->getAll(),
        "Розмір"
        );
        $args['productCount'] = (new Model_Main())->count();
        $this->view->generate("index_view", "basic_view.php", $args);
    }

    function admin($params)
    {
        // if(!isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])){
        //     http_response_code(401);
        //     return;
        // }
        // $data = JWT::decode($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        // if($data === false) {
        //     http_response_code(401);
        //     return;
        // }
        // if($data['role'] !== 1) {
        //     http_response_code(401);
        //     return;
        // }

        $this->view->generate("admin_view");
    }
}
