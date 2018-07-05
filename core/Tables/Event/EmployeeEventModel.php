<?php

namespace core\Tables\Event;

use app\App;
use core\Session\Session;

class EmployeeEventModel
{

    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function isMyEvent($eventid)
    {
        $owner =  $this->db->query("SELECT fk_iduser FROM we__employeecareerevent WHERE pk_ideventcareer = ?",[$eventid])->fetch()->fk_iduser;
        return $owner == $this->session->read('auth')->pk_iduser;
    }
}