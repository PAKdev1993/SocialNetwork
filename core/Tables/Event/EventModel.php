<?php

namespace core\Tables\Event;

use app\App;
use core\Session\Session;

class EventModel
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
        $owner =  $this->db->query("SELECT fk_iduser FROM we__gamercareerevent WHERE pk_idevent = ?",[$eventid])->fetch()->fk_iduser;
        return $owner == $this->session->read('auth')->pk_iduser;
    }
}