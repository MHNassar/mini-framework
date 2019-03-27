<?php

namespace Core\Database\Query\SQLQuery;


class DeleteQuery extends Query
{
    public function query($data = null)
    {
        $tableName = $this->model->table;
        $query = "delete  from $tableName";
        $this->query = $query;
        return $this;

    }
}