<?php 
namespace cgc\platform\controllers;

use cgc\platform\database\Mysql;
use cgc\platform\libs\Pagination;
use cgc\platform\libs\Search;
use cgc\platform\models\Department;
use cgc\platform\libs\Message;

class DepartmentController 
{
    private $mysql;
    private $pagination;
    private $search;
    private $departement;
    private $message;

    public function __construct()
    {
        $this->mysql = new Mysql();
        $this->pagination = new Pagination();
        $this->search = new Search();
        $this->departement = new Department();
        $this->message = new Message();
    }

    /** 
     * Retun list of club departments
     * @param int $club_id
     * @param string $search_for
     */
    public function ClubDepartements(int $club_id, string $search_for=null)
    {

        // get departments by club id
        $query = "SELECT 
                        departments.name as dep_name, 
                        departments.id,clubs.name as club_name 
                        
                        FROM departments INNER JOIN clubs ON departments.club_id = clubs.id
                        WHERE clubs.id = $club_id";

        if($search_for != null) {
        
            // search for departments 
            $search = $this->search->search(
                                            $query,
                                            'departments',
                                            $search_for,
                                            ['departments.name'],
                                            ['club_id'],
                                            [$club_id],
                                            'id',
                                            null,
                                            'page_departement'
                                        );
            
            if($search) return $search;

            else return $this->message->error('server_error');

        }else {

            // count total of departments

            $total_departements = $this->mysql->Count('departments',['club_id'],[$club_id]);

            // fetch records using 'Pagination' libs 

            $departements = $this->pagination->Pagination($total_departements,'page_departement',$query,'departments.id');
          
            // return pages numbers
            $pagination_links = $this->pagination->PaginationLinks($departements['total_pages'],'page_departement');

            // return records along side the pages numbers
            if($departements != false) return [ 'data'  => $departements['data'],'pagination_links' => $pagination_links];

            else return $this->message->error('server_error');


        }
    }



    /** 
     * Create new department
     * @param array $data
     */
    public function CreateDepartement(array $data)
    {
        $store = $this->departement->store($data);

        if($store) return true;
        
        else return false;
    }

    /** 
     * Find department by id and club_id
     * @param int $club_id
     * @param int $id
     */
    public function FindDepartement(int $club_id, int $id)
    {
        // check if department exists in db

        $Validate = $this->isAuthorized($club_id, $id);

        if($Validate == true)
        {
            $find = $this->departement->find($id);
            
            if($find) return $find;
           
            else return $this->message->error('server_error');
        }

        else return $this->message->error('server_error');
    }

    /** 
     * Update department by club_id and id
     * @param int $club_ids
     * @param array $data
     * @param int $id
     */
    public function UpdateDepartement(int $club_id, array $data,int $id)
    {

        // check if department exists
        $Validate = $this->isAuthorized($club_id, $id);
        
        if($Validate == true) {
            
            $update = $this->departement->update(
                $data,
                $id
            );

            if($update) return true;
        
            else return $this->message->error('server_error');
        }

        else return $this->message->error('server_error');

    }

    /** 
     * Delete department by club_id and id
     * @param int $club_id
     * @param int $id
     */
    public function DeleteDepartement(int $club_id, int $id)
    {
        // check if department exists in db
        $Validate = $this->isAuthorized($club_id, $id);

        if($Validate == true) 
        {
            $delete = $this->departement->delete($id);

            if($delete) return true;
            
            else return false;
        }

        else return $this->message->error('server_error');

    }


    /** 
     * Validate if requsted department exists in db
     * @param int $club_id
     * @param int $id
     */
    private function isAuthorized(int $club_id, int $id)
    {
        $query = "SELECT * FROM departments WHERE club_id = $club_id AND id = $id";
 
        $Validate = $this->mysql->RawFetch($query);
 
        if($Validate) return true;
 
        else return false; 
    }

}