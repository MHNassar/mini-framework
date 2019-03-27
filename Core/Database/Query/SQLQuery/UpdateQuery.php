<?php

namespace Core\Database\Query\SQLQuery;


class UpdateQuery extends Query
{

    public function query($parameters)
    {
        $sql = sprintf(
            'update `%s` set %s',
            $this->model->table,
            $this->prepareParameters($parameters)
        );
        $this->query = $sql;
        return $this;

    }

    public function execute($parameters = [])
    {
        try {
            $query = $this->connection->prepare($this->query);
            $query->execute($parameters);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        return $query;
    }

    protected function prepareParameters($parameters)
    {
        return implode(', ', array_map(
            function ($k) {
                return $k . '=:' . $k;
            },
            array_keys($parameters)));

    }


}