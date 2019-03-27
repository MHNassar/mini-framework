<?php

namespace Core\Database\Connection;

interface IConnection
{

    public static function getInstance();

    public function getConnection();

}