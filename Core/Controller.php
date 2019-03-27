<?php

namespace Core;

use Core\Helpers\Request;

class Controller
{
    public $model;
    protected $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    protected function view($name, $args = [])
    {
        $view = new View();
        return $view->render($name, $args);
    }

    protected function json(Array $data, $type = 'application/json')
    {
        header('Content-Type:' . $type);
        echo json_encode($data);
        die();
    }
}