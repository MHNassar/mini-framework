<?php

namespace Core;

class Router
{
    private static $instance;

    private static $routes = [
        "ANY" => [],
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "PATCH" => [],
        "DELETE" => [],
        "OPTION" => []
    ];
    private static $middlewares = [];
    private static $latestMethods = [];
    private static $current = false;

    private static $controllerNamespace = "App\\Controllers\\";
    private static $controllerNameEnd = "";
    private static $middlewareNamespace = "App\\Middlewares\\";
    private static $middlewareNameEnd = "";

    private function __construct()
    {
    }

    private static function getUrl($url)
    {
        $url = ltrim(urldecode($url), "/");
        $url = ltrim(urldecode($url), Config::get("env.root_url"));
        $url = ltrim(urldecode($url), "/");
        return $url;
    }

    private static function getRoutes()
    {
        $routes = [];
        $method = self::getRequestMethod();
        if (is_array(self::$routes["ANY"]) && is_array(self::$routes[$method]))
            $routes = array_merge(self::$routes["ANY"], self::$routes[$method]);
        return $routes;
    }

    private static function controlUrl($url, $rUrl, &$parameters)
    {
        $rUrl = preg_replace("@{([0-9a-zA-Z]+)}@", "(.*?)", $rUrl);
        $rUrl = preg_replace("@{([0-9a-zA-Z]+)\?}@", "(.*?|)", $rUrl);

        $rUrl = preg_replace("@{/}@", "(/?)", $rUrl);

        $result = preg_match("@^" . $rUrl . "$@", $url, $parameters);
        unset($parameters[0]);
        $parameters = array_values($parameters);
        return $result;
    }

    private static function clearParameters(&$parameters, $values = [])
    {
        if (count($values)) {
            foreach ($values as $value) {
                unset($parameters[$value]);
            }
            $parameters = array_values($parameters);
        } else {
            for ($i = count($parameters); $i > 0; $i--) {
                if (isset($parameters[$i]) && ($parameters[$i] == "/" || !$parameters[$i])) {
                    unset($parameters[$i]);
                }
            }
            $parameters = array_values($parameters);
        }
    }

    private static function getController($controller, &$parameters, &$unset)
    {
        if ($controller == "{?}") {
            if (isset($parameters[0])) {
                $controller = ucfirst(strtolower($parameters[0]));
                unset($parameters[0]);
            } else die("Controller parameter not found!");
        } else if (preg_match("@{([0-9]+)}@", $controller, $cont)) {
            if (isset($parameters[$cont[1]])) {
                $controller = ucfirst(strtolower($parameters[$cont[1]]));
                $unset[] = $cont[1];
            } else die("Controller parameter not found!");
        }
        $parameters = array_values($parameters);
        return self::$controllerNamespace . $controller . self::$controllerNameEnd;
    }

    private static function getMethod($method, &$parameters, &$unset)
    {
        if ($method == "{?}") {
            if (isset($parameters[0])) {
                $method = strtolower($parameters[0]);
                unset($parameters[0]);
            } else die("Method parameter not found!");
        } else if (preg_match("@{([0-9]+)}@", $method, $meth)) {
            if (isset($parameters[$meth[1]])) {
                $method = strtolower($parameters[$meth[1]]);
                $unset[] = $meth[1];
            } else die("Method parameter not found!");
        }
        return $method;
    }

    private static function handleMiddleware($middlewares)
    {

        if (count($middlewares)) {
            foreach ($middlewares as $middleware) {
                $middleware = self::$middlewareNamespace . $middleware . self::$middlewareNameEnd;
                if (class_exists($middleware)) {
                    if (method_exists($middleware, "handle")) {
                        forward_static_call([$middleware, "handle"]);
                    }
                }
            }
        }
    }

    private static function handleController($controller, &$parameters)
    {
        if (is_string($controller)) {
            if (preg_match("/^([{?}a-zA-Z0-9]+)@([{?}a-zA-Z0-9]+)$/", $controller, $result)) {
                if (isset($result[1]) && isset($result[2])) {
                    $unset = [];
                    $controller = self::getController($result[1], $parameters, $unset);
                    $method = self::getMethod($result[2], $parameters, $unset);

                    self::clearParameters($parameters, $unset);

                    if (class_exists($controller)) {
                        if (method_exists($controller, $method)) {
                            $controller = new $controller();
                            $return = call_user_func_array([$controller, $method], $parameters);
                            if (is_array($return))
                                echo json_encode($return);
                        } else die("<b>" . $controller . "</b> controller <b>" . $method . "</b> No method was found!");
                    } else die("<b>" . $controller . "</b> No controller found!");
                } else die("Router <b>controller@method</b> problem");
            }
        } else if (is_callable($controller)) {
            $return = call_user_func_array($controller, $parameters);
            if (is_array($return))
                echo json_encode($return);
        }
    }

    private static function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST") {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                    $headers[str_replace([' ', 'Http'], ['-', 'HTTP'], ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], ['PUT', 'DELETE', 'PATCH', 'OPTION'])) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }
        return $method;
    }

    private static function setCurrent($name, $url, $pattern, $parameters, $method)
    {
        self::$current = [
            "name" => $name,
            "url" => $url,
            "pattern" => $pattern,
            "parameters" => $parameters,
            "method" => $method
        ];
    }

    static function Run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = self::getUrl($url);
        $routes = self::getRoutes();
        foreach ($routes as $route) {
            $parameters = [];
            if (!self::controlUrl($url, $route["url"], $parameters))
                continue;
            self::clearParameters($parameters);
            self::handleMiddleware($route["middleware"]);
            self::handleController($route["function"], $parameters);
            self::setCurrent($route["name"], $url, $route["url"], $parameters, self::getRequestMethod());
            break;
        }
    }

    static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new self;
        return self::$instance;
    }

    static function setControllerNamespace($namespace, $nameend = "")
    {
        self::$controllerNamespace = $namespace;
        self::$controllerNameEnd = $nameend;
    }

    static function setMiddlewareNamespace($namespace, $nameend = "")
    {
        self::$middlewareNamespace = $namespace;
        self::$middlewareNameEnd = $nameend;
    }


    static function middleware($middleware)
    {
        $instance = self::getInstance();
        if (is_array($middleware))
            self::$middlewares = $middleware;
        else
            self::$middlewares[] = $middleware;
        return $instance;
    }


    static function match($methods, $url, $function)
    {
        $instance = self::getInstance();
        self::$latestMethods = [];
        if (!is_array($methods))
            self::$latestMethods = explode("|", $methods);
        if (is_array(self::$latestMethods) && count(self::$latestMethods)) {
            foreach (self::$latestMethods as $method) {
                $middlewares = [];
                $url = trim($url, "/");
                if (is_array(self::$middlewares)) {
                    $middlewares = self::$middlewares;
                }


                self::$routes[strtoupper($method)][] = [
                    "name" => "",
                    "url" => $url,
                    "function" => $function,
                    "where" => null,
                    "middleware" => $middlewares
                ];
            }
        }
        return $instance;
    }

    static function any($url, $function)
    {
        return self::match("ANY", $url, $function);
    }

    static function get($url, $function)
    {
        return self::match("GET", $url, $function);
    }

    static function post($url, $function)
    {
        return self::match("POST", $url, $function);
    }

    static function put($url, $function)
    {
        return self::match("PUT", $url, $function);
    }

    static function patch($url, $function)
    {
        return self::match("PATCH", $url, $function);
    }

    static function delete($url, $function)
    {
        return self::match("DELETE", $url, $function);
    }

    static function option($url, $function)
    {
        return self::match("OPTION", $url, $function);
    }

}
