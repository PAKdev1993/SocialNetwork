<?php

namespace core\Tables\Career;

use app\App;
use core\Session\Session;

class CareerModel
{

    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function isMyTeam($teamid)
    {
        $owner =  $this->db->query("SELECT fk_iduser FROM we__gamercareerteam WHERE pk_idteam = ?",[$teamid])->fetch()->fk_iduser;
        return $owner == $this->session->read('auth')->pk_iduser;
    }
}