<?php 

namespace cgc\platform\controllers;


use cgc\platform\models\Meeting;
use cgc\platform\libs\Pagination;
use cgc\platform\libs\Search;
use cgc\platform\libs\Message;
use cgc\platform\database\Mysql;


class MeetingController
{

    private $meeting;
    private $pagination;
    private $search;
    private $message;
    private $mysql;


    public function __construct()
    {
        $this->meeting = new Meeting();
        $this->pagination = new Pagination();
        $this->search = new Search();
        $this->message = new Message();
        $this->mysql = new Mysql();
    }



      /**
     * Get current logged user meetings
     * @param int $club_id
     * @param int $member_id
     * @param string $search_for
     */
    public function GetMyMeetings(int $club_id, int $member_id, string $search_for=null)
    {
        // check current logged in user role (if he is not a normal member he can check meetings he declared )
        // only users that are not normal users can declare meetings

        $validate = $this->ValidateRole($member_id);

        if($validate) 
        {
        
                // check if there is any search for certain meeting 

                if($search_for != null) {
                
                    $query = "SELECT date,time,type,place FROM meetings WHERE club_id = $club_id AND sender_id = $member_id";
                
                    // use 'Search' lib to return results ('Search' lib already using 'Pagination' lib)

                    $search = $this->search->search($query,'owned_meetings',$search_for,['date','time','type','place'],['club_id','sender_id'],[$club_id,$member_id],null,'date','page_my_meetings');
                
                    if($search) return $search;

                    else return $this->message->error('server_error');

                }else {

                    // get total of meetings records for the current logged in member 

                    $total_meetings = $this->mysql->Count('meetings',['club_id','sender_id'],[$club_id,$member_id]);

                    $query = "SELECT date,time,type,place FROM meetings WHERE club_id = $club_id AND sender_id = $member_id";

                    // return records with pagination using 'Pagination' lib 

                    $meetings = $this->pagination->Pagination($total_meetings,'page_my_meeting',$query,null,'date');
                    
                    // return pages number of required records 

                    $pagination_links = $this->pagination->PaginationLinks($meetings['total_pages'],'page_my_meetings');

                    // return meetings data along side with pages links

                    if($meetings != false) return [ 'data'  => $meetings['data'],'pagination_links' => $pagination_links];

                    else return $this->message->error('server_error');


            
                
                }

        }
    }   


    /** 
     * Get meetings that sended for current logged in user
     * @param int $club_id
     * @param int $member_id
     * @param string $search_for
     */
    public function RecivedMeetings( int $club_id, int $member_id, string $search_for=null)
    {

           
                    // select meetings where current logged in user id is in their recivers_id filed
                    // Searching in the table 'meetings_status.reciver_id' filed
                    $query = "SELECT 
                                    
                                    meetings.id,
                                    meetings.date,
                                    meetings.time,
                                    meetings.type,
                                    meetings.place 
                                    
                                    FROM meetings_status 
                                    INNER JOIN meetings 
                                    ON meetings_status.meeting_id = meetings.id 
                                    
                                    WHERE meetings_status.club_id = $club_id AND meetings_status.reciver_id = $member_id;";
                  
                    // count total of meetings where current logged in user id exist in their reciver_id filed
                    $total_meetings_query = "SELECT 
                                
                                count(meetings.id) as total

                                FROM meetings_status 
                                INNER JOIN meetings ON meetings_status.meeting_id = meetings.id 
                                
                                WHERE meetings_status.club_id = $club_id AND meetings_status.reciver_id = $member_id";
       
                // check if there is any search for certain recived meeting 

                if($search_for != null) {
                 


                    // Use 'Search' lib for getting the looked for record for the current logged in user 

                    $search = $this->search->search(
                                                    $query,
                                                    'meetings_status',
                                                    $search_for,
                                                    ['date','time','type','place'],
                                                    null,
                                                    null,
                                                    'meetings.id',
                                                    null,
                                                    'page_my_meetings',
                                                    $total_meetings_query
                                                );
                
                    if($search) return $search;

                    else return $this->message->error('server_error');

                }else {


                    // get total of recived meetings for current logged in user 

                    $total_meetings = $this->mysql->RawFetch($total_meetings_query);


                    // Use 'Pagination' lib for fetching the records
                    $meetings = $this->pagination->Pagination($total_meetings,'page_my_meeting',$query,null,'meetings.id');
                    
                    // get pages numbers
                    $pagination_links = $this->pagination->PaginationLinks($meetings['total_pages'],'page_my_meetings');


                    // return records along side pages links
                    if($meetings != false) return [ 'data'  => $meetings['data'],'pagination_links' => $pagination_links];

                    else return $this->message->error('server_error');


            
                
                }

        
    } 


