<?php 

namespace cgc\platform\models;

use cgc\platform\database\Mysql;
use cgc\platform\libs\Message;
use cgc\platform\libs\ORM;

class Meeting
{
    private $mysql;
    private $columns;
    private $message;
    private $orm;

    public function __construct()
    {
        $this->mysql = new Mysql();
        $this->columns = [
            'club_id', 'date', 'time', 'sender_id', 'recivers_id', 'reason', 'note','place','type','absents_id'
        ];
        $this->message = new Message();
        $this->orm = new ORM();
    }


    public function sended($club_id,$sender_id)
    {

        $query = "SELECT * FROM meetings WHERE club_id = $club_id AND sender_id = $sender_id ORDER BY date DESC";

        $meetings = $this->mysql->RawFetch($query);

        if($meetings) return $meetings;

        else return $this->message->error('server_error');
    }


    public function find($id)
    {
        $query = "SELECT * FROM meetings WHERE id = $id";

        $meeting = $this->mysql->RawFetch($query);
       
        if($meeting) return $meeting;
        else return false;
    }

    function recived($club_id,$reciver_id)
    {
        $query = "SELECT * FROM meetings WHERE club_id  = $club_id AND recivers_id CONTAINS '$reciver_id' ";

        $meetings = $this->mysql->RawFetch($query);

        if($meetings) return $meetings;

        else return $this->message->error('server_error');
    }


    public function store($data)
    {


        $store = $this->mysql->insert('meetings',$this->columns,array_values($data));
        $this->orm->connect();
        $stmt = $this->orm->query("SELECT id FROM `meetings` order by id desc LIMIT 1");

        $meeting_id = $stmt->fetchColumn();
      
        $this->orm->disconnect();

        $recivers = explode(",",$data['recivers_id']);

        $status = false;

        
        foreach($recivers as $reciver)
        {
            $meeting = $this->mysql->insert('meetings_status',['club_id','sender_id','absent_id','meeting_id','reciver_id'],[$data['club_id'],$data['sender_id'],0,$meeting_id,$reciver]);

            if($meeting) $status = true;

            else $status = false;
        }
       

        if($store == true && $status == true) return true;
        else return $this->message->error('server_error');
    }



    public function attendance ($club_id,$meeting_id,$sender_id,$absents,$present)
    {

    
        $validate = $this->isAuthorized($club_id,$sender_id,$meeting_id);
    
        if($validate) {

            $status = false;
            if($absents != 0):
                foreach($absents as $absent) {

                    $query = "UPDATE meetings_status SET absent_id = $absent, modified_at = CURRENT_DATE() WHERE club_id = $club_id AND sender_id = $sender_id AND meeting_id = $meeting_id and reciver_id =                            $absent";

                     $this->orm->connect();

                    $update = $this->orm->query($query);
                    
                    $this->orm->disconnect();

                    if($update) $status = true;
                    else $status = false;
                }
            endif;
            if($present != 0):
                foreach($present as $p) {

                    $query = "UPDATE meetings_status SET absent_id = 0,  modified_at = CURRENT_DATE() WHERE club_id = $club_id AND sender_id = $sender_id AND meeting_id = $meeting_id and reciver_id = $p";
                    
                     $this->orm->connect();

                    $update = $this->orm->query($query);
                    
                    $this->orm->disconnect();

                    if($update) $status = true;
                    else $status = false;
                }
            endif;


            if($status == true OR $absents == 0) return true;
            else return $this->message->error('server_error');
            
           

        }

        else return $this->message->CustomError('Non autorisé');
    }


    public function delete ($club_id,$meeting_id,$sender_id)
    {
        $validate = $this->isAuthorized($club_id,$sender_id,$meeting_id);
        if($validate) {
            $delete = $this->mysql->deleteById('meetings',$meeting_id);

            if($delete) return true;
            else return $this->message->error('server_error');

        }

        else return $this->message->CustomError('Non autorisé');
    }



    private function isAuthorized($club_id,$sender_id,$meeting_id)
    {
        $query = "SELECT * FROM meetings WHERE id = $meeting_id AND club_id = $club_id AND sender_id = $sender_id";

        $validate = $this->mysql->RawFetch($query);

        if($validate) return true;
        else return false;
    }
}