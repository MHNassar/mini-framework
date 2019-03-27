<?php

namespace Core\Database\Query\SQLQuery;

use Core\Database\Models\Model;
use PDO, PDOException;

use Core\Database\Connection\IConnection;

abstract class Query
{
    protected $connection;
    protected $model;
    protected $query;

    public function __construct(IConnection $connection, Model $model)
    {
        $db_connection = $connection::getInstance();
        $this->connection = $db_connection->getConnection();
        $this->model = $model;
    }

    abstract public function query($data);

    public function where($key, $operation = "=", $value)
    {
        $this->query .= " where $key $operation '$value'";
        return $this;
    }

    public function orderBy($kay = "id", $type = "ASC")
    {
        $this->query .= " ORDER BY `$kay` $type ";
        return $this;
    }

    public function execute()
    {
        try {
            $query = $this->connection->prepare($this->query);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

}