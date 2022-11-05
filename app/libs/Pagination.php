<?php 

namespace cgc\platform\libs;
use cgc\platform\database\Mysql;

class Pagination 
{

    private $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql();
    }


    public function Pagination($total_results,$page_name, $query,$GroupBy=null,$orderby=null,$filter=null)
    {
        
        
        $perPage = 5;
        
        $total_pages = ceil(intval($total_results) / $perPage);
        
        // Current page
        $page = isset($_GET[$page_name]) ? $_GET[$page_name] : 1;

        $starting_limit = ($page - 1) * $perPage;
        
        $orderby != null ? $orderby : $orderby = 'id';
        $filter != null ? $filter : $filter ='DESC';
        // Query to fetch 
        if($GroupBy != null)
            $query .= " GROUP BY $GroupBy DESC LIMIT $starting_limit,$perPage";
        else if($orderby != null && $filter != null) $query .= " ORDER BY $orderby $filter LIMIT $starting_limit,$perPage";

        
      
        // Fetch all  for current page
        $fetchAll = $this->mysql->RawFetch($query);
       
        return [
            'data' => $fetchAll,
            'total_pages' => $total_pages
        ];
    }

    public static function PaginationLinks ($total_pages, $page_name)
    {
        $links = [];
        $request = $_SERVER['REQUEST_URI'];
        $current_page_name = strchr($request,'?'); 
       
        if(strpos($request, '?')== true && strpos($current_page_name, $page_name) == false)
        {
            for ($page = 1; $page <= $total_pages ; $page++){
                
                $link = '<a href='.$current_page_name.'&&'.$page_name.'='.$page.' class="btn btn-primary">'.$page.'</a>';
                array_push($links, $link) ;    
            }
        }

        else {
            for ($page = 1; $page <= $total_pages ; $page++){
                
                $link = '<a href=?'.$page_name.'='.$page.' class="btn btn-primary">'.$page.'</a>';
                array_push($links, $link) ;    
            }
        }
           
        return $links;
        
    }

}