<?php

class Controller_Login extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->models = [
            "model_users"
        ];
    }

    private function _validate($post)
    {
        preg_match("/\S+/", $post['password'], $matches);
        if(count($matches) > 1) {
            return "Special chars doesn't allowed";
        }
        if(strlen($post['password']) < 6) {
            return "Password must be at least 6 chars";
        }
        if(!preg_match("/(?:\d+)/", $post['password'])) {
            return "Password must contain at least 1 number";
        }
        return true;
    }

    private function _sendData($data, $code)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['data' => $data, 'status' => $code]);
    }

    function verify($params)
    {
        try{
            JWT::verify($params['token']);
        }catch (JWTException $e){
            http_response_code(400);
            $this->_sendData($e->getMessage(), -1);
            return;
        }

        http_response_code(200);
    }

    function register()
    {
        if(!isset($_POST['login']) || !isset($_POST['password'])) {
            http_response_code(400);
            return;
        }

        $res = $this->_validate($_POST);
        if($res !== true) {
            http_response_code(400);
            $this->_sendData($res, -1);
            return;
        }

        $model = new Model_User();

        $ret = $model->read($_POST['login']);
        if($ret !== false) {
            http_response_code(400);
            $this->_sendData("User already exists", -1);
            return;
        }
        
        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $model->create(
            ['login' => $_POST['login'],
            'pass_hash' => $hash,
            'role' => 0
            ]
        );
        $token = JWT::encode(['login' => $_POST['login'], 'role' => 0, 'exp' => time() + 3600]);
        http_response_code(200);
        $this->_sendData($token, 1);
    }

    function login()
    {
        if(!isset($_POST['login']) || !isset($_POST['password'])) {
            http_response_code(400);
            return;
        }

        $res = $this->_validate($_POST);
        if($res !== true) {
            http_response_code(400);
            $this->_sendData($res, -1);
            return;
        }

        $model = new Model_User();
        $status = $model->read($_POST['login']);
        if($status === false) {
            http_response_code(400);
            $this->_sendData("User not found", -1);
            return;
        }

        if(password_verify($_POST['password'], $status['pass_hash'])) {
            $token = JWT::encode(['login' => $model->login, 'role' => $model->role, 'exp' => time() + 3600]);
            http_response_code(200);
            $this->_sendData($token, 1);
        }else {
            http_response_code(400);
            $this->_sendData("Wrong password", -1);
        }
    }
}
