<?php
class App {
    protected $controller = 'Auth';
    protected $method = 'login';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Store original URL segments for method determination
        $originalUrl = $url;

        // Default values
        $controllerClass = $this->controller;
        $controllerFile = 'app/controllers/' . $this->controller . '.php';
        $methodName = 'login'; // Default method
        $paramsStartIndex = 0; // Index in $originalUrl where parameters start

        // Check for controller in URL
        if (isset($originalUrl[0])) {
            $potentialController = ucfirst($originalUrl[0]);
            $potentialControllerFile = 'app/controllers/' . $potentialController . '.php';

            if (file_exists($potentialControllerFile)) {
                $controllerClass = $potentialController;
                $controllerFile = $potentialControllerFile;
                $paramsStartIndex = 1; // Parameters start after controller
            } else {
                // Check for subdirectory controller
                if (isset($originalUrl[1])) {
                    $potentialSubdir = $originalUrl[0];
                    $potentialSubController = ucfirst($originalUrl[1]);
                    $potentialSubControllerFile = 'app/controllers/' . $potentialSubdir . '/' . $potentialSubController . '.php';

                    if (file_exists($potentialSubControllerFile)) {
                        $controllerClass = $potentialSubController;
                        $controllerFile = $potentialSubControllerFile;
                        $paramsStartIndex = 2; // Parameters start after sub-controller
                    } else {
                        show_404(); // Controller not found
                    }
                } else {
                    show_404(); // Controller not found
                }
            }
        }
        
        require_once $controllerFile;
        $this->controller = new $controllerClass;

        // Determine method
        if (isset($originalUrl[$paramsStartIndex])) {
            if (method_exists($this->controller, $originalUrl[$paramsStartIndex])) {
                $methodName = $originalUrl[$paramsStartIndex];
                $paramsStartIndex++; // Parameters start after method
            } else {
                show_404(); // Method not found
            }
        } else {
            // If no method is specified, default to 'index'
            if (method_exists($this->controller, 'index')) {
                $methodName = 'index';
            } else {
                show_404(); // Default method 'index' not found
            }
        }
        $this->method = $methodName;

        // Extract parameters
        $this->params = $originalUrl ? array_slice($originalUrl, $paramsStartIndex) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return null;
    }
}
