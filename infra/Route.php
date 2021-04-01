<?php
namespace infra;

use controller\Controller;
use model\utils\StringUtils;

class Route {

    private static $controllers = [];
    private static $routes = array();
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    /**
     * @param $expression
     * @param $method
     * @param $function
     */
    public static function add($expression, $method, $function){
        array_push(self::$routes,Array(
            'expression' => $expression,
            'method' => $method,
            'function' => $function
        ));
    }

    /**
     * @param $controllerClass
     * @param Controller $instance
     */
    public static function addController($controllerClass, Controller $instance){
        self::$controllers[$controllerClass] = $instance;
    }

    /**
     * @param $function
     */
    public static function pathNotFound($function){
        self::$pathNotFound = $function;
    }

    /**
     * @param $function
     */
    public static function methodNotAllowed($function){
        self::$methodNotAllowed = $function;
    }

    /**
     *
     */
    public static function initControllers() {
        foreach(self::$controllers as $controller) {
            $controller->initRoutes();
        }
    }

    /**
     * @param string $basepath
     */
    public static function run($basepath = '/'){
        self::initControllers();
        // Parse current url
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri

        if(isset($parsed_url['path'])){
            $path = $parsed_url['path'];
        }else{
            $path = '/';
        }

        if($path != '/' && StringUtils::endsWith($path, '/')) {
            $path = $path.substr(0, strlen($path) - 1);
        }

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        $path_match_found = false;

        $route_match_found = false;

        foreach(self::$routes as $route){

            // If the method matches check the path

            // Add basepath to matching string
            if($basepath!=''&&$basepath!='/'){
                $route['expression'] = '('.$basepath.')'.$route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^'.$route['expression'];

            // Add 'find string end' automatically
            $route['expression'] = $route['expression'].'$';

            // Check path match
            if(preg_match('#'.$route['expression'].'#',$path,$matches)){
                $path_match_found = true;

                // Check method match
                if(strtolower($method) == strtolower($route['method'])){

                    array_shift($matches);// Always remove first element. This contains the whole string

                    if($basepath!=''&&$basepath!='/'){
                        array_shift($matches);// Remove basepath
                    }

                    call_user_func_array($route['function'], $matches);

                    $route_match_found = true;

                    // Do not check other routes
                    break;
                }
            }
        }

        // No matching route was found
        if(!$route_match_found){

            // But a matching path exists
            if($path_match_found){
                header("HTTP/1.0 405 Method Not Allowed");
                if(self::$methodNotAllowed){
                    call_user_func_array(self::$methodNotAllowed, Array($path,$method));
                }
            }else{
                header("HTTP/1.0 404 Not Found");
                if(self::$pathNotFound){
                    call_user_func_array(self::$pathNotFound, Array($path));
                }
            }

        }

    }

}
