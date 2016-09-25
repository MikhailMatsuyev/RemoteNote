<?php
/**
 * Класс Router
 * Компонент для работы с маршрутами
 */
class Router
{
    /**
     * Свойство для хранения массива роутов
     * @var array 
     */
    private $routes;
    /**
     * Конструктор
     */
    public function __construct()
    {
        // Путь к файлу с роутами
        $routesPath = Q_PATH . '/application/lib/routes.php';
        // Получаем роуты из файла
        $this->routes = include($routesPath);
    }
    /**
     * Возвращает строку запроса
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    /**
     * Метод для обработки запроса
     */
    public function run()
    {
        // Получаем строку запроса
        $uri = $this->getURI();

        // Проверяем наличие такого запроса в массиве маршрутов (routes.php)
        foreach ($this->routes as $uriPattern => $path) {
            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {

                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Определить контроллер, action, параметры
                $segments = explode('/', $internalRoute);
                $controllerName = 'controller'.(array_shift($segments));
				$actionName = 'action' . (array_shift($segments));
                $parameters = $segments;

                // Подключить файл класса-контроллера
                $controllerFile = Q_PATH.'/application/controllers/' .
						$controllerName . '.php';

                 if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                // Создать объект, вызвать метод (т.е. action)
				
                $controllerObject = new $controllerName;
                /* Вызываем необходимый метод ($actionName) у определенного 
                 * класса ($controllerObject)
                 */
                $controllerObject->$actionName($parameters);
            }   
        }
    }
}