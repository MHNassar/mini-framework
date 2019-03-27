<?php

namespace Core\Database\Query\SQLQuery;


class SelectQuery extends Query
{
    public function query($data = null)
    {
        $tableName = $this->model->table;
        $query = "select * from $tableName";
        $this->query = $query;
        return $this;
    }
}