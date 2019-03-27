<?php

namespace App\Controllers;


use Core\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->view("index", ["name" => "mahmoud Nassar"]);
    }

    public function index2($id)
    {
        echo $id;
//        $this->view("index", ["name" => "mahmoud Nassar"]);
    }

}