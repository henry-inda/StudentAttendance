<?php
class App {
    protected $controller = 'Auth';
    protected $method = 'login';
    protected $params = [];

    public function __construct() {
        require_once 'app/config/config.php'; // Include config for SESSION_NAME
        if (session_status() == PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            // Set session cookie security flags
            ini_set('session.cookie_httponly', 1);
            // Only set 'secure' if using HTTPS
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }
            session_start();
        }

        $url = $this->parseUrl();
        error_log('Parsed URL: ' . print_r($url, true));

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
                error_log('App: Found root controller: ' . $controllerClass);
            } else {
                // Check for subdirectory controller
                if (isset($originalUrl[1])) {
                    $potentialSubdir = $originalUrl[0];
                    $potentialSubController = ucfirst($originalUrl[1]);
                    $potentialSubControllerFile = 'app/controllers/' . $potentialSubdir . '/' . $potentialSubController . '.php';
                    error_log('App: Potential subdirectory controller file: ' . $potentialSubControllerFile);

                    if (file_exists($potentialSubControllerFile)) {
                        $controllerClass = $potentialSubController;
                        $controllerFile = $potentialSubControllerFile;
                        $paramsStartIndex = 2; // Parameters start after sub-controller
                        error_log('App: Found subdirectory controller: ' . $controllerClass . ' in ' . $potentialSubdir);
                    } else {
                        error_log('App: Controller not found: ' . $potentialSubControllerFile);
                        show_404(); // Controller not found
                    }
                } else {
                    error_log('App: Controller not found: ' . $potentialControllerFile);
                    show_404(); // Controller not found
                }
            }
        }
        
        require_once $controllerFile;
        $this->controller = new $controllerClass;
        error_log('App: Instantiated controller: ' . $controllerClass);

        // Determine method
        if (isset($originalUrl[$paramsStartIndex])) {
            error_log('App: Checking for method: ' . $originalUrl[$paramsStartIndex] . ' in controller ' . $controllerClass);
            if (method_exists($this->controller, $originalUrl[$paramsStartIndex])) {
                $methodName = $originalUrl[$paramsStartIndex];
                $paramsStartIndex++; // Parameters start after method
                error_log('App: Found method: ' . $methodName);
            } else {
                error_log('App: Method not found: ' . $originalUrl[$paramsStartIndex] . ' in controller ' . $controllerClass);
                show_404(); // Method not found
            }
        } else {
            // If no method is specified, default to 'index'
            if (method_exists($this->controller, 'index')) {
                $methodName = 'index';
                error_log('App: Defaulted to index method.');
            } else {
                error_log('App: Default method index not found in controller ' . $controllerClass);
                show_404(); // Default method 'index' not found
            }
        }
        $this->method = $methodName;
        error_log('App: Method to be called: ' . $this->method);

        // Extract parameters
        $this->params = $originalUrl ? array_slice($originalUrl, $paramsStartIndex) : [];
        error_log('App: Parameters: ' . print_r($this->params, true));

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return null;
    }
}
