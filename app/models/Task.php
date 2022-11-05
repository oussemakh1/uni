<?php 

namespace cgc\platform\models;

use cgc\platform\database\Mysql;
use cgc\platform\libs\Message;
use cgc\platform\libs\Pagination;
use cgc\platform\libs\ORM;

class Task 
{
    private $mysql;
    private $columns;
    private $message;
    private $pagination;
    private $orm;

    public function __construct()
    {
        $this->mysql = new Mysql();
        $this->columns = [
            'club_id', 'date_ddl', 'time_ddl', 'sender_id', 'reciver_id', 'task', 'title'
        ];
        $this->message = new Message();
        $this->pagination  = new Pagination();
        $this->orm = new ORM();
    }


    public function list($club_id,$owner_id,$search_for=null)
    {
        if($search_for != null) {

            $query = "SELECT * from tasks WHERE sender_id = $owner_id ";

            $search = $this->search->search($query,'tasks',$search_for,['title','assigned_to','date_ddl','time_ddl'],['club_id','sender_id'],[$club_id,$owner_id],null,'date_ddl',"tasks");

            return $search;
        } 
        else {
            $total_tasks = $this->mysql->Count('tasks',['club_id','sender_id'],[$club_id,$owner_id]);


            $query = "SELECT * FROM tasks WHERE club_id = $club_id AND sender_id = $owner_id ";
            
              
            $Tasks = $this->pagination->Pagination($total_tasks['total'],'page_tasks',$query,'tasks.date_ddl',null,null);
    
            $pagination_links = $this->pagination::PaginationLinks($Tasks['total_pages'],'page_tasks');
    
            if($Tasks != false) return [ 'data'  => $Tasks['data'],'pagination_links' => $pagination_links];

            else return false;
        }
    }

    public function recived($club_id,$reciver_id,$search_for=null)
    {
        if($search_for != null) {

            $query = "SELECT tasks.date_ddl,tasks.time_ddl,tasks.id as task_id,tasks.title,tasks_status.status as current_status from tasks INNER JOIN tasks_status ON tasks.id = tasks_status.task_id WHERE tasks_status.reciver_id = $reciver_id  AND tasks.club_id = $club_id";

            $custom = "SELECT COUNT(tasks.id) as total from tasks INNER JOIN tasks_status ON tasks.id = tasks_status.task_id WHERE tasks_status.reciver_id = $reciver_id  AND tasks.club_id = $club_id";

            
            $search = $this->search->search($query,'tasks',$search_for,['title','assigned_to','date_ddl','time_ddl'],null,null,null,'tasks.id',"tasks",$custom);

            return $search;
        } 
        else {
            $total_tasks = $this->mysql->RawFetch("SELECT COUNT(tasks.id) as total from tasks INNER JOIN tasks_status ON tasks.id = tasks_status.task_id WHERE tasks_status.reciver_id = $reciver_id AND tasks.club_id = $club_id");


            $query = "SELECT tasks.date_ddl,tasks.time_ddl,tasks.id as task_id,tasks.title,tasks_status.status as current_status from tasks INNER JOIN tasks_status ON tasks.id = tasks_status.task_id WHERE tasks_status.reciver_id = $reciver_id  AND tasks.club_id = $club_id ";
            
          
              
            $Tasks = $this->pagination->Pagination($total_tasks[0]['total'],'page_tasks',$query,'tasks.id',null,null);
            
            $pagination_links = $this->pagination::PaginationLinks($Tasks['total_pages'],'page_tasks');
    
            if($Tasks != false) return [ 'data'  => $Tasks['data'],'pagination_links' => $pagination_links];

            else return false;
        }
    }

    public function send($data)
    {
        $recivers =explode(",",$data['reciver_id']);
        
      
        $send =$this->mysql->insert('tasks',$this->columns,array_values($data));

        $this->orm->connect();
       
        $stmt = $this->orm->query("SELECT id FROM tasks order by id desc LIMIT 1");
        $task_id = $stmt->fetchColumn();

        $this->orm->disconnect();
      
        if($send) $status = true;
        
        else $status = false;

        $status = false;
        foreach($recivers as $reciver)
        {
           
            $data['reciver_id'] = $reciver;
            $columns_status = ['club_id','sender_id', 'reciver_id','task_id','status'];
            $values_status = [$data['club_id'],$data['sender_id'],$data['reciver_id'],$task_id,'pending'];

            $setStatus = $this->mysql->insert('tasks_status',$columns_status,$values_status);

            if($setStatus) $status= true;

            else $status = false;
           
        }

        if($status == true) return true;

        else  return $this->message->error('server_error');


    }

   



