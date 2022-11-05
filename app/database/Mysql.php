<?php

namespace cgc\platform\database;

use cgc\platform\Interfaces\MysqlAdapter;
use cgc\platform\libs\ORM; 

use PDO;
use PDOException;

class Mysql  
{
    private $orm;

    public function __construct()
    {
        $this->orm = new ORM();
    }

    public function connect()
    {
        $this->orm->connect();
    }

    public function disconnect()
    {
        $this->orm->disconnect();
    }

    public function insert($tableName, $columns, $values) 
    {
       return $this->orm->insert($tableName, $columns, $values);
    }


    public function updateById($tableName, $columns, $values,$id)
    {

           return  $this->orm->updateById($tableName, $columns, $values,$id);

    }

 


    public function select($tableName, $columns =null,$condition=null, $value=null,$custom=null,$orderby=null,$filter=null)
    {
        return  $this->orm->select($tableName, $columns,$condition, $value,$custom,$orderby,$filter);


    }


    public function findById($tableName,$id) 
    {

        return $this->orm->findById($tableName,$id);


    }


    public function deleteById($tableName, $id,$custom=null)
    {

     return $this->orm->deleteById($tableName, $id,$custom);

    }



    public function RawFetch($query)
    {
        
          return $this->orm->RawFetch($query);
    }

    public function Count($tableName, $column = null, $value = null,$custom=null) 
    {   
       
        $result = $this->orm->Count($tableName, $column, $value, $custom);

        return $result;
        

    }


    public function Query($query)
    {
        return $this->orm->Query($query);
    }

}