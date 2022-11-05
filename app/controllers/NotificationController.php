<?php 

namespace cgc\platform\controllers;

use cgc\platform\libs\Notification;
use cgc\platform\database\Mysql;

class NotificationController
{
    private $notification;
    private $mysql;

    public function __construct()
    {
        $this->notification = new Notification();
        $this->mysql = new Mysql();
    }



    /** 
     * Get club demande notfication for the last 24 hours
     * @param int $club_id
     */
    public function MyClubDemandeNotification(int $club_id)
    {
        $notification = $this->notification->get(
                                                'demande_event_notification',
                                                ['club_id'],
                                                [$club_id],
                                                'created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)'
                                            );

        if($notification) return $notification;

        else return false;
    }

    
    /** 
     * Count total club demande notification
     * @param int $club_id
     */
    public function MyClubTotalNotification(int $club_id){
    
        $total = $this->mysql->Count(
                                    'demande_event_notification',
                                    ['club_id'],[$club_id],
                                    'created_at > DATE_SUB(CURDATE(), INTERVAL 1 DAY)'
                                );
        
        return $total;
    }

    /** 
     * Send demande notification for club
     * @param array $data
     */
    public function SendDemandNotification(array $data)
    {
        
        $send = $this->notification->create('demande_event_notification',$data);

        if($send) return true;

        else return false;
    }
}