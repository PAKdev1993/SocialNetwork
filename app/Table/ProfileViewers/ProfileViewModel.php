<?php

namespace app\Table\ProfileViewers;

use app\App;
use core\Session\Session;

class ProfileViewModel
{
    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }
    
    public function getMyProfileViewers()
    {
        $viewers = $this->db->query("SELECT * FROM we__ProfileViewers as pv LEFT JOIN we__quickinfos as qi ON pv.fk_iduser = qi.fk_iduser WHERE id_userviewed = ? ORDER BY date DESC",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $viewers;
    }

    //#todo OPTIMISER LA REQUETTE
    public function getMyProfileViewerForHeader()
    {
        $viewers = $this->db->query("SELECT * FROM we__ProfileViewers as pv LEFT JOIN we__quickinfos as qi ON pv.fk_iduser = qi.fk_iduser WHERE id_userviewed = ? ORDER BY date DESC LIMIT 0,3",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $viewers;
    }

    public function getLastViewFromUserSlug($userid)
    {
        return  $this->db->query("SELECT MAX(date) as date FROM we__ProfileViewers WHERE id_userviewed = ? AND fk_iduser = ?",[
            $userid,
            $this->session->read('auth')->pk_iduser
        ])->fetch()->date;
    }

    public function addViewerToUser($userid)
    {
        //Test to avoid the case where an user wright something wrong in the u parameter of URL
        if($userid)
        {
            $dateLastView = $this->getLastViewFromUserSlug($userid);
            $time1  =       strtotime($dateLastView);
            $time2 =        strtotime(date("Y-m-d H:i:s"));
            $interval =     $time2 - $time1;
            $oneDaySec = 24 * 3600;
            if($interval <= $oneDaySec)
            {
                $this->updateViewDate($userid, $dateLastView);
            }
            else{
                $this->saveViewer($userid);
            }
        }
        else{
            return false;
        }

    }

    public function updateViewDate($userid, $dateLastView)
    {
        $this->db->query("UPDATE we__ProfileViewers SET date = ? WHERE date = ? AND fk_iduser = ?",[ //#todo OPTIMISATION recuperer l'entrée we__ProfileViewers grace a son id et non grace a la combinaison des paramètres
            date("Y-m-d H:i:s"),
            $dateLastView,
            $userid
        ]);
    }

    public function saveViewer($userid)
    {
        $this->db->query("INSERT INTO we__ProfileViewers SET id_userviewed = ?, fk_iduser = ?",[
            $userid,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    //fonction qui update l'etat consulted de chaques entrée de we__ProfileViewers et le met a 1, declenché par le chargement de la page ProfileView
    public function updateConsulted()
    {
        $this->db->query("UPDATE we__ProfileViewers SET consulted = ? WHERE id_userviewed = ?",[ //#todo OPTIMISATION recuperer l'entrée we__ProfileViewers grace a son id et non grace a la combinaison des paramètres
            1,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function getNbProfileViewUnconsulted()
    {
        $nbNotifs = $this->db->query("SELECT COUNT(consulted) as nbNewViewers FROM we__profileviewers WHERE id_userviewed = ? AND consulted = ?",[
            $this->session->read('auth')->pk_iduser,
            0
        ])->fetch()->nbNewViewers;
        return $nbNotifs;
    }

    public function consultMyProfileVewer()
    {
        $this->db->query("UPDATE we__profileviewers SET consulted = ? WHERE id_userviewed = ?",[
            1,
            $this->session->read('auth')->pk_iduser
        ]);
    }
}