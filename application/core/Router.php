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
           $controller = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller.php';
           if(class_exists($controller)){

           }else{
                echo "Не найден:". $controller;
           }
        }else{
            echo "Маршрут не найден";
        }
    }
    
}
