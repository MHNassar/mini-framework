<?php

namespace Core\Database\Query;



trait SelectQueryTrait
{

    public function all()
    {
        $select = $this->queryBuilder->select()->execute();
        return $select;
    }

    public function find($id)
    {
        $select = $this->queryBuilder->select()
            ->where("id", "=", $id)->execute();
        return $select;
    }

    public function findBy($key, $value)
    {
        $select = $this->queryBuilder->select()
            ->where($key, "=", $value)
            ->execute();
        return $select;
    }

    public function selectWhere($key, $operation, $value)
    {
        $select = $this->queryBuilder->select()
            ->where($key, $operation, $value)
            ->execute();
        return $select;

    }


}