<?php

namespace MoviesApp\Common;

use MoviesApp\App;

/**
 * Class Dispatcher
 * @package MoviesApp
 */
class Dispatcher
{
    /**
     * @var
     */
    private $controller;

    public function setController(Controller $controller) {

        $this->controller = $controller;
    }

    /**
     * Dispatches requests
     */
    public function dispatch() {

        $action = 'index';

        if (isset($argv) || isset($_SERVER['argv'])) {

            $action = 'query';

        } elseif (!empty($_REQUEST['action'])) {

            $action = $_REQUEST['action'];
        }

        if (method_exists($this->controller, $action)) {

            $this->controller->$action();

        } elseif (!isset($argv)) {

            http_response_code(404);
        }
    }
}
