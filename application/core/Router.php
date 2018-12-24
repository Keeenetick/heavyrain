<?php
namespace application\core;
class Router{
    protected $routes = [];
    protected $params = [];
    public function __construct(){
        $array = require 'application/config/routes.php';
        foreach ($array as $key => $val) {
            $this->addRouter($key, $val);
        }
        
    }
    public function addRouter($route, $params){
        $route = '#^'.$route.'$#';              //Регулярные выражения
        $this->routes[$route] = $params;
    }
    public function matchRouter(){
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
            if(preg_match($route, $url, $matches)){
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
    public function startRouter(){
        if($this->matchRouter()){
           $path = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
           if(class_exists($path)){
                $action = $this->params['action'].'Action';
                if(method_exists($path, $action)){
                    $controller = new $path($this->params);
                    $controller->$action();
                }else{
                    echo "Не найден action" . $action;
                }
           }else{
                echo "Не найден controller:". $path;
           }
        }else{
            echo "Маршрут не найден";
        }
    }
    
}