    public function find($club_id,$sender_id,$task_id)
    {

        $query = "
        SELECT *,tasks.title, 
        tasks.task, 
        tasks.id,
        tasks.date_ddl,
        tasks.time_ddl,
        club_members.id as member_id,
        club_members.fullname 
                
                FROM tasks_status INNER JOIN tasks ON tasks_status.task_id = tasks.id 
                
                INNER JOIN  club_members ON tasks_status.reciver_id = club_members.id
                
                WHERE tasks_status.task_id = $task_id AND tasks_status.sender_id = $sender_id
                GROUP BY tasks_status.reciver_id

        ";

        $task = $this->mysql->RawFetch($query);

        if($task) return $task;

        else return $this->message->error('server_error');
    }


    public function task_detail($club_id,$task_id)
    {
        $query = "
            SELECT tasks.title,
                    tasks.task,
                    tasks_status.status,
                    tasks.date_ddl,
                    tasks.time_ddl,
                    club_members.id as member_id,
                    club_members.fullname 
                    
                    FROM tasks INNER JOIN club_members ON tasks.club_id = club_members.club_id 
                    INNER JOIN tasks_status ON tasks_status.task_id = tasks.id

                    WHERE EXISTS(SELECT * FROM tasks_status WHERE tasks_status.reciver_id = club_members.id ) 
                    AND tasks.club_id = $club_id  AND tasks.id = $task_id GROUP by tasks_status.id;

        ";

        $task = $this->mysql->RawFetch($query);

        if($task) return $task;

        else return $this->message->error('server_error');
    }

    public function updateStatus($club_id,$sender_id,$task_id,$members_done,$members_undone,$members_pending)
    {
        $validate = $this->isAuthorized($club_id,$sender_id,$task_id);
       
        $status = false;

        if($validate){

            if($members_done > 0) {

                foreach($members_done as $hasDoneId) {
                    

                    $query = "UPDATE tasks_status SET status ='done' WHERE club_id = $club_id AND sender_id = $sender_id AND reciver_id = '$hasDoneId' AND task_id = $task_id";
                     $this->orm->connect();
                    
                    $update = $this->orm->query($query);
                    $this->orm->disconnect();
                    if($update) $status = true;
        
                    else $status = true;
                }
             
            }

            if($members_undone > 0) {

                foreach($members_undone as $hasUndoneId){

                    $query = "UPDATE tasks_status SET status ='undone' WHERE club_id = $club_id AND sender_id = $sender_id AND reciver_id = '$hasUndoneId' AND task_id = $task_id";
            
                      $this->orm->connect();
                    
                    $update = $this->orm->query($query);
                    $this->orm->disconnect();
        
                    if($update) $status = true;
        
                    else $status = true;
                }
             
            }

            if($members_pending > 0) {

                foreach($members_pending as $hasPendingId) {

                    $query = "UPDATE tasks_status SET status ='pending' WHERE club_id = $club_id AND sender_id = $sender_id AND reciver_id = '$hasPendingId' AND task_id = $task_id";
            
                    $this->orm->connect();
                    
                    $update = $this->orm->query($query);
                    $this->orm->disconnect();
        
                    if($update) $status = true;
        
                    else $status = true;
                }
             
            }

            if($status) return true;
            else return $this->message->error('server_error');
         
        }
        else return $this->message->error('server_error');
    }


    public function delete($club_id,$sender_id,$task_id)
    {
        $validate = $this->isAuthorized($club_id,$sender_id,$task_id);

        if($validate) {
            
            $delete = $this->mysql->deleteById('tasks',$task_id);

            if($delete) return true;

            else return $this->message->error('server_error');
        }

        else return $this->message->error('server_error');

    }





    private function isAuthorized($club_id,$sender_id,$task_id)
    {
        $query = "SELECT * FROM tasks WHERE id = $task_id AND club_id = $club_id AND sender_id = $sender_id";

        $validate = $this->mysql->RawFetch($query);

        if($validate) return true;
        else return false;
    }
}