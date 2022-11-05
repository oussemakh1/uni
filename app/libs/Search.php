<?php 

namespace cgc\platform\libs;

use cgc\platform\database\Mysql;
use cgc\platform\libs\Pagination;

class Search 
{

    private $mysql;
    private $pagination;

    public function __construct()
    {
        $this->mysql = new Mysql();
        $this->pagination = new Pagination();
    }

    /** 
     * @param query  = 'native sql command'
     * @param tablename = 'sql table name'
     * @param search_for = 'searched item as string'
     * @param columns = 'table columns to search in'
     * @param condition = 'any conditions in the select process'
     * @param condition_value = 'condition value'
     * @param group_by = 'sql resutl group by'
     * @param orderBy = 'sql result order by'
     * @param page_name = 'uri name for pagination'
     * @param custom = 'any custom sql'
     * 
     */
    public function search($query, $tablename,$search_for,$columns ,$condition=null,$condition_value=null,$groupBy=null,$orderBy =null,$page_name,$custom =null)
    {
        
        if($condition !=null && $condition_value != null)  {
            
            $conditions = [];

            foreach($condition as $c) {
               
                // prepare conditions and push them in conditions array 

                foreach($condition_value as $val) {
                    
                    $c = "WHERE $c = '$val'";
               
                    array_push($conditions,$c);
                }
            }
            
            // convert conditions array into string to included in a query

            $conditions = implode(" AND ",$conditions);
            
            // get the total of records 

            $total = $this->mysql->RawFetch("SELECT count(id) as total FROM $tablename $conditions");
        
        }
     
        else if($custom !=null) {
            
            // get total records for custome query

            $total = $this->mysql->RawFetch($custom);
        
        }
        
        // get total records for query without conditions or custom query

        else $total = $this->mysql->RawFetch("SELECT count(id) as total FROM $tablename");
       
        $blanks= [];
        
        // prepare the search keyword and push them into blanks array

        foreach($columns as $key) {
            
            $key = "$key LIKE '%$search_for%'";
            
            array_push($blanks,$key);

        }
        
        // convert blanks array to string to includ it in a query 

        $blanks = implode(" OR ",$blanks);
        // if there is already any sql   conditions add the key word 'AND' before the searched key words

        if($condition != null)
        
            $query .= ' AND ('.$blanks.')';
        
        // if there is no conditions but the query include 'WHERE' sql command add the key word 'AND' before the searched key words

        else if($condition == null && strpos($query,'WHERE') == true)
        
            $query .= ' AND  ('.$blanks.')';
        
        //  if there is not condition or 'WHERE' command 
        else 
           
            $query .= ' WHERE ('.$blanks.')';

       
        // if group by is null put orderby command
        
       

        if($groupBy == null) $result = $this->pagination->Pagination($total[0]['total'],$page_name,$query,null,$orderBy,'desc');

        // if group by not null put order by command

        else if($groupBy != null) $result = $this->pagination->Pagination($total[0]['total'],$page_name,$query,$groupBy,null,null);
       
        // pagination page numbers

        $pagination_links = $this->pagination::PaginationLinks($result['total_pages'],$page_name);
   
        // check if list result is empty if not send the requsted data along side the paginations

        if($result != false) return ['data'  => $result['data'],'pagination_links' => $pagination_links];

        else return false;
    }
}

