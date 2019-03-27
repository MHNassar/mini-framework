<?php

namespace Core\Helpers;

class Request
{
    public function info()
    {
        var_dump($_REQUEST);
        exit();
        // json_decode($inputJSON, TRUE);
    }

    public function all()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        return $input;
    }


}