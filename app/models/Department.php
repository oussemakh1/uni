<?php

namespace cgc\platform\models;

use cgc\platform\interfaces\Crud;
use cgc\platform\database\Mysql;
use cgc\platform\libs\Message;

class Department 
{

    private $columns;
    private $mysql;
    private $message;

    public function __construct()
    {

        $this->mysql = new Mysql();
        $this->message = new Message();

        $this->columns = [   
            "name",
            "club_id",
            
        ];


    
    }




    public function index()
    {
        $departements = $this->mysql->select('departments');
        if($departements) return true;
        else return $this->message->error('server_error');
    }

    public function store (array $data) 
    {
      
        $store = $this->mysql->insert('departments',$this->columns,array_values($data));

        if($store) return true;
        else return $this->message->error('server_error');
    }

    public function find($id)
    {
        $find = $this->mysql->findById('departments',$id);
        
        if($find) return $find;
        else return $this->message->error('server_error');
    }

    public function update(array $data,int $id)
    {
        $update = $this->mysql->updateById('departments',$this->columns,array_values($data),$id);
       
        if($update) return true;
        else return $this->message->error('server_error');
    }


    public function delete($id)
    {
        $delete = $this->mysql->deleteById('departments',$id);
        if($delete) return true;
        else return $this->message->error('server_error');
    }

}