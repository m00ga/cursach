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
    private array $_args;

    function __construct(string $path, array $data, array $parseInfo)
    {
        $this->path = explode('/', trim($path, "/"));
        // $this->path = explode('/', $path);
        $this->data = $data;
        $this->wants = $parseInfo;
        $this->_args = [];
    }

    function verify(string $uri)
    {
        $data = explode('/', $uri);
        if(!isset($this->path[0]) || $this->path[0] != $data[0]) {
            return false;
        }
        if(isset($this->path[1]) && $this->path[1] != $data[1]) {
            return false;
        }
        if(isset($this->wants) && count($this->wants) != (count($data) - count($this->path))) {
            return false;
        }
        $raw_args = array_slice($data, count($this->path));
        $keys = array_keys($this->wants);
        for($i = 0; $i < count($raw_args); ++$i){
            if(preg_match($this->wants[$keys[$i]], $raw_args[$i], $matches) === 0) {
                return false;
            }
            $this->_args[$keys[$i]] = $matches[0];
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
        $modelName = "Model_".$this->data['controller'];

        $modelPath = ROOT_DIR."/Models/".strtolower($modelName).".php";
        if(file_exists($modelPath)) {
            include $modelPath;
        }

        $controllerPath = ROOT_DIR."/Controllers/".strtolower($controllerName).".php";
        if(file_exists($controllerPath)) {
            include $controllerPath;
        }else{
            throw new RouteException($controllerName." not found");
        }

        $controllerObj = new $controllerName;
        if(method_exists($controllerObj, $this->data['action'])) {
            $action = $this->data['action'];
            $controllerObj->$action($this->_args);
        }else{
            throw new RouteException($this->data['action']." not found");
        }
    }
}

class Router
{
    private array $_routes;

    function add(Route $route)
    {
        $this->_routes[] = $route;
    }

    function start()
    {
        $uri = trim($_SERVER["REQUEST_URI"], '/');
        foreach($this->_routes as &$route){
            if($route->verify($uri) === true) {
                $route->run();
            }
        }
    }

    static function Error404(){

    }
}

// class Route
// {
//     static function start()
//     {
//         $path = $_SERVER["REQUEST_URI"] ?? "/";
//         $path = ltrim($path, "/");
//         $data = explode("/", $path);
//         var_dump($data);
//
//         $controller = $data[0] ?? "Main";
//         $action = $data[1] ?? "index";
//         $args = array_slice($data, 2);
//
//         $controllerName = "Controller_".$controller;
//         $modelName = "Model_".$controller;
//         $actionName = "action_".$action;
//
//         $modelPath = __DIR__."/Models/".strtolower($modelName);
//         if (file_exists($modelPath)) {
//             include $modelPath;
//         }
//
//         $controllerPath = __DIR__."/Controllers/".strtolower($controllerName);
//         if (file_exists($controllerPath)) {
//             include $controllerPath;
//         } else {
//
//         }
//
//         $controllerObj = new $controllerName;
//         if (method_exists($controllerObj, $actionName)) {
//             $controllerObj->$actionName($args);
//         } else {
//
//         }
//     }
// }
