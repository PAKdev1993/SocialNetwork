<?php

namespace app\Table\Profile\Events;

use app\App;
use core\Session\Session;
use core\Functions;

class EventModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMyEvents()
    {
        $events = $this->db->query("SELECT * FROM we__gamercareerevent WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $events;
    }

    public function getEventsFromId($iduser)
    {
        $events = $this->db->query("SELECT * FROM we__gamercareerevent WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $iduser
        ])->fetchAll();
        return $events;
    }

    public function getMyEventFromId($idevent)
    {
        $event = $this->db->query("SELECT * FROM we__gamercareerevent WHERE fk_iduser = ? AND pk_idevent =  ?",[
            $this->session->read('auth')->pk_iduser,
            $idevent
        ])->fetch();
        return $event;
    }

    public function saveMyEvent($name , $logo, $game, $team, $role, $startdate, $enddate, $platform, $description, $rank)
    {
        $suppressToken = Functions::str_random(60);

        $name       = strip_tags($name);
        $game       = strip_tags($game);
        $team       = strip_tags($team);
        $role       = strip_tags($role);
        $platform   = strip_tags($platform);
        $rank       = strip_tags($rank);
        $description   = strip_tags($description, '<a>');

        $this->db->query("INSERT INTO we__gamercareerevent SET fk_iduser = ?,  name = ?, logo = ?, game = ?, team = ?, role = ?, startdate = ?, enddate = ?, platform = ?, description = ?, rank = ?, suppressToken = ?", [
            Session::getInstance()->read('auth')->pk_iduser,
            $name,
            $logo,
            $game,
            $team,
            $role,
            $startdate,
            $enddate,
            $platform,
            $description,
            $rank,
            $suppressToken
        ]);
    }

    public function updateMyEvent($pk_idevent, $name , $logo, $game, $team, $role, $startdate, $enddate, $platform, $description, $rank)
    {
        $name       = strip_tags($name);
        $game       = strip_tags($game);
        $team       = strip_tags($team);
        $role       = strip_tags($role);
        $platform   = strip_tags($platform);
        $rank       = strip_tags($rank);
        $description   = strip_tags($description, '<a>');

        if($logo == 'didnttouch')
        {
            $this->db->query("UPDATE we__gamercareerevent SET name = ?, game = ?, team = ?, role = ?, startdate = ?, enddate = ?, platform = ?, description = ?, rank = ? WHERE pk_idevent = ?", [
                $name,
                $game,
                $team,
                $role,
                $startdate,
                $enddate,
                $platform,
                $description,
                $rank,
                $pk_idevent,
            ]);
        }
        else{
            $this->db->query("UPDATE we__gamercareerevent SET name = ?, logo = ?, game = ?, team = ?, role = ?, startdate = ?, enddate = ?, platform = ?, description = ?, rank = ? WHERE pk_idevent = ?", [
                $name,
                $logo,
                $game,
                $team,
                $role,
                $startdate,
                $enddate,
                $platform,
                $description,
                $rank,
                $pk_idevent
            ]);
        }

    }

    public function deleteMyEvent($eventid)
    {
        $this->db->query("DELETE FROM we__gamercareerevent WHERE pk_idevent = ? AND fk_iduser = ?", [
            $eventid,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function getMyEmployeeEvents()
    {
        $events = $this->db->query("SELECT * FROM we__employeecareerevent WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $events;
    }

    public function getUserEmployeeEventsFromIduser($iduser)
    {
        $events = $this->db->query("SELECT * FROM we__employeecareerevent WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $iduser
        ])->fetchAll();
        return $events;
    }

    public function getMyEmployeeEventFromId($idevent)
    {
        $event = $this->db->query("SELECT * FROM we__employeecareerevent WHERE fk_iduser = ? AND pk_ideventcareer =  ?",[
            $this->session->read('auth')->pk_iduser,
            $idevent
        ])->fetch();
        return $event;
    }

    public function saveMyEmployeeEvent($name , $logo, $jobtitle, $startdate, $enddate, $company, $description)
    {
        $suppressToken = Functions::str_random(60);

        $name           = strip_tags($name);
        $jobtitle       = strip_tags($jobtitle);
        $company        = strip_tags($company);
        $description    = strip_tags($description, '<a>');

        $this->db->query("INSERT INTO we__employeecareerevent SET fk_iduser = ?,  name = ?, logo = ?, jobtitle = ?, startdate = ?, enddate = ?, company = ?, description = ?, suppressToken = ?", [
            Session::getInstance()->read('auth')->pk_iduser,
            $name,
            $logo,
            $jobtitle,
            $startdate,
            $enddate,
            $company,
            $description,
            $suppressToken
        ]);
    }

    public function updateMyEmployeeEvent($pk_ideventcareer, $name , $logo, $jobtitle, $startdate, $enddate, $company, $description)
    {
        $name           = Functions::secureVarSQL($name);
        $jobtitle       = Functions::secureVarSQL($jobtitle);
        $company        = Functions::secureVarSQL($company);
        $description    = Functions::secureVarSQL($description, '<a>');
        
        if($logo == 'didnttouch')
        {
            $this->db->query("UPDATE we__employeecareerevent SET name = ?, jobtitle = ?, startdate = ?, enddate = ?, company = ?, description = ? WHERE pk_ideventcareer = ?", [
                $name,
                $jobtitle,
                $startdate,
                $enddate,
                $company,
                $description,
                $pk_ideventcareer
            ]);
        }
        else{
            $this->db->query("UPDATE we__employeecareerevent SET name = ?, logo = ?, jobtitle = ?, startdate = ?, enddate = ?, company = ?, description = ? WHERE pk_ideventcareer = ?", [
                $name,
                $logo,
                $jobtitle,
                $startdate,
                $enddate,
                $company,
                $description,
                $pk_ideventcareer
            ]);
        }
    }

    public function deleteEmployeeEvent($eventid)
    {
        $this->db->query("DELETE FROM we__employeecareerevent WHERE pk_ideventcareer = ?", [$eventid]);
    }

    public function getEventSuppressTokenFromId($eventid)
    {
        return $this->db->query("SELECT suppressToken FROM we__gamercareerevent WHERE fk_iduser = ? AND pk_idevent = ?",[
            $this->session->read('auth')->pk_iduser,
            $eventid
        ])->fetch()->suppressToken;
    }

    public function getEmpEventSuppressTokenFromId($eventid)
    {
        return $this->db->query("SELECT suppressToken FROM we__employeecareerevent WHERE fk_iduser = ? AND pk_ideventcareer = ?",[
            $this->session->read('auth')->pk_iduser,
            $eventid
        ])->fetch()->suppressToken;
    }
}