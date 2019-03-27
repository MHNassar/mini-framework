<?php

namespace Core\Database\Query\QueryBuilder;


interface IQueryFactory
{

    public function select();

    public function delete();

    public function insert();

    public function update();
}