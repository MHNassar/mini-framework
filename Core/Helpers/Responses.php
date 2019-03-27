<?php

class Responses
{
    public static function bad_request()
    {
        return JsonOutput::convert(["error" => "Bad Request"], 400);
    }
}