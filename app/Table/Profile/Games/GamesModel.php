<?php

namespace app\Table\Profile\Games;

use app\App;
use core\Session\Session;
use core\Functions;

class GamesModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMyGames()
    {
        $games = $this->db->query("SELECT * FROM we__gamercareergames WHERE fk_iduser = ? ORDER BY pk_idgame DESC",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $games;
    }

    public function getGamesFromUserId($iduser)
    {
        $games = $this->db->query("SELECT * FROM we__gamercareergames WHERE fk_iduser = ? ORDER BY pk_idgame DESC",[
            $iduser
        ])->fetchAll();
        return $games;
    }
    
     public function getMyGameFromId($idgame)
     {
         $game = $this->db->query("SELECT * FROM we__gamercareergames WHERE fk_iduser = ? AND pk_idgame =  ?",[
             $this->session->read('auth')->pk_iduser,
             $idgame
         ])->fetch();
         return $game;
     }

    public function saveMyGames($name, $gameaccount, $logo, $plateform)
    {
        $suppressToken = Functions::str_random(60);

        $name           = Functions::secureVarSQL($name);
        $gameaccount    = Functions::secureVarSQL($gameaccount);
        $plateform      = Functions::secureVarSQL($plateform);

        $this->db->query("INSERT INTO we__gamercareergames SET fk_iduser = ?,  name = ?, gameaccount = ?, logo = ?, platform = ?, suppressToken = ?", [
            Session::getInstance()->read('auth')->pk_iduser,
            $name,
            $gameaccount,
            $logo,
            $plateform,
            $suppressToken
        ]);
    }

    public function updateMyGames($pk_idgame, $name , $logo, $plateform, $gameaccount)
    {
        $name           = Functions::secureVarSQL($name);
        $gameaccount    = Functions::secureVarSQL($gameaccount);
        $plateform      = Functions::secureVarSQL($plateform);
        
        if($logo == 'didnttouch')
        {
            $this->db->query("UPDATE we__gamercareergames SET name = ?, platform = ?, gameaccount = ? WHERE pk_idgame = ?", [
                $name,
                $plateform,
                $gameaccount,
                $pk_idgame
            ]);
        }
        else{
            $this->db->query("UPDATE we__gamercareergames SET name = ?, logo = ?, platform = ?, gameaccount = ? WHERE pk_idgame = ?", [
                $name,
                $logo,
                $plateform,
                $gameaccount,
                $pk_idgame
            ]);
        }
    }

    public function deleteGame($gameid)
    {
        $this->db->query("DELETE FROM we__gamercareergames WHERE pk_idgame = ?", [$gameid]);
    }

    public function getGameSuppressTokenFromId($gameid)
    {
        return $this->db->query("SELECT suppressToken FROM we__gamercareergames WHERE fk_iduser = ? AND pk_idgame = ?",[
            $this->session->read('auth')->pk_iduser,
            $gameid
        ])->fetch()->suppressToken;
    }
}