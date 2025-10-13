<?php

class Router
{
    private $routes = [];

    // Додати новий маршрут
    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    // Визначити, який маршрут відповідає запиту
    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            // Замінити фігурні дужки на регулярні вирази для параметрів у шляху
            $pattern = str_replace('/', '\/', $route['path']);
            $pattern = preg_replace('/\{(\w+)\}/', '(?<$1>[^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                // Додаємо параметри до масиву $route
                $route['params'] = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $route;
            }
        }

        return null;
    }

    // Обробити запит
    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $route = $this->match($method, $uri);

        if ($route) {
            if (is_callable($route['handler'])) {
                // Якщо обробник є функцією, викличте його та передайте параметри
                call_user_func_array($route['handler'], $route['params'] ?? []);
            } else {
                // Якщо обробник - це строка, можливо, це ім'я класу та методу
                $class = $route['handler'][0];
                $pdo = $route['handler'][1];
                $method = $route['handler'][2];
                $classInstance = new $class($pdo);
                // Викликайте метод та передавайте параметри
                call_user_func_array([$classInstance, $method], $route['params'] ?? []);
            }
        } else {
            // Маршрут не знайдено, вивести помилку 404
            header("HTTP/1.0 404 Not Found");
            echo '404 Not Found';
        }
    }
}