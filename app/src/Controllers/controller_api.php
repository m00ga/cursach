<?php

function checkAuth()
{
    return !!($_SESSION['user_id'] ?? false);
}

class RESTify
{
    private Model $_model;
    public int $id;

    function __construct(Model $model, int $id)
    {
        $this->_model = $model;
        $this->id = $id;
    }

    function get()
    {
        if($this->id !== 0) {
            $res = $this->_model->read($this->id);
        }else if (count($_GET) > 0) {
            $res = $this->_model->filterBy($_GET);
        }else{
            $res = $this->_model->getAll(PDO::FETCH_ASSOC);
        }
        header('Content-Type: application/json; charset=utf-8');
        if($res !== false) {
            echo json_encode($res);
        }else{
            http_response_code(404);
        }
    }

    function post(array $params)
    {
        // if(!checkAuth()) {
        //     http_response_code(401);
        //     return;
        // }
        $ret = $this->_model->create($params);
        if($ret === true) {
            http_response_code(201);
        }else{
            http_response_code(400);
        }
    }

    function put(array $params)
    {
        // if(!checkAuth()) {
        //     http_response_code(401);
        //     return;
        // }
        $ret = $this->_model->update($this->id, $params);
        if($ret === true) {
            http_response_code(201);
        }else{
            http_response_code(400);
        }
    }

    function delete()
    {
        // if(!checkAuth()) {
        //     http_response_code(401);
        //     return;
        // }
        $ret = $this->_model->delete($this->id);
        if($ret === true) {
            http_response_code(200);
        }else{
            http_response_code(400);
        }
    }

    function process()
    {
        switch($_SERVER['REQUEST_METHOD']){
        case "GET":
            $this->get();
            break;
        case "POST":
            $this->post($_POST); 
            break;
        case "PUT":
            parse_str(file_get_contents("php://input"), $put_vars);
            $this->put($put_vars);
            break;
        case "DELETE":
            $this->delete();
            break;
        }
    }
}

class Controller_API extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->models = [
            "model_main",
            "model_manufactors",
            "model_sizes",
            "model_types",
            "model_cart"
        ];
    }

    function product($params)
    {
        $model = new Model_Main();
        $rest = new RESTify($model, ($params['id'] != "")? intval($params['id']):0);
        $rest->process();
    }

    function manufactors($params)
    {
        $model = new Model_Manufactor();
        $rest = new RESTify($model, ($params['id'] != "")? intval($params['id']):0);
        $rest->process();
    }

    function sizes($params)
    {
        $model = new Model_Size();
        $rest = new RESTify($model, ($params['id'] != "")? intval($params['id']):0);
        $rest->process();
    }

    function types($params)
    {
        $model = new Model_Type();
        $rest = new RESTify($model, ($params['id'] != "")? intval($params['id']):0);
        $rest->process();
    }

    function cart($params){
        $model = new Model_Cart();
        $rest = new RESTify($model, ($params['id'] != "")? intval($params['id']):0);
        $rest->process();
    }
}
