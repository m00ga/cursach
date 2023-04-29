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
        $this->path = explode('/', $path);
        $this->data = $data;
        $this->wants = $parseInfo;
        $this->_args = [];
    }

    function verify(string $uri)
    {
        $data = explode('/', $uri);
        if(count($this->path) != count($data)) {
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
                if($path != $data[$i]){
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
        $uri = $_SERVER["REQUEST_URI"];
        foreach($this->_routes as &$route){
            if($route->verify($uri) === true) {
                $route->run();
            }
        }
    }

    static function Error404()
    {

    }
}
