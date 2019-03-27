<?php

use Core\Router;

Router::middleware("AuthMiddleware")->get("/", "IndexController@index");
Router::middleware("AuthMiddleware")->get("/index2/{id}", "IndexController@index2");

Router::run();