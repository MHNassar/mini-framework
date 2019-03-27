<?php

namespace Core\Database\Query;


trait UpdateQueryTrait
{

    public function update($id, $data)
    {
        $object = $this->queryBuilder->update()
            ->query($data)->where('id', '=', $id)
            ->execute($data);
        if ($object) {
            return "Successfully Added";
        }
    }


}