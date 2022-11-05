<?php 


    // get id if page require id 
    $url = explode( "/", $_SERVER['REQUEST_URI']);
    $id = end($url);


    // setup current user type
    if(isset($_SESSION['log_data'])):
        $current_user = null;
        $club_id = null;
        $hasType = null;

        $session = $_SESSION['log_data'][0];

        if(isset($session['club_id'])) {

            if(isset($session['type'])) {

                if($session['type'] != 'member')
                    $current_user = 'mid';
                else $current_user = 'member';

            }

            else $current_user = 'president';
        }

        else $current_user = 'admin';


    endif;
?>


    