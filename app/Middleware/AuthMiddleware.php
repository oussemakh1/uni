<?php 

namespace cgc\platform\Middleware;

use cgc\platform\database\Mysql;


class AuthMiddleware 
{

    private $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql();
    }



    public function checkIfExists (array $cred, $tablename) 
    {
        $email = $cred['email'];
        
        $password = $cred['password'];
        
        $query = "SELECT * FROM $tablename WHERE email ='$email' and password ='$password'";
        
        $checkCred = $this->mysql->RawFetch($query);

        if($checkCred != false)  return $checkCred;

        else return false;
    }



    public function Guard($guard) {
       
        if(isset($_SESSION['log_data'])) {

            $cred = [
              "email" => $_SESSION['log_data'][0]['email'],
              "password" => $_SESSION['log_data'][0]['password']
            ];
    
            $check = $this->checkIfExists($cred, $guard);
           
            if($check == false) {
                
                echo '<script type="text/javascript">';
                echo 'window.location.href="/";';
                echo '</script>';
              
            } else return true;
    
        }
        else {
            
          echo '<script type="text/javascript">';
          echo 'window.location.href="/";';
          echo '</script>';
        } 
    }


    public function AuthGuard () {
        
        $request = $_SERVER['REQUEST_URI'];

        switch($request) {


            // member dashboard
            case '/member_workplace':
                $this->Guard('club_members');
                break;

            //  president dashboard
                
            case '/dashboard_president' :
                $this->Guard('presidents');
                


            //  president add demande        
            case '/president/demande' :
                 $this->Guard('presidents');
                


            // club members
            case '/members':
                if(isset($_SESSION['log_data']['0']['type']) != false)
                {
                    if($_SESSION['log_data']['0']['type'] != 'member')
                    {
                      return  $this->Guard('club_members');
                    }
                    else return $this->Guard('presidents');
                      
                }
                else { return $this->Guard('presidents');}
               

            // president add member
            case '/president/add_member':
                return $this->Guard('presidents');
                break;

                
            // admin dashboard
            case '/dashboard_admin' :
                 $this->Guard('admins');
                

            // admin clubs list
            case '/clubs' :
                 $this->Guard('admins');
                

            // admin add club 
            case '/club/add' :
                $this->Guard('admins');
                

           // admin add club president
            case '/president/add' :
                 $this->Guard('admins');
               


             // access for pages require the access to id ex: path -> 'club/edit/id'
            case preg_match('/[\s\S]*\d$/','/edit') :
            
                $path= strchr($request, 'edit');
            
                $id =  strchr($request,"=");

                
                switch($request){
                    
                    // if  admin club update 
                    case $path == 'edit/club?id'.$id :
            
                       $this->Guard('admins');
                        
                    
                    // if admin handle demande
                    case $path == 'edit/demande?id'.$id:
            
                         $this->Guard('admins');
                       


                            
                    // if admin  update president
                    case $path == 'edit/president?id'.$id:
            
                        $this->Guard('admins');
                        

        
                       

            }
            

             // access for pages require the access to id ex: path -> 'club/edit/id'
             case preg_match('/[\s\S]*\d$/','/president') :
            
                $path= strchr($request, 'president');
            
                $id =  strchr($request,"=");

                
                switch($request){
                    
                    
                    // president preview demande
                    case $path == '/club_demande?id'.$id:

                        $this->Guard('presidents');
                        

                        
                    // president update member
                    case $path == '/update_member?id'.$id:

                        echo '<script>alert("hi")</script>';
                       

            }




        }
    }
}