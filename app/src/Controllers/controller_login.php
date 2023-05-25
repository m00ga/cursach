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

    // function verify($params) {
    //     $token = $params['token'];
    //     $res = 
    // }

    private function _sendData($data, $code)
    {
        echo json_encode(['data' => $data, 'status' => $code]);
    }

    function register()
    {
        $model = new Model_User();
        if(!isset($_POST['login']) || !isset($_POST['password'])) {
            http_response_code(400);
            return;
        }
        $ret = $model->read($_POST['login']);
        if($ret !== false) {
            $this->_sendData("User already exists", -1);
            return;
        }
        preg_match("/\S+/", $_POST['password'], $matches);
        if(count($matches) > 1) {
            $this->_sendData("Special chars doesn't allowed", -1);
            return;
        }
        if(strlen($_POST['password']) < 6) {
            $this->_sendData("Password must be at least 6 chars", -1);
            return;
        }
        if(!preg_match("/(?:\d+)/", $_POST['password'])) {
            $this->_sendData("Password must contain at least 1 number", -1);
            return;
        }

        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $model->create(
            ['login' => $_POST['login'],
            'pass_hash' => $hash,
            'role' => 0
            ]
        );
        http_response_code(200);
    }

    function login()
    {
        $model = new Model_User();
        if(!isset($_POST['login']) || !isset($_POST['password'])) {
            http_response_code(400);
            return;
        }
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
