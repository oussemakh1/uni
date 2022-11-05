<?php 

namespace cgc\platform\controllers;

use cgc\platform\models\President;
use cgc\platform\models\Demande;
use cgc\platform\models\ClubMember;
use cgc\platform\database\Mysql;
use cgc\platform\libs\Pagination;
use cgc\platform\libs\Search;
use cgc\platform\libs\Message;
use cgc\platform\models\Department;
use cgc\platform\models\Club;

class PresidentController 
{
    private $mysql;
    private $president;
    private $demande;
    private $ClubMember;
    private $pagination;
    private $search;
    private $message;
    private $department;
    private $club;

    public function __construct()
    {
        $this->message = new Message();
        $this->president = new President();
        $this->mysql = new Mysql();
        $this->demande = new Demande();
        $this->ClubMember = new ClubMember();
        $this->pagination = new Pagination();
        $this->search = new Search();
        $this->department = new Department();
        $this->club = new Club();

    }


    /** 
     * Club owner update profile
     * @param array $data
     * @param string $tablename 
     * @param int $id -> president_id
     * @param int club_id
     */
    public function profile(array $data, string $tablename, int $id,$club_id) {

        
        $columns = [];

        $values = [];

        foreach($data as $key => $value) {

            // execlude photo from data 
            if($key != 'photo') {
                
                // push data keys into columns array (extract field names)
                array_push($columns,$key);
                
                // push data values into values
                array_push($values,$value);
             }
        }
      
       
        // update club logo 
        $update_club = $this->club->UpdateClubLogo($data['photo'],$club_id);
        
        // update president 
        $update_profile = $this->mysql->updateById($tablename,$columns,$values,$id);
      
        if($update_profile && $update_club) {

            // set the new updates for the session (Auth guard well log out the user due to the changes if the session is not updated)
            $_SESSION['log_data'] = $this->GetPresident($data['cin']);

            return true;
        }
        else return false;

    }

    /** 
     * Return the lis of all presidents
     */
    public function ListPresidents () {

        $clubs = $this->president->index();
        return $clubs;
    }



    /** 
     * Create new president
     * @param array $data
     */
    public function AddPresident(array $data)  {

        $insertPresident = $this->president->store($data);

        if($insertPresident) return true;

        else return $this->message->error('server_error');
    }


    /** 
     * Find president by cin
     */
    public function GetPresident(int $cin)  {

        $President = $this->president->find($cin);

        if($President !== false) return $President;

        else return false;
    }


    /** 
     * Find president by club id
     * @param int $id -> club_id
     */
    public function GetPresidentByClubId (int $id) 
    {
        $president = $this->mysql->select(
                'presidents',
                ['fullname','id','email','phone','cin'],
                ['club_id'],
                [$id]
            );

        if($president) return $president;

        else return $president;
    }


    public function UpdatePresident(array $data, int $id) {

        $updatePresident = $this->president->update($data, $id);

        if($updatePresident) return true;
       
        else return false;
    }


    public function DeletePresident(int $id) {
        
        $deletePresident = $this->president->delete($id);

        if($deletePresident) return true;

        else return false;
    }






}