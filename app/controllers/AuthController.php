<?php 

namespace cgc\platform\controllers;

use cgc\platform\database\Mysql;
use cgc\platform\libs\Message;

class AuthController 
{

    private $mysql;
    private $message;

    public function __construct()
    {
        $this->mysql = new Mysql();
        $this->message = new Message();
    }


   

    public function Login(array $cred, string $tablename)
    {
       
        // check if user exists in db

        $check = $this->checkIfExists($cred, $tablename);

    
        if($check != false) {
            
            // check user exists in more then one club 

            if(sizeof($check) > 1)
            {

                // init list of clubs related to user
                $list_clubs = [];

                // get list of clubs id user related to then push them into '$list_clubs' array
                foreach($check as $c)
                {
                    $club_id = $c['club_id'];
                    
                    $query = "SELECT name,id,logo FROM clubs WHERE id = $club_id";
    
                    $clubs = $this->mysql->RawFetch($query);
                    
                    array_push($list_clubs,$clubs[0]);
                }
               
                // return list of clubs to user to choose from
                return $list_clubs;
            }
            else {

                // user related to one club
                // log user in
                
                session_start();

                $_SESSION['log_data'] = $check;
              
                return true;
            }
         
        }
        
            
        // user do not exist in db

        else return false;
    }


    public function ChooseClub($tablename,$creds,$club_id)
    {
        
            // let user choose the club want to sign into

           $custom = "AND club_id = $club_id";
           
           // check if user exists in the selected club

           $check = $this->checkIfExists($creds,$tablename,$custom);

           if($check) {

           // log user into selected club 
            
           session_start();

            $_SESSION['log_data'] = $check;
       
          
            return true;
           }

           // user don't exist in the selected club 

           else return false;


    }


    public function Logout()
    {
        $_SESSION['log_data'] = null;

        if (isset($_COOKIE['club_id'])) {
            unset($_COOKIE['club_id']); 
            setcookie('club_id', null, -1, '/'); 
            echo "    document.cookie = club_id =; Max-Age=-99999999;';  
            ";
         
        }

        session_destroy();
    }


    public function checkIfExists (array $cred, $tablename,$custom=null) 
    {
        // user email && passsword

        $email = $cred['email'];
        
        $password = $cred['password'];
        
        // if user has more then one club check into his selected club if user exists in

        if($custom)  $query = "SELECT * FROM $tablename WHERE email ='$email' $custom";
        
        // check if user email exists in db
        else  $query = "SELECT * FROM $tablename WHERE email ='$email'";
        
        $checkCred = $this->mysql->RawFetch($query);
       
        if($checkCred == false)  return  $this->message->CustomError('adresse email erronée');


        if($checkCred != false) {

            // validate if user password match requsted account 

            if(password_verify($password, $checkCred[0]['password'])) {
                return $checkCred;
            }

            // password do not match requested account 

            else return  $this->message->CustomError("mot's de pass erronée");
        }

        // email do not exists in db
        
        else return false;
    }
    



}