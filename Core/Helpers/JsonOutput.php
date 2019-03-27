<?php

class JsonOutput
{
    public static function convert($array, $response_code = 200)
    {
        header('Content-Type: App/json');
        http_response_code($response_code);
        return json_encode($array);

    }

}