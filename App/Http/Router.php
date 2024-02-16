<?php

namespace App\Http;

use App\Exceptions\RouterException;
use App\View\View;

class Router
{
    private string  $requestMethod;
    private string  $uri;
    private array   $routes           = [];
    private array   $placeholders     = [];
    private ?string $redirectEndpoint = null;
    private array   $acceptedMethods  = ["GET", "POST", "PUT", "DELETE", "OPTIONS", "HEAD"];

    public function __construct()
    {
        $this->requestMethod = $this->validateRequestMethod($_SERVER['REQUEST_METHOD']);
        $this->uri           = self::captureURI();
    }

    public function __destruct()
    {
        return $this->dispatch();
    }

    private static function captureURI()
    {
        $uri = parse_url($_SERVER['REQUEST_URI']);
        $uri = $uri['path'];

        return $uri;
    }

    private function validateRequestMethod(string $method)
    {
        $method = strtoupper($method);

        if (!in_array($method, $this->acceptedMethods)) {
            throw new RouterException("método HTTP não aceito: {$method}");
        }

        return $method;
    }

    public function __call($method, $args) 
    {
        $method = $this->validateRequestMethod($method);
        
        $this->addRoute($method, ...$args);
        return $this;
    }

    private function addRoute($method, $endpoint, $controller = [], $callbackMiddleware = null)
    {
        $newRoute = [
            'endpoint'           => $endpoint,
            'class'              => $controller[0] ?? null,
            'method'             => $controller[1] ?? null,
            'callbackMiddleware' => $callbackMiddleware
        ];

        $this->routes[$method][] = $newRoute;
    }

    public function dispatch()
    {
        $route = $this->findRoute();

        if (!$route) {
            die ((new View)->renderHTML('pages/error/404'));
        }

        $this->extractPlaceholdersValues($route['endpoint']);

        $className          = $route['class']              ?? null;
        $method             = $route['method']             ?? null;
        $callbackMiddleware = $route['callbackMiddleware'];

        // Adicionando verificação e execução do middleware de callback
        if ($callbackMiddleware) {
            return call_user_func($callbackMiddleware);
        }

        if (!$className or !$method) {
            return $this->redirect($this->redirectEndpoint);
        }

        echo (new $className)->$method((new Request($this->placeholders)));
    }

    private function findRoute()
    {
        $routes = $this->routes[$this->requestMethod];

        $route = array_filter($routes, function ($checkRoute) 
        {
            $pattern = preg_replace('#\{.*?\}#', '([^/]+)', $checkRoute['endpoint']);
            $pattern = '#^' . $pattern . '$#';

            return preg_match($pattern, $this->uri);
        });

        return $route ? reset($route) : null;
    }

    private function extractPlaceholdersValues(string $endpoint)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_-]+)\}/', '(?<$1>[^/]+)', $endpoint);
        $pattern = '#^' . $pattern . '$#';

        if (!preg_match($pattern, $this->uri, $matches)) {
            throw new RouterException("A URL não corresponde à rota esperada.");
        }

        $this->placeholders = $matches;
    }

    public function redirect(string $endpoint)
    {
        if ($this->redirectEndpoint) {
            return (new Response)->redirect($endpoint);
        }

        $this->redirectEndpoint = $endpoint;
    }
}