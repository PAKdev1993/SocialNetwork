<?php

namespace core\Tables\Game;

use app\App;
use core\Session\Session;

class GameModel
{

    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function isMyGame($gameid)
    {
        $owner = $this->db->query("SELECT fk_iduser FROM we__gamercareergames WHERE pk_idgame = ?",[$gameid])->fetch()->fk_iduser;
        return $owner == $this->session->read('auth')->pk_iduser;
    }
}