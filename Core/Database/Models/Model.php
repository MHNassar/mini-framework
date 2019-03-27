<?php

namespace Core\Database\Models;

use Core\Database\Connection\Connection;
use Core\Database\Query\DeleteQueryTrait;
use Core\Database\Query\InsertQueryTrait;
use Core\Database\Query\QueryBuilder\QueryFactory;
use Core\Database\Query\SelectQueryTrait;
use Core\Database\Query\UpdateQueryTrait;

class Model
{
    use SelectQueryTrait, InsertQueryTrait, DeleteQueryTrait, UpdateQueryTrait;

    public $qu;
    public $table = "recipe";
    public $connection;
    public $queryBuilder;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
        $this->queryBuilder = new QueryFactory($this->connection, $this);
    }


}