<?php 

namespace cgc\platform\libs;
use cgc\platform\database\Mysql;

class Notification 
{

    private $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql();
    }



    public function create($tablename, $data)
    {
        $columns = array_keys($data);

        $values = array_values($data);

        $create = $this->mysql->insert($tablename, $columns, $values);

        if($create) return true;

        else return false;
    }


    public function Get($tablename,$condition,$values,$custom=null)
    {
        if($custom !=null) $get = $this->mysql->select($tablename,null,$condition,$values,$custom);

       else $get = $this->mysql->select($tablename,null,$condition,$values);

        if($get) return $get;

        else return false;
    }


}