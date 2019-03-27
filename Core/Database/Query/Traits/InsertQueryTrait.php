<?php

namespace Core\Database\Query;


use Core\Database\Query\SQLQuery\InsertQuery;

trait InsertQueryTrait
{

    public function save($data)
    {
        $object = $this->queryBuilder->insert()
            ->query($data);
        if ($object) {
            return "Successfully Added";
        }
    }


}