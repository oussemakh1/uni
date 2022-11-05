<?php 

namespace cgc\platform\controllers;

use cgc\platform\models\ClubMember;
use cgc\platform\database\Mysql;
use cgc\platform\libs\Pagination;
use cgc\platform\libs\Search;
use cgc\platform\libs\Message;
use cgc\platform\models\Department;

class ClubMemberController 
{
    private $mysql;
    private $ClubMember;
    private $pagination;
    private $search;
    private $message;
    private $department;

    public function __construct()
    {
        $this->message = new Message();
        $this->mysql = new Mysql();
        $this->ClubMember = new ClubMember();
        $this->pagination = new Pagination();
        $this->search = new Search();
        $this->department = new Department();

    }


  

    /** 
     * Get current logged in user members that are assigned to him (his stuff)
     * Only users who are not normal members can have assigned members
     * @param int $club_id
     * @param int $member_id
     * @param string $search_for
     */
    public function GetMyMembers(int $club_id, int $member_id, string $search_for=null) {

        // check if current logged in user not a normal memeber
        $validate = $this->ValidateRole($member_id);

        if($validate) {

            // get assigned memebrs for the current logged user
            $query = "SELECT * FROM club_members WHERE club_id = $club_id AND assigned_to = $member_id";

            // check if there is any search for any assigned member 

            if($search_for != null) {
            
                
                
                // use 'Search' lib to get requested records ('Search' lib already using 'Pagination' lib)

                $search = $this->search->search(
                                                $query,
                                                'club_members',
                                                $search_for,
                                                ['fullname','type','email','phone','speciality','gender'],
                                                ['club_id'],
                                                [$club_id],
                                                null,
                                                'id',
                                                "page_my_members"
                                            );
    
                return $search;
            }
    
            else {
    
                // count total of current logged user assigned members

                $total_members = $this->mysql->Count('club_members',['club_id','assigned_to'],[$club_id,$member_id]);
               
                // use 'Pagination( lib to get records
                $club_members= $this->pagination->Pagination($total_members['total'],'page_my_members',$query,null,'id','DESC');
                
                // return records page numbers 
                $pagination_links = $this->pagination::PaginationLinks($club_members['total_pages'],'page_my_members');
    
                // return records along side their pages links
                if($club_members != false) return [ 'data'  => $club_members['data'],'pagination_links' => $pagination_links];
    
                else return false;
            }
        }
        
    }


    /** 
     * Get club member by his ID && CLUB_ID
     * @param int $member_id
     * @param int $club_id
     */
    public function GetClubMember(int $member_id, int $club_id) {

        // Get club member by his id && club_id
        $query = "SELECT * FROM club_members WHERE id = $member_id AND club_id = $club_id";

        // fetch result from database
        $member = $this->mysql->RawFetch($query);

        // get club member departement
        $member_department = $this->department->find($member[0]['departement_id']);
        
        // if club member has department 
        if($member_department){

             // add department name to member fetched informations
             $member[0]['department_name'] = $member_department[0]['name'];
        
        }

        // if club members has no department 
        else $member[0]['department_name'] = 'Aucun';
      
        if($member !=false) return $member;

        else return false;
    }


    /** 
     * Update current logged in user profile
     * @param array $data 
     * @param int $member_id
     */
    public function UpdateProfile(array $data, int $member_id) {

        $update = $this->ClubMember->updateProfile($data, $member_id);

        if($update != false) return true;

        else return false;
    }


    /** 
     * Create new club member
     * @param array $data
     * @param int $club_id
     */
    public function CreateClubMember(array $data, int $club_id = null) {

        $create = $this->ClubMember->store($data,$club_id);

        if($create !== false) return true;

        else return false;
    }

    /** 
     * Get club members with the type 'bureau'
     * @param int $club_id
     */
    public function GetClubVp(int $club_id)
    {
        $vp = $this->mysql->select('club_members',null,['club_id','type'],[$club_id,'bureau'],null,null);

        if($vp) return $vp;
        
        else return $this->message->error('server_error');
    }


