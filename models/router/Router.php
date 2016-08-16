<?php

class Router
{
    /**
     * Массим со всеми назначеными путями.
     *
     * @var array
     */
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    /**
     * Добавить в список путь с методом GET.
     *
     * @param string $route
     * @param string $controller
     * @param string $action
     */
    public function get($route, $controller, $action)
    {
        $this->addRoute('GET', $route, $controller, $action);
    }

    /**
     * Добавить в список путь с методом POST.
     *
     * @param string $route
     * @param string $controller
     * @param string $action
     */
    public function post($route, $controller, $action)
    {
        $this->addRoute('POST', $route, $controller, $action);
    }

    /**
     * Добавить в список путь с методом PUT.
     *
     * @param string $route
     * @param string $controller
     * @param string $action
     */
    public function put($route, $controller, $action)
    {
        $this->addRoute('PUT', $route, $controller, $action);
    }

    /**
     * Добавить в список путь с методом DELETE.
     *
     * @param string $route
     * @param string $controller
     * @param string $action
     */
    public function delete($route, $controller, $action)
    {
        $this->addRoute('DELETE', $route, $controller, $action);
    }

    /**
     * Найти подходящий путь и вернуть данные для него.
     *
     * @throws Exception
     * @param string $method
     * @param string $path
     * @return array
     */
    public function findRoute($method, $path)
    {
        $parts = explode('/', $path);
        $count = count($parts);

        $matches = [];

        // Находим пути с таким же количеством элементов.
        foreach ($this->routes[$method] as $route) {
            if ($route['Size'] == $count) {
                $matches[] = $route;
            }
        }
        // Ищем путь.
        foreach ($matches as $match) {
            // В первую очередь проверяем пути без пемеренных.
            if (!array_key_exists('Variables', $match)) {
                if ($route = $this->checkStaticRoute($match, $parts, $count)) {
                    return [
                        'Controller' => $route['Controller'],
                        'Action' => $route['Action']
                    ];
                }
            } else {
                if ($route = $this->checkDynamicRoute($match, $parts, $count)) {
                    return [
                        'Controller' => $route['Controller'],
                        'Action' => $route['Action'],
                        'Wildcards' => $route['Wildcards']
                    ];
                }
            }
        }

        return [404 => 'Route not found.'];
    }

    /**
     * Вернуть список всех путей.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Добавить путь в свойство $routes.
     *
     * @param string $method
     * @param string $route
     * @param string $controller
     * @param string $action
     */
    private function addRoute($method, $route, $controller, $action)
    {
        $parts = explode('/', $route);
        $count = count($parts);

        $variables = [];

        foreach ($parts as &$part) {
            if (strpos($part, '{') !== false) {
                $variables[] = trim($part, '{}');
                $part = preg_replace('/{[A-z]\w+}/', '*', $part);
            }
        }

        $fullRoute = [
            'Route' => $parts,
            'Size' => $count,
            'Controller' => $controller,
            'Action' => $action
        ];

        if (!empty($variables)) {
            $fullRoute['Variables'] = $variables;
        }

        $this->routes[$method][] = $fullRoute;
    }

    /**
     * Проверить существует ли путь без переменных.
     *
     * @param string $match
     * @param array $parts
     * @param int $count
     * @return array|null
     */
    private function checkStaticRoute($match, $parts, $count)
    {
        // Количество совпадений в пути.
        $matchCount = 0;

        for ($i = 0; $i <= ($count-1); $i++) {
            if ($match['Route'][$i] == $parts[$i]) {
                $matchCount++;
            }
        }

        if ($matchCount == $count) {
            return $match;
        }

        return null;
    }

    /**
     * Проверить существует ли путь с переменными.
     *
     * @param string $match
     * @param array $parts
     * @param int $count
     * @param int $iteration
     * @return null
     */
    private function checkDynamicRoute($match, $parts, $count, $iteration = null)
    {
        // Итерация рекурсивного повтора метода.
        $iteration = 0;

        for ($i = 0; $i <= ($count-1); $i++) {
            if ($match['Route'][$i] == $parts[$i] || $match['Route'][$i] == '*') {
                $iteration++;

                if ($match['Route'][$i] == '*') {
                    $match['Wildcards'][] = $parts[$i];
                }

                $this->checkStaticRoute($match, $parts, $count, $iteration);
            }

            if ($iteration == $count) {
                return $match;
            }
        }

        return null;
    }
}