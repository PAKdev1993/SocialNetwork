<?php

namespace app\Table\Live;

use app\App;
use core\Functions;
use core\Session\Session;

class LiveModel
{

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMyLive()
    {
        return $this->db->query('SELECT * FROM we__lives WHERE fk_iduser = ?', [$this->session->read('auth')->pk_iduser])->fetch();
    }

    public function saveMyLive($embeded, $channelLink)
    {
        $existLive = $this->getMyLive();

        $channelLink    = Functions::secureVarSQL($channelLink, '<a>');
        //$embeded        = Functions::secureVarSQL($embeded, '<iframe><a>');

        if($existLive)
        {
            $this->db->query("UPDATE we__lives SET embedhtml = ?, channelLink = ?, online = ?, date = ? WHERE fk_iduser = ?",[
                $embeded,
                $channelLink,
                0,
                date("Y-m-d H:i:s"),
                $this->session->read('auth')->pk_iduser
            ]);
        }
        else{
            $this->db->query('INSERT INTO `we__lives` (`embedhtml`, `fk_iduser`, `channelLink`, `date`) VALUES (?, ?, ?, ?)', [
                $embeded,
                $this->session->read('auth')->pk_iduser,
                $channelLink,
                date("Y-m-d H:i:s")
            ]);
        }
    }

    public function turnMyLiveOn()
    {
        $this->db->query("UPDATE we__lives SET online = ?, date = ? WHERE fk_iduser = ?",[
            1,
            date("Y-m-d H:i:s"),
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function turnMyLiveOff()
    {
        $this->db->query("UPDATE we__lives SET online = ?, date = ? WHERE fk_iduser = ?",[
            0,
            date("Y-m-d H:i:s"),
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function getUserLiveFromId($iduser)
    {
        return $this->db->query('SELECT * FROM we__lives WHERE fk_iduser = ?', [$iduser])->fetch();
    }
}