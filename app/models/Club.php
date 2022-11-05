<?php

namespace cgc\platform\models;

use cgc\platform\interfaces\Crud;
use cgc\platform\database\Mysql;
use cgc\platform\traits\UploadImageTrait;
use cgc\platform\libs\Message;


class Club 
{
    use UploadImageTrait;

    private $columns;
    private $mysql;
    private $message;


    public function __construct()
    {
        $this->columns = [   
            "name",
            "description",
            "logo",
            "created_at",
            "type",
            "objective"
        ];

        $this->mysql = new Mysql();
        $this->message = new Message();
    }


    /**
     * Return list of clubs
     * @param Mysql
     * @return array
     */
    public function index() 
    {
        // select all clubs from database
        $clubs = $this->mysql->select("clubs");
        
        // validate if any clubs exists in the database
        
        return $clubs;

   
    }



    /** 
     * Store new club into the database
     * @param Mysql
     * @param data
     * @return boolean
     */
    public function store(array $data) 
    {

          // check if email or phone or cin exist

          $name_exist = $this->mysql->Count('clubs',['name'],[$data['name']]);
   
          if($name_exist['total'] > 0) {
               $this->message->error("nom club a déjà été utilisé");
               return false;
          }

        $logo = $data['logo'];

        $logo =  $this->uploadImage($logo);
       
        if($logo !== false) {
            $data["logo"] = $logo;
            // extract values from data

            $data = array_values($data);
        
            // insert club
            
            $insertClub = $this->mysql->insert(
                "clubs",
                $this->columns,
                $data
                
            );

            // validate if inserted

            if($insertClub) return true;

            else return false;
        }

        else return $this->message->error('une erreur survient, essayez plus tard');
      
     }



     /** 
      * Find club by id
      * @param id
      * @return array
      */
      public function find (int $id) 
      {

        // find club by id

        $club = $this->mysql->findById("clubs", $id);

        // validate if exist in the database

        if(sizeof($club) > 0) return $club;

        else return $this->message->error('une erreur survient, essayez plus tard');

      }



      /** 
       * Update club by id
       * @param id
       * @param data
       * @return boolean
       */
      public function update(array $data, int $id) 
      {
            // get current club 

            $current_club = $this->find($id);

           // check if email or phone or cin exist

           $name_exist = $this->mysql->Count('clubs',['name'],[$data['name']]);
   
           if($name_exist['total'] > 0 && $data['name'] != $current_club[0]['name']) {
                $this->message->error("nom club a déjà été utilisé");
                return false;
           }
   
   
         

        $logo = $data['logo'];
     
        if(is_array($logo) == true) {
                $logo =  $this->uploadImage($logo);
                $data["logo"] = $logo;
        }
            
            $data = array_values($data);
        
            // update club by id 
            
            $updateClub = $this->mysql->updateById(
                "clubs",
                $this->columns,
                $data,
                $id
            );

            // validate if updated

            if($updateClub) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
       
      }


      public function UpdateClubLogo($logo,$club_id)
      {
        
        
        $query ="SELECT logo FROM clubs WHERE id = $club_id ";
        $club = $this->mysql->RawFetch($query);
        
      
        if(is_array($logo) == true  && !empty($logo['name'][0])) {
                $logo =  $this->uploadImage($logo);
        }
        else $logo = $club[0]['logo'];
            
        $update = $this->mysql->updateById('clubs',['logo'],[$logo],$club_id);
       
        if($update) return true;

        else return false;
      }


      /** 
       * Delete club by id
       * @param id
       * @return boolean
      */
      public function delete (int $id)
      {
            // delete club by id

            $deleteClub = $this->mysql->deleteById("clubs",$id);

            // validate if club deleted 

            if($deleteClub) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
        }



 


      







}
