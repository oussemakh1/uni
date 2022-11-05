<?php 

namespace cgc\platform\controllers;

use cgc\platform\database\Mysql;
use cgc\platform\libs\Pagination;
use cgc\platform\libs\Search;
use cgc\platform\models\Demande;

class DemandController 
{
    private $mysql;
    private $pagination;
    private $search;
    private $demande;

    public function __construct()
    {
        $this->mysql = new Mysql();
        $this->pagination = new Pagination();
        $this->search = new Search();
        $this->demande = new Demande();
    }


    /** 
     * Return list of accepted demands
     * @param string $search_for
     */
    public function ListOfAllAccepted (string $search_for=null) {

         // get demands with status = accepted along side with club_logo 
         $query = "SELECT *,
         clubs.logo 
         FROM demande_event 
         INNER JOIN clubs on demande_event.club_id = clubs.id  
         WHERE demande_event.status = 'accepted' ";

        // if there is a search for certain demande
        if($search_for != null) {
            
           

            // use 'Search' lib to get records ('Search' lib already use 'Pagination' lib)
            $search = $this->search->search(
                                            $query,
                                            'demande_event',
                                            $search_for,
                                            ['event_name','type_event','date','name'],
                                            ["status"],
                                            ["accepted"],
                                            null,
                                            'date',
                                            "accepted_demands"
                                        );

            return $search;
        } 

        else {
            
            // count total of accepted demands
            $total_accepted_demands = $this->mysql->Count('demande_event',['status'],['accepted']);
                       
            // use 'Pagination' lib to get records     
            $Demands = $this->pagination->Pagination($total_accepted_demands['total'],'page_timeline',$query,'demande_event.date',null,null);
    
            // return pages numbers
            $pagination_links = $this->pagination::PaginationLinks($Demands['total_pages'],'page_timeline');
    
            // return records along side with pages links and numbers
            if($Demands != false) return [ 'data'  => $Demands['data'],'pagination_links' => $pagination_links];

            else return false;
        }


      
    }

    /** 
     * Return list of pending demands
     * @param string $search_for
     */

    public function ListOfPending (string $search_for=null) 
    {

        // if there is a search for certain pending demande
        if($search_for != null) {
            
            // get pending demands
            $query = "SELECT 
                            demande_event.id,
                            event_name,
                            type_event,clubs.name as name,
                            date,
                            status
                            
                            FROM demande_event INNER JOIN clubs on demande_event.club_id = clubs.id 
                            WHERE status = 'pending' ";
            
            // use 'Search' lib to get records ('Search' lib already use 'Pagination' lib)
            $search = $this->search->search($query,'demande_event',$search_for,['event_name','type_event','date','name'],["status"],["pending"],null,'date',"pending_demands");

            return $search;
        }

        else {

            // count total of pending demands
            $total_pending_demands = $this->mysql->Count('demande_event',['status'],['pending']);

            // get pending demands 
            $query = "SELECT id, event_name, type_event, date, status FROM demande_event WHERE status = 'pending' ";
            
            $Demands = $this->pagination->Pagination($total_pending_demands['total'],'page_pending',$query,null,'date','desc');
            

            $pagination_links = $this->pagination::PaginationLinks($Demands['total_pages'],'page_pending');
            
    
    
            // check if list demands is empty 
    
             if($Demands != false) return [ 'data'  => $Demands['data'],'pagination_links' => $pagination_links];
    
            else return false;

        }

    }


    /** 
     * Find demande
     * @param int $id
     */

    public function Edit (int $id)
    {
        $query = "SELECT *  FROM demande_event WHERE id = '$id' ";

        $Demande = $this->mysql->RawFetch($query);

        // check if list demands is empty 

        if($Demande != false) return $Demande;

        else return false;
    }


    /** 
     * Approve or refuse demande
     * @param array $data
     * @param int $id
     */
    public function HandleDemand(array $data, int $id)
    {
       
        // extract columns 
        $columns = array_keys($data);

        // extract values
        $values = array_values($data);
        
        
        $tablename= 'demande_event';

        $store = $this->mysql->updateById($tablename, $columns,$values,$id);
        
        return $store;
    }


    /** 
     * Club create  demande
     * @param array $data
     */
    public function CreateDemande(array $data) {

        $create = $this->demande->store($data);

        if($create !=false) return true;

        else return false;
    }


     /** 
     * get current logged in club demands 
     * @param int $club_id
     * @param string $search_for
     */
    public function MyClubDemands(int $club_id, string $search_for=null) {

        // get current club demands
        $query = "SELECT * FROM demande_event WHERE club_id = $club_id";

        if($search_for != null) {
            
            $search = $this->search->search($query,'demande_event',$search_for,['event_name','type_event','date','status'],['club_id'],[$club_id],null,'date',"all_demands");

            return $search;
        }

        else {

            // count total demands 
            $total_demands = $this->mysql->Count('demande_event',['club_id'],[$club_id]);

            // use 'Pagination' lib to get records
            $club_demands = $this->pagination->Pagination($total_demands['total'],'page_demands',$query,null,'date','DESC');
        
            $pagination_links = $this->pagination::PaginationLinks($club_demands['total_pages'],'page_demands');

            if($club_demands != false) return [ 'data'  => $club_demands['data'],'pagination_links' => $pagination_links];

            else return false;
        }
    }

}