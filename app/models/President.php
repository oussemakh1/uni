<?php

namespace cgc\platform\models;

use cgc\platform\interfaces\Crud;
use cgc\platform\database\Mysql;
use cgc\platform\libs\Message;

class President 
{

    private $columns;
    private $columnsWithoutId;
    private $mysql;
    private $message;

    public function __construct()
    {

        $this->mysql = new Mysql();
        $this->message = new Message();

        $this->columns = [   
            "fullname",
            "email",
            "cin",
            "mandate",
            "club_id",
            "password",
            "phone"
            
        ];


    
    }


    /**
     * Return list of presidents
     * @param Mysql
     * @return array
     */
    public function index() 
    {
        // select all presidents from database
        
        $presidents = $this->mysql->select('presidents');
        
        // validate if any presidents exists in the database
        
        if($presidents !== false) return $presidents;

        else return $this->message->error('une erreur survient, essayez plus tard');
    }



    /** 
     * Store new president into the database
     * @param Mysql
     * @param data
     * @return boolean
     */
    public function store(array $data) 
    {

     
        
        // extract values from data

        $data = array_values($data);

        // store new president into database 
   
        $store_president = $this->mysql->insert("presidents",$this->columns,$data);


        // validate if new club inserted 

        if($store_president == true) return true;

        else return $this->message->error('une erreur survient, essayez plus tard');
     }



     /** 
      * Find president by cin
      * @param int $cin
      * @return array
      */
      public function find (int $cin) 
      {

        // find president by id
        $query = "SELECT * FROM presidents WHERE cin = $cin";
        
        $president = $this->mysql->RawFetch($query);

        // validate if exist in the database

        if($president !== false) return $president;

        else return $this->message->error('une erreur survient, essayez plus tard');

      }



      /** 
       * Update president by id
       * @param id
       * @param data
       * @return boolean
       */
      public function update(array $data, int $id,int $club_id=null) 
      {

            // extract values from data

           
        
            // update president by id 
            
            $data = array_values($data);
            $updatePresident = $this->mysql->updateById(
                "presidents",
                $this->columns,
                $data,
                $id
            );

            // validate if updated

            if($updatePresident !== false) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
        }



      /** 
       * Delete president by id
       * @param id
       * @return boolean
      */
      public function delete (int $id)
      {
            // delete president by id

            $deletePresident = $this->mysql->deleteById("presidents",$id);

            // validate if president deleted 

            if($deletePresident) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
        }



      /** 
       * Extract only values from data array's
       * @param array
       * @return array
       */
      private function extractValues(array $data)
      {

        return $data = array_values($data);

      }

}
