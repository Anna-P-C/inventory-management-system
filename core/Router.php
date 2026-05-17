<?php

class Router
{
    private array $routes = [];

   public function add(string $route, $action): void
    {
        $this->routes[$route] = $action;
    }

    public function dispatch(string $uri): void
    {
        if (array_key_exists($uri, $this->routes)) {

         $callback = $this->routes[$uri];

           if (is_string($callback)) {
               
                list($controllerName, $method) = explode('@', $callback);

            
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    
                    if (method_exists($controller, $method)) {
                        $controller->$method();
                        return;
                    }
                }
                
                http_response_code(500);
                echo "500 - Controller or Method not found";
                return;
            }

           
            if (is_callable($callback)) {
                $callback();
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page not found";
    }
}
