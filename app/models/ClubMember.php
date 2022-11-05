<?php

namespace cgc\platform\models;

use cgc\platform\interfaces\Crud;
use cgc\platform\database\Mysql;
use cgc\platform\libs\Message;
use cgc\platform\traits\UploadImageTrait;

class ClubMember 
{

    use UploadImageTrait;

    private $columns;
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
            "gender",
            "club_id",
            "grade",
            "speciality",
            "phone",
            "type",
            "departement_id",
            "assigned_to",
            "password",
            "photo"
        ];


    }

    public function index()
    {

    }
    /**
     * Return list of Club Members
     * @param Mysql
     * @return array
     */
    public function indexClub(int $club_id) 
    {
        // append id for the select
        array_push($this->columns,"id");

        // select all club members from database
        $clubMembers = $this->mysql->select(
            "club_members",
            $this->columns,
            ["club_id"],
            [$club_id]
        );
        
        // validate if any club members exists in the database
        
        if($clubMembers != false) return $clubMembers;

       // else return $this->message->error('une erreur survient, essayez plus tard');
    }



    /** 
     * Store new club member into the database
     * @param Mysql
     * @param data
     * @return boolean
     */
    public function store(array $data,$club_id=null) 
    {

           // check if email or phone or cin exist

           $email_exist = $this->mysql->Count('club_members',['email','club_id'],[$data['email'],$club_id]);
           $phone_exist = $this->mysql->Count('club_members',['phone','club_id'],[$data['phone'],$club_id]);
           $cin_exist = $this->mysql->Count('club_members',['cin','club_id'],[$data['cin'],$club_id]);
        
        
           if($email_exist['total'] > 0) {
                $this->message->error("L'email a déjà été utilisé");
                return false;
           }
   
   
           if($phone_exist['total'] > 0) {
               $this->message->error("telephone a déjà été utilisé");
               return false;
           }
   
           if($cin_exist['total'] > 0) {
               $this->message->error("cin a déjà été utilisé");
               return false;
           }

           // upload image 
           $photo = $data['photo'];

           $photo =  $this->uploadImage($photo);

           if($photo == false) $data['photo'] = 'default.jpg';
           else $data['photo'] = $photo;
        // extract values from data
        $data['password'] = password_hash($data['password'],PASSWORD_BCRYPT);
        $data = array_values($data);

        // store new club member into database 
       
        $store_clubMember = $this->mysql->insert(
            "club_members",
            $this->columns,
            $data
        );


        // validate if new club member inserted 

        if($store_clubMember == true) return true;

        else return $this->message->error('une erreur survient, essayez plus tard');
     }



     /** 
      * Find club member by id
      * @param id
      * @return array
      */
      public function find (int $id) 
      {

        // find club member by id

        $clubMember = $this->mysql->findById("club_members", $id);

        // validate if exist in the database

        if($clubMember) return $clubMember;

        else return $this->message->error('une erreur survient, essayez plus tard');

      }



      /** 
       * Update club member by id
       * @param id
       * @param data
       * @return boolean
       */
      public function update(array $data, int $id) 
      {
            // get member 
            $member = $this->find($id);
         
            // check if email or phone or cin exist
         
            $email_exist = $this->mysql->Count('club_members',['email','club_id'],[$data['email'],$data['club_id']]);
            $phone_exist = $this->mysql->Count('club_members',['phone','club_id'],[$data['phone'],$data['club_id']]);
            $cin_exist = $this->mysql->Count('club_members',['cin','club_id'],[$data['cin'],$data['club_id']]);
   
           if($email_exist['total'] > 0 && $data['email'] != $member['0']['email']) {
                $this->message->error("L'email a déjà été utilisé");
                return false;
           }
   
   
           if($phone_exist['total'] > 0 && $data['phone'] != $member['0']['phone'] ) {
               $this->message->error("telephone a déjà été utilisé");
               return false;
           }
   
           if($cin_exist['total'] > 0 && $data['cin'] != $member['0']['cin']) {
               $this->message->error("cin a déjà été utilisé");
               return false;
           }

           //upload photo
           $photo = $data['photo'];
     
           if(is_array($photo) == true) {
                   $photo =  $this->uploadImage($photo);
                   $data["photo"] = $photo;

                   // delete old photo 
                   $photo = $member[0]['photo'];
                   $path  = 'app/public'.$photo;
                   unlink($path);
           }
           
            // extract values from data
           
            $data = array_values($data);
         
            // update club member by id 
           
            $updateClubMember = $this->mysql->updateById(
                "club_members",
                $this->columns,
                $data,
                $id
            );

            // validate if updated

            if($updateClubMember) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
        }


        public function updateProfile(array $data,int $id) 
        {
              // get member 
              $member = $this->find($id);
           
             
              // check if email or phone or cin exist
           
             $email_exist = $this->mysql->Count('club_members',['email'],[$data['email']]);
             $phone_exist = $this->mysql->Count('club_members',['phone'],[$data['phone']]);
             $cin_exist = $this->mysql->Count('club_members',['cin'],[$data['cin']]);
     
             if($email_exist['total'] > 0 && $data['email'] != $member['0']['email']) {
                  $this->message->error("L'email a déjà été utilisé");
                  return false;
             }
     
     
             if($phone_exist['total'] > 0 && $data['phone'] != $member['0']['phone'] ) {
                 $this->message->error("telephone a déjà été utilisé");
                 return false;
             }
     
             if($cin_exist['total'] > 0 && $data['cin'] != $member['0']['cin']) {
                 $this->message->error("cin a déjà été utilisé");
                 return false;
             }
  
             //upload photo
             $photo = $data['photo'];
           
             if(is_array($photo) == true && !empty($photo['name'])) {
                     $photo =  $this->uploadImage($photo);
                     $data["photo"] = $photo;
             }
             else $data["photo"] = $member[0]['photo'];
            
              // extract values from data
  
              $data = array_values($data);
             
              // update club member by id 
              $columns = [
                    "email",
                    "cin",
                    "grade",
                    "speciality",
                    "phone",
                    "password",
                    "photo"
                ];
            


              $updateClubMember = $this->mysql->updateById(
                  "club_members",
                  $columns,
                  $data,
                  $id
              );
  
              // validate if updated
  
              if($updateClubMember){ 
                    $_SESSION['log_data'] = $this->find($id);
                    return true;
            }
  
              else return $this->message->error('une erreur survient, essayez plus tard');
          }
  


      /** 
       * Delete club member by id
       * @param id
       * @return boolean
      */
      public function delete (int $id)
      {
            // delete club member by id

            $deleteClubMember = $this->mysql->deleteById("club_members",$id);

            // validate if club member deleted 

            if($deleteClubMember) return true;

            else return $this->message->error('une erreur survient, essayez plus tard');
        }



}
