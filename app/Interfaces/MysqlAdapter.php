<?php
namespace cgc\platform\Interfaces;


interface MysqlAdapter 
{
    function connect();

    function disconnect();

    function insert ($tableName, $columns, $values) ;

    function updateById($tableName, $columns, $values,$id);

    function select($tableName, $columns);

    function deleteById($tableName, $id);

}