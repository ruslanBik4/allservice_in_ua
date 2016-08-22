<?php

class Router
{
    /**
     * Список назначенных путей.
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
     * Вернуть список всех путей.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Найти подходящий путь и вернуть данные для него.
     *
     * @param string $method
     * @param string $path
     * @return array
     */
    public function findRoute($method, $path)
    {
        // Убрать строку запроса
        if ($position = strpos($path, "?")) {
            $path = substr($path, 0, strpos($path, "?"));
        }

        // Убрать '/'
        $path = rtrim($path, '/');
        $path = ($path == '') ? '/' : $path;

        $parts = explode('/', $path);
        $count = count($parts);

        // Массив для хранения потенциально подходящих путей
        $matches = [];

        // Найти пути с таким же количеством элементов
        foreach ($this->routes[$method] as $route) {
            if ($route['Size'] == $count) {
                $matches[] = $route;
            }
        }

        // Найти нужный путь среди потенциально походящих путей
        foreach ($matches as $match) {
            // В первую очередь проверить среди путей без пемеренных
            if (!array_key_exists('Variables', $match)) {
                if ($route = $this->checkStaticRoute($match, $parts, $count)) {
                    return [
                        'Controller' => $route['Controller'],
                        'Action' => $route['Action']
                    ];
                }
            } else {
                // Проверить среди путей с переменными
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
     * Добавить новый путь.
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

        //
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
     * Проверить существует ли путь (для путей без переменных).
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
     * Проверить существует ли путь (для путей с переменными).
     *
     * @param string $match
     * @param array $parts
     * @param int $count
     * @param int $iteration
     * @return array|null
     */
    private function checkDynamicRoute($match, $parts, $count, $iteration = null)
    {
        // Количество рекурсивных итераций
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