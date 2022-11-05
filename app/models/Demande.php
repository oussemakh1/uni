<?php

namespace cgc\platform\models;

use cgc\platform\interfaces\Crud;
use cgc\platform\database\Mysql;

class Demande  
{

    private $columns;
    private $mysql;

    public function __construct()
    {

        $this->mysql = new Mysql();

        $this->columns = [   
            "event_name",
            "date",
            "start_at" ,
            "end_at",
            "datashow",
            "haut_parleur",
            "board",
            "other",
            "description",
            "presence_interval",
            "room" ,
            "type_event",
            "club_id",
            "place",
            "note",
            "status"
            
        ];


    
    }


    /**
     * Return list of demands
     * @param Mysql
     * @return array
     */
    public function index() 
    {
        // select all demands from database
        
        $presidents = $this->mysql->select('demande_event');
        
        // validate if any demands exists in the database
        
        if($presidents !== false) return $presidents;

        else return $this->message->error('une erreur survient, essayez plus tard');
    }



    /** 
     * Store new demande into the database
     * @param Mysql
     * @param data
     * @return boolean
     */
    public function store(array $data) 
    {

        
        // extract values from data

        $data = array_values($data);

        // store new demande into database 
       
        $store_demande = $this->mysql->insert("demande_event",$this->columns,$data);


        // validate if new demande inserted 

        if($store_demande == true) return true;

        else return $this->message->error('une erreur survient, essayez plus tard');
     }



     /** 
      * Find demande by id
      * @param id
      * @return array
      */
      public function find (int $id) 
      {

        // find demande by id

        $demande = $this->mysql->findById("demande_event", $id);

        // validate if exist in the database

        if($demande !== false) return $demande;

        else return $this->message->error('une erreur survient, essayez plus tard');

      }



      /** 
       * Update demande by id
       * @param id
       * @param data
       * @return boolean
       */
      public function update(array $data, int $id) 
      {
            // extract values from data

            $data = array_values($data);
        
            // update demande by id 
            
            $updateDemande = $this->mysql->updateById(
                "demande_event",
                $this->columns,
                $data,
                $id
            );

            // validate if updated

            if($updateDemande !== false) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
        }



      /** 
       * Delete Demande by id
       * @param id
       * @return boolean
      */
      public function delete (int $id)
      {
            // delete Demande by id

            $deleteDemande = $this->mysql->deleteById("demande_event",$id);

            // validate if Demande deleted 

            if($deleteDemande) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
        }




}