    /** 
     * Return the list of owned meeting for the current logged in user
     * @param int $club_id
     * @param int $sender_id
     * @param string $search_for
     */
    public function OwnedMeetings(int $club_id, int $sender_id, string $search_for=null)
    {
        // get meetings where sender_id filed same as current logged in user id 

        $query = "SELECT * FROM  meetings WHERE club_id = $club_id and sender_id = $sender_id";

        if($search_for !=null) {

            // user 'Search' lib to get records ('Search' lib already using 'Pagination' lib )
            $search = $this->search->search(
                                            $query,
                                            'meetings',
                                            $search_for,
                                            ['date','time','place','type'],
                                            null,
                                            null,
                                            null,
                                            'date',
                                            'meetings'
                                        );
            
            return $search;
        }

        else {
            
            // count total of owned meetings

            $total_owned_meetings = $this->mysql->Count('meetings',['club_id','sender_id'],[$club_id,$sender_id]);

            // use 'Pagination' lib to get records 
            $meetings = $this->pagination->Pagination($total_owned_meetings['total'],'page_meetings',$query,null,'date','desc');

            // return pages numbers 
            $pagination_links = $this->pagination::PaginationLinks($meetings['total_pages'],'page_meetings');

            // return records along side with pages numbers
            if($meetings !=false) return ['data' => $meetings['data'],'pagination_links' => $pagination_links];
            
            else return false;
        }
    }

    /** 
     * Find logged user owned meeting by meeting id && club_id && user_id
     * @param int $club_id
     * @param int $sender_id
     * @param int $meeting_id
     */
    public function FindOwnedMeeting(int $club_id, int $sender_id, int $meeting_id)
    {
        // get meeting with the invited members where sender_id equal to logged in user
        $query = "
                SELECT 
                    meetings_status.reciver_id,
                    meetings_status.absent_id as member_absent,
                    meetings.type as type_meeting,
                    club_members.id as member_id,
                    club_members.fullname,
                    club_members.type as member_type,
                    meetings.date, meetings.place,
                    meetings.time 
                    
                    FROM meetings INNER JOIN meetings_status ON meetings_status.meeting_id = meetings.id 
                    INNER JOIN club_members ON meetings_status.reciver_id = club_members.id
                    
                    WHERE EXISTS(SELECT * FROM club_members WHERE meetings_status.reciver_id = club_members.id )
                    AND meetings.sender_id = $sender_id AND meetings.id = $meeting_id AND meetings.club_id = $club_id
                    
                    GROUP BY meetings_status.id,club_members.id;
        ";
        
        $meeting = $this->mysql->RawFetch($query);
        


        if($meeting) return $meeting;

        else return $this->message->error('server_error');
    }

    /** 
     * Set meeting absence for the invited users
     * @param int $club_id
     * @param int $sender_id
     * @param int $meeting_id
     * @param array $absents
     * @param array $present
     */
    public function SetAbsence(int $club_id, int $sender_id, int $meeting_id, array $absents, array $present)
    {
    
        
        $update = $this->meeting->attendance($club_id,$meeting_id,$sender_id,$absents,$present);
      

        if($update) return true;

        else return $this->message->error('server_error');
    }


    /** 
     * Delete meeting
     * @param int $club_id
     * @param int $sender_id
     * @param int $meeting_id
     */
    public function DeleteMyMeeting (int $club_id, int $sender_id, int $meeting_id)
    {
        $delete = $this->meeting->delete($club_id,$meeting_id,$sender_id);

        if($delete) return true;

        else return $this->message->error('server_error');
    }


    /** 
     * validate if current logged in user is not of they type  'member'
     * @param int $member_id
     */
    private function ValidateRole(int $member_id)
    {
        $query = "SELECT * FROM club_members WHERE type='bureau' OR  type = 'leader' OR type ='manager' AND id = $member_id";

        $member = $this->mysql->RawFetch($query);

        if($member) return true;
 
        else return false;
    }
}