<?php

namespace Core\Database\Query\QueryBuilder;


use Core\Database\Connection\IConnection;
use Core\Database\Models\Model;
use Core\Database\Query\SQLQuery\DeleteQuery;
use Core\Database\Query\SQLQuery\InsertQuery;
use Core\Database\Query\SQLQuery\SelectQuery;
use Core\Database\Query\SQLQuery\UpdateQuery;

class QueryFactory implements IQueryFactory
{
    protected $connection;
    protected $model;


    public function __construct(IConnection $connection, Model $model)
    {
        $this->connection = $connection;
        $this->model = $model;
    }

    public function select()
    {
        $selector = new SelectQuery($this->connection, $this->model);
        return $selector->query();
    }

    public function insert()
    {
        return new InsertQuery($this->connection, $this->model);
    }

    public function update()
    {
        return new UpdateQuery($this->connection, $this->model);
    }

    public function delete()
    {
        $selector = new DeleteQuery($this->connection, $this->model);
        return $selector->query();
    }


}