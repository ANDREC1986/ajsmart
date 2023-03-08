<?php

namespace App\Http;
use Closure;
use Exception;
use ReflectionFunction;

class Roteador {
    private $url;
    private $prefix;
    private $routes = [];
    private $request;

    public function __construct($url){
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    public function setPrefix() {
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ?? '';
    }

    public function addRoute($method, $route, $params = []){
        foreach ($params as $key=>$value){
            if($value instanceof Closure){
                $params['Controller'] = $value;
                unset($params[$key]);
            }
        }

        $params['variables'] = [];            
        
        $pattternVariable = '/{(.*?)}/';
        if(preg_match_all($pattternVariable,$route,$matches)){
            $route = preg_replace($pattternVariable,'(.*?)',$route);
            $params['variables'] = $matches[1];
        }

        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';
        $this->routes[$patternRoute][$method] = $params;
    }

    public function get($route,$params = []){
        return $this->addRoute('GET', $route, $params);
    }

    public function post($route,$params = []){
        return $this->addRoute('POST', $route, $params);
    }

    public function put($route,$params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    public function delete($route,$params = []){
        return $this->addRoute('DELETE', $route, $params);
    }

    private function getRoute(){
        $uri = $this->getUri();       
        $httpMethod = $this->request->getHttpMethod();
        foreach($this->routes as $patternRoute=>$methods){
            if(preg_match($patternRoute,$uri,$matches)){
                if(isset($methods[$httpMethod])){
                    unset($matches[0]);
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                    return $methods[$httpMethod];
                }
                throw new Exception('Forbbiden Method',405);
            }
        } throw new Exception('Page not Found',404);
    }

    private function getUri(){
        $uri = $this->request->getUri();
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];
        return end($xUri);
    }
    public function run(){
        try{
            $route = $this->getRoute();
            if (!isset($route['Controller'])){
                throw new Exception('Erro de Processamento',500);
            }

            $args = [];
            $reflection = new ReflectionFunction($route['Controller']);
            foreach($reflection->getParameters() as $paramenter){
                $name = $paramenter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            return call_user_func_array($route['Controller'],$args);
        }
        catch(Exception $e) {
            return new Response($e->getCode(),$e->getMessage());
        }
    }
}