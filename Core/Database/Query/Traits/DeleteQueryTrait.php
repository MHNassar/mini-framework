<?php

namespace Core\Database\Query;


use Core\Database\Query\SQLQuery\DeleteQuery;

trait DeleteQueryTrait
{

    public function delete($id)
    {
        $selectObj = $this->queryBuilder->select()
            ->where("id", "=", $id)
            ->execute();
        return $selectObj;
    }

    public function deleteWhere($key, $operation, $value)
    {
        $selectObj = $this->queryBuilder->select()
            ->where($key, $operation, $value)
            ->execute();
        return $selectObj;
    }


}