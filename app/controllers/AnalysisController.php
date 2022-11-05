<?php 

namespace cgc\platform\controllers;

use cgc\platform\database\Mysql;

class AnalysisController 
{


    private $mysql;


    public function __construct()
    {
        $this->mysql = new Mysql();
    }



    public function TotalClubDemands(int $club_id)
    {

        $TotalDemands = $this->mysql->Count('demande_event',['club_id'],[$club_id]);

        if($TotalDemands) return $TotalDemands;

        else return false;
    }


    public function TotalClubAcceptedDemands (int $club_id)
    {
        $TotalDemands = $this->mysql->Count('demande_event',['club_id','status'],[$club_id,'accepted']);

        if($TotalDemands) return $TotalDemands;

        else return false;
    }


    public function TotalClubMembers (int $club_id)
    {

        $TotalMembers = $this->mysql->Count('club_members', ['club_id'], [$club_id]);

        if($TotalMembers) return $TotalMembers;

        else return false;
    }


    public function TotalClubs ()
    {

        $TotalClubs = $this->mysql->Count('clubs');

        if($TotalClubs) return $TotalClubs;
        
        else return false;
    }


    public function TotalEvents () 
    {
        $TotalEvents = $this->mysql->Count('demande_event',['status'],['accepted']);

        if($TotalEvents) return $TotalEvents;

        else return false;
    }

    public function TotalMembers()
    {
        $TotalMembers = $this->mysql->Count('club_members');

        if($TotalMembers) return $TotalMembers;

        else return false;
    }


}