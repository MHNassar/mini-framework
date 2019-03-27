<?php

namespace Core\Database\Query\SQLQuery;


class InsertQuery extends Query
{

    public function query($parameters)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $this->model->table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters))
        );
        $this->execute($sql, $parameters);
        return true;
    }

    public function execute($sql = "", $parameters = [])
    {
        try {
            $query = $this->connection->prepare($sql);
            $query->execute($parameters);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        return $query;
    }


}