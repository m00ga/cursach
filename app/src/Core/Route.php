<?php

class RouteException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
            parent::__construct($message, $code, $previous);
    }
}

class Route
{
    public array $path;
    public array $data;
    public array $wants;
    public string $method;
    private array $_args;

    function __construct(string $path, array $data, array $parseInfo, string $method = "GET")
    {
        $this->path = explode('/', $path);
        $this->data = $data;
        $this->wants = $parseInfo;
        $this->method = $method;
        $this->_args = [];
    }

    function verify(string $uri)
    {
        $get = explode("?", $uri);
        $data = explode('/', $get[0]);
        if(count($this->path) != count($data)) {
            return false;
        }
        if($_SERVER["REQUEST_METHOD"] !== $this->method) {
            return false;
        }
        $keys = array_keys($this->wants);
        for($i = 0; $i < count($this->path); $i++){
            $path = trim($this->path[$i], "{}");
            if(in_array($path, $keys)) {
                if(preg_match($this->wants[$path], $data[$i], $matches) === 0) {
                    return false;
                }
                $this->_args[$path] = $matches[0];
            }else{
                if($path != $data[$i]) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Main function for route
     * * $this->data example:
     * * array('controller' => "Product",
     * *       'action' => "showAction")
     *
     * @return void
     **/
    function run(): void
    {
        if(!isset($this->data['controller'])) {
            throw new RouteException("Controller isn't setted");
        }
        if(!isset($this->data['action'])) {
            throw new RouteException("Action isn't setted");
        }
        $controllerName = "Controller_".$this->data['controller'];

        $controllerPath = ROOT_DIR."/Controllers/".strtolower($controllerName).".php";
        if(file_exists($controllerPath)) {
            include $controllerPath;
        }else{
            throw new RouteException($controllerName." not found");
        }

        $controllerObj = new $controllerName;
        if(method_exists($controllerObj, $this->data['action'])) {
            if(count($controllerObj->models) > 0) {
                $modelsPath = ROOT_DIR."/Models/";
                foreach($controllerObj->models as $model){
                    include $modelsPath.strtolower($model).".php";
                }
            }
            $action = $this->data['action'];
            $controllerObj->$action($this->_args);
        }else{
            throw new RouteException($this->data['action']." not found");
        }
    }
}

class Router
{
    private array $_nroutes;

    function __construct()
    {
        $this->_nroutes = array();
        $this->_nroutes['noone'] = array();
    }

    function add(Route $route)
    {
        $data = array_slice($route->path, 1, count($route->path) - 2);
        $point = &$this->_nroutes;
        if(count($data) < 2) {
            $point = &$point['noone'];
        } else {
            foreach($data as $sub){
                if(!isset($point[$sub])) {
                    $point[$sub] = array();
                }
                $point = &$point[$sub];
            }
        }
        array_push($point, $route);
    }

    function start()
    {
        $uri = $_SERVER["REQUEST_URI"];
        $data = explode("/", $uri);
        $data = array_slice($data, 1, count($data) - 2);
        $point = &$this->_nroutes;
        if(count($data) < 2) {
            $point = $point['noone'];
        }else {
            foreach($data as $sub){
                if(!isset($point[$sub])) {
                    Router::Error404();
                    return;
                }
                $point = &$point[$sub];
            }
        }
        foreach($point as $route){
            if($route->verify($uri) === true) {
                $route->run();
                return;
            }
        }
        Router::Error404();
    }

    static function Error404()
    {
        echo "aga";
    }
}
