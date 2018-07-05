<?php

namespace core\Tables\Equipment;

use app\App;
use core\Session\Session;

class EquipmentModel
{

    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function isMyEquipment($equipmentid) //#todo reflechir a la loqique de placer ceci ds core
    {
        $owner =  $this->db->query("SELECT fk_iduser FROM we__gamercareergear WHERE pk_idgear = ?",[$equipmentid])->fetch()->fk_iduser;
        return $owner == $this->session->read('auth')->pk_iduser;
    }
}