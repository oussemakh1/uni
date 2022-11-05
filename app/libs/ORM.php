<?php 

namespace cgc\platform\libs;

use PDO;
use PDOException;
use cgc\platform\libs\Env;

class ORM
{
    
    private $host;
    private $username;
    private $password ;
    private $db;
    public $link = null;
    private $error;


    public function __construct ()
    {
        (new Env('app/.env'))->load();

        $this->host = getenv('HOST_DEV');

        $this->username =  getenv('USERNAME_DEV');

        $this->password =  getenv('PASSWORD_DEV');

        $this->db =  getenv('DB_DEV');
    }

    public function connect ()
    {
        $options = [

            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
       
                if($this->link == null):
                try
                {
                        $dsn = 'mysql:host='.$this->host.';dbname='.$this->db;

                        $this->link = new PDO($dsn, $this->username, $this->password,$options);

                        
                        return $this->link;
                }
                catch(PDOException $e)
                {
                        return   $this->error = 'Connection Failed! -> '. $e->getMessage();
                    
                }
                endif;
        

    

    }


    public  function disconnect () {
     
        return $this->link = null;
    }
  
    
    function insert ($tableName, $columns, $values)
     {
       
        $blanks = array();

        foreach($columns as $key) {
          
            $key ="?";
           
            array_push($blanks, $key);
        }


        $blanks = implode(",", $blanks);
        $columns = implode(",",$columns);

       

        $query = "INSERT INTO $tableName($columns) VALUES ($blanks)";
       

       
        $this->connect();
        
        $stmt = $this->link->prepare($query);
            
        $result =  $stmt->execute($values);
        
        $this->disconnect();
        
        if($result) {   

            return true;
        } 
            
        else {
            
            return false;

        } 
            
            

    }


    public function query($query)
    {
       return $this->link->query($query);
    }

    function updateById($tableName, $columns, $values,$id)
    {
        $blanks = array();

        foreach($columns as $key) {
          
            $key .="=?";
           
            array_push($blanks, $key);
        }
       
        $blanks = implode(",", $blanks);

        $query = "UPDATE $tableName SET $blanks WHERE  id = $id";

        $this->connect();
       
        $stmt = $this->link->prepare($query);
        
        $result = $stmt->execute($values);
        
        $this->disconnect();

        if($result){
          
                return true;
        } 
        
        else {
              
                return false;
        } 
        
    }


    function select($tableName, $columns = null,$condition = null ,$values = null,$custom=null,$orderby = null, $filter = null)
    {

        $orderby != null ? $orderby : $orderby ='id';
        
        $filter != null ? $filter : $filter='DESC'; 
        
        // check if fetching certain columns 

        if($columns != null && $condition == null && $values == null ) {
           
            $blanks = implode(",", $columns);

            $query = "SELECT $blanks FROM $tableName ORDER BY $orderby $filter";
           
        }

        // check if fetching certain columns with conditions

        else if($columns != null && $condition != null && $values != null) {

            $blanks = array();

            foreach($condition as $key) {
              
                $key .="=?";
               
                array_push($blanks, $key);
            }
           
            $blanks = implode(" AND ", $blanks);

            $columns = implode(",", $columns);
        
            if($custom !=null) $query = "SELECT $columns FROM $tableName WHERE $blanks  AND $custom ORDER BY $orderby $filter";
        
            else  $query = "SELECT $columns FROM $tableName WHERE $blanks ORDER BY $orderby $filter";
        }
        
        // if select all with condition
        else if($columns == null && $condition != null && $values != null) {
            $blanks = array();

            foreach($condition as $key) {
              
                $key .="=?";
               
                array_push($blanks, $key);
            }
           
            $blanks = implode(" AND ", $blanks);

    
            if($custom !=null)  $query = "SELECT * FROM $tableName WHERE $blanks AND $custom ORDER BY $orderby $filter";
        
            else $query = "SELECT * FROM $tableName WHERE $blanks ORDER BY $orderby $filter";
        }
        
        // fetch all 

        else  $query = "SELECT * FROM $tableName ORDER BY $orderby $filter";

        $this->connect();

        $stmt = $this->link->prepare($query);
        
        $stmt->execute($values);

        if($stmt->rowCount() > 0) {

            $result = $stmt->fetchAll();
            
            $this->disconnect();

            return $result;
        }
        else {
            
            $this->disconnect();
            
            return false;
        } 


    }


    function findById($tableName,$id)
    {
        $query = "SELECT * FROM $tableName WHERE id = $id ";
       
        $this->connect();
        
        $stmt = $this->link->prepare($query);
        
        $stmt->execute();

        if($stmt->rowCount() > 0) {

            $result = $stmt->fetchAll();
            
            $this->disconnect();
            
            return $result;
        }
        else {
            
            $this->disconnect();
            
            return false;
            
        } 
    }

    function deleteById($tableName, $id,$custom=null)
    {
        if($custom == null)
            $query = "DELETE FROM $tableName WHERE id = '$id'";
        
        else $query = "DELETE FROM $tableName WHERE $custom = '$id'";
        
        $this->connect();
        
        $stmt = $this->link->query($query);
        
        $this->disconnect();
        
        if($stmt) {
              
                return true;
        } 
        
        else {
               
                return false;
        } 

    }





    function RawFetch($query)
    {
      
        $this->connect();
        
        $stmt = $this->link->prepare($query);
        
        $stmt->execute();
     
        if($stmt->rowCount() > 0) {

            $result = $stmt->fetchAll();
            
            $this->disconnect();

            return $result;
        }

        else {
            
            $this->disconnect();
                 
            return false;
            
        } 

    }


    function Count($tablename = null, $columns = null, $values = null,$custom=null) 
    {
        if($tablename != null && $columns != null && $values != null) {

            $blanks = array();

            foreach($columns as $key) {
              
                $key .="=?";
               
                array_push($blanks, $key);
            }
           
            $blanks = implode(" AND ", $blanks);
        
            if($custom !=null) $query = "SELECT COUNT(id) as total FROM $tablename WHERE $blanks AND $custom";
        
            else $query = "SELECT COUNT(id) as total FROM $tablename WHERE $blanks";
        }

        else {
            
            $query = "SELECT COUNT(id) as total FROM $tablename";
        }

        $this->connect();
        
        $stmt = $this->link->prepare($query);

        $stmt->execute($values);

        $result = $stmt->fetch();
        
        $this->disconnect();

     
        return $result;

    }

   


}