      /** 
     * Get club members with the type different then 'member'
     * @param int $club_id
     */
    public function GetLeaders(int $club_id)
    {
        // check if there is departements

        $isDepartements = $this->mysql->RawFetch("SELECT * FROM departments WHERE club_id = $club_id");
       
        if($isDepartements != false) {
            
            // get members with type not equal 'member' if there is  departments in db
            $query = "SELECT 
                            fullname,
                            cin,
                            type,
                            club_members.id as member_id,
                            departments.name as dep_name 
                            FROM club_members INNER JOIN departments ON club_members.departement_id = departments.id 
                            WHERE type !='member' AND club_members.club_id = $club_id";
      
        }


        else $query = "SELECT 
                             fullname,
                             cin,
                             type,
                             club_members.id as member_id
                             FROM club_members  WHERE  type !='member'  AND club_members.club_id = $club_id";
      

        $leaders = $this->mysql->RawFetch($query);
        

        if($leaders) return $leaders;
        else return $this->message->CustomError("Vous ne pouvez pas assigner quelqu'un à ce membre");

    }

    /** 
     * Get club members 
     * @param int $club_id
     * @param string $search_for
     * @param int $boss -> current logged in user id if he is not presdient 
     */
    public function GetClubMembers(int $club_id,$search_for=null,$boss =null) {
 
        
        if($search_for != null) {
            
            if($boss == null)
                 $query = "SELECT * FROM club_members WHERE club_id = $club_id";

            else $query = "SELECT * FROM club_members WHERE club_id = $club_id AND assigned_to = $boss";
            
            
            $search = $this->search->search($query,'club_members',$search_for,['fullname','type','email','phone','speciality','gender'],['club_id'],[$club_id],null,'id',"page_members");
            
            return $search;
        }

        else if($search_for == null) {
            

            if($boss == null) {

                $total_members = $this->mysql->Count('club_members',['club_id'],[$club_id]);
           
                $query = "SELECT * FROM club_members WHERE club_id = $club_id";
    
                $club_members= $this->pagination->Pagination($total_members['total'],'page_members',$query,null,'id','DESC');
                
                
                $pagination_links = $this->pagination::PaginationLinks($club_members['total_pages'],'page_members');
            }
            else {
                    $total_members = $this->mysql->Count('club_members',['club_id','assigned_to'],[$club_id,$boss]);
                
                    $query = "SELECT * FROM club_members WHERE club_id = $club_id AND assigned_to = $boss";

                    

                    $club_members= $this->pagination->Pagination($total_members['total'],'page_members',$query,null,'id','DESC');
                    
                
                    $pagination_links = $this->pagination::PaginationLinks($club_members['total_pages'],'page_members');
            }
            
           
            if($club_members != false) return [ 'data'  => $club_members['data'],'pagination_links' => $pagination_links];

            else return false;
        }
    }


   
    /** 
     * Update club member 
     * @param array $data
     * @param int $member_id
     * @param int $club_id
     */
    public function UpdateClubMember(array $data, int $member_id, int $club_id) {

        $update = $this->ClubMember->update($data, $member_id,$club_id);

        if($update != false) return true;

        else return false;
    }


    /** 
     * Delete club member
     * @param int $club_member -> club member id
     */
    public function DeleteClubMember(int $club_member) {
        
        $delete = $this->ClubMember->delete($club_member);

        if($delete != false) return true;
        
        else return false;
    }

    /** 
     * validate if current logged in user is not of they type  'member'
     * @param int $member_id
     */
    private function ValidateRole(int $member_id)
    {
        $query = "SELECT * FROM club_members WHERE type='bureau' OR  type = 'leader' OR type ='manager' AND id = $member_id";

        $member = $this->mysql->RawFetch($query);

        if($member) return true;
 
        else return false;
    }
}