<?php 

namespace cgc\platform\controllers;

use cgc\platform\database\Mysql;
use cgc\platform\models\Club;
use cgc\platform\libs\Search;
use cgc\platform\libs\Pagination;

class ClubController 
{
    private $mysql;
    private $club;
    private $search;
    private $pagination;

    public function __construct()
    {
        $this->mysql = new Mysql();
        $this->club = new Club();
        $this->search = new Search();
        $this->pagination = new Pagination();
    }


    public function GetClubInfo(int $id)
    {
       $club = $this->club->find($id);

        // check if list demands is empty 

        if($club != false) return $club;

        else return false;
    }

    public function ListClubsAdmin ($president=null) { 


            if($president == true && sizeof($president) > 1){
                
                $clubs = [];


                $first_item  = "WHERE clubs.id = ".$president[0]['club_id'];
                
                array_push($clubs,$first_item);
                
                foreach(array_slice($president,1) as $c )
                {
                    $sentence = " OR clubs.id = ".$c['club_id']." ";
                
                    array_push($clubs,$sentence);
                    
                }
                
               $clubs = implode(" ",$clubs);
               

               $query ="SELECT clubs.name,presidents.club_id FROM clubs INNER JOIN presidents ON clubs.id = presidents.club_id WHERE clubs.id != $clubs";
               
               $clubs = $this->mysql->RawFetch($query);
            
            }
            
            else {

                // check if any clubs exist 
                $clubs_exits = $this->mysql->RawFetch("SELECT club_id FROM presidents");

                if($clubs_exits == false) { $query = "SELECT * FROM clubs"; }
            
                else {
            
                    $query ="SELECT clubs.id,clubs.name FROM clubs WHERE NOT EXISTS(SELECT club_id FROM presidents WHERE club_id = clubs.id)";
            
                }

               
                $clubs = $this->mysql->RawFetch($query);
                
            }

            
            if($clubs) return $clubs;

            else return false;
    }


    public function ListClubs ($search_for=null) { 

        // if there is search for club

        if($search_for != null) {
            
            // get all clubs with pagination using 'Search' Lib

            $query = "SELECT * FROM clubs";

            // search by club [name or type or objective ]
            $search = $this->search->search($query,'clubs',$search_for,['name','type','objective'],null,null,null,'id',"page_clubs");

            return $search;
        }

        else {

            // if there is no search 
            // get total clubs for pagination 

            $total_clubs= $this->mysql->Count('clubs',null,null);
           
            $query = "SELECT * FROM clubs";

            // return all clubs

            $clubs= $this->pagination->Pagination($total_clubs['total'],'page_clubs',$query,null,'id','DESC');
            
            // return pages numbers
            $pagination_links = $this->pagination::PaginationLinks($clubs['total_pages'],'page_clubs');

            // return clubs and the pages numbers 

            if($clubs != false) return [ 'data'  => $clubs['data'],'pagination_links' => $pagination_links];

            // there is no clubs in the database
            else return false;
        }
    }


    public function AddClub(array $data)  {

        // insert new club using  'Club' model

        $insertClub = $this->club->store($data);

        // if club inserted
        if($insertClub) return true;

        // error on insert
        else return false;
    }


    public function UpdateClub(array $data, int $id) {

        // update club using 'Club' model
        $updateClub = $this->club->update($data, $id);

        // if club updated
        if($updateClub) return true;
        
        // error on club update
        else return false;
    }


    public function DeleteClub(int $id) {
        
        // delete club using 'Club' model 
        $deleteClub = $this->club->delete($id);

        // if club deleted
        if($deleteClub) return true;

        // error on delete
        else return false;
    }


   
    
}