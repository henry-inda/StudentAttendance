<?php
class Controller {
    public function model($model) {
        require_once 'app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        if (file_exists(ROOT . '/views/' . $view . '.php')) {
            require_once ROOT . '/views/' . $view . '.php';
        } else {
            die('View does not exist.');
        }
    }
}
