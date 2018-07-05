<?php

namespace app\Table\MyCommunity;

use app\App;
use core\Session\Session;

class MyCommunityModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    //#todo comprend pourquoi le ORDER BY fonctionne mal
    //#todo FAIRE UN JOIN ON QI et recuperer la photo de profil après ds le display
    public function getMyContacts($begin, $nbcontact)
    {
        $limit = $begin + $nbcontact;
        $contacts = $this->db->query("SELECT * FROM we__contacts LEFT JOIN we__user ON id_contact = pk_iduser WHERE we__contacts.fk_iduser = ? LIMIT $begin, $limit",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $contacts;
    }

    public function saveContact($iduser)
    {
        //delete de la table askscontacts
        $this->db->query("DELETE FROM we__contactsask WHERE fk_iduserfrom = ? AND fk_iduserto = ?",[
            $iduser,
            $this->session->read('auth')->pk_iduser
        ]);

        //#TODO VERY IMPORTANT: changer la structure de la table contact pour ca, ou alors creer une autre table, a faire très rapidement car a cause de cette methode le nombre d'entrée a chaque ajourts est doublé
        //ajout ds la table des contacts
        //POUR LE CURRENT USER
        $this->db->query("INSERT INTO we__contacts SET fk_iduser = ?, id_contact = ?", [
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);
        //POUR L'USER QUI ASK
        $this->db->query("INSERT INTO we__contacts SET fk_iduser = ?, id_contact = ?", [
            $iduser,
            $this->session->read('auth')->pk_iduser
        ]);

        //NOTIFICATION
        App::getNotificationManager()->createNotification($this->session->read('auth')->pk_iduser, $iduser, 11, NULL);
    }

    public function deleteContactFromIduser($iduser)
    {
        //POUR LE CURRENT USER
        $this->db->query("DELETE FROM we__contacts WHERE fk_iduser = ? AND id_contact = ?", [
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);
        //POUR L'USER QUI ASK
        $this->db->query("DELETE FROM we__contacts WHERE fk_iduser = ? AND id_contact = ?", [
            $iduser,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function getMypendingContactsQi($begin, $nbcontact)
    {
        $limit = $begin + $nbcontact;
        $pendingContactsQi = $this->db->query("SELECT * FROM we__contactsask as t1 LEFT JOIN we__quickinfos as t2 ON t1.fk_iduserfrom = t2.fk_iduser WHERE t1.fk_iduserto = ? LIMIT $begin, $limit",[
            $this->session->read('auth')->pk_iduser,
        ])->fetchAll();
        return $pendingContactsQi;
    }

    public function amIPendingContactFromIdUser($iduser)
    {
        $pendingContact = $this->db->query("SELECT fk_iduserfrom FROM we__contactsask WHERE fk_iduserfrom = ? AND fk_iduserto = ? OR fk_iduserto = ? AND fk_iduserfrom = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser,
            $this->session->read('auth')->pk_iduser,
            $iduser
        ])->fetch();
        if($pendingContact)
        {
            return 1;
        }
        else{
            return 0;
        }
    }

    public function getMyContactFromIduser($iduser)
    {
        $contact = $this->db->query("SELECT * FROM we__contacts WHERE fk_iduser = ? AND id_contact = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser
        ])->fetch();
        return $contact;
    }

    public function getMyFolowers($begin, $nbfollowers)
    {
        $limit = $begin + $nbfollowers;
        $followers = $this->db->query("SELECT * FROM we__folowingusers LEFT JOIN we__user ON fk_iduser = pk_iduser WHERE we__folowingusers.id_userfolowed = ? ORDER BY nickname ASC LIMIT $begin, $limit",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $followers;
    }

    public function isBlockedFromId($iduser)
    {
        $contactsid = $this->db->query("SELECT id_userBlocked FROM we__usersblocked WHERE fk_iduser = ? AND id_userBlocked = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser
        ])->fetchAll();
        return $contactsid;
    }

    public function blockMyContactFromId($iduser)
    {
        //ajout ds la table des user blocked
        $this->db->query("INSERT INTO we__usersblocked SET fk_iduser = ?, id_userBlocked = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);

        //mise a jour de la table contact
        $this->db->query('UPDATE we__contacts SET blocked = ? WHERE fk_iduser = ? AND id_contact = ?', [
            1,
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);
    }

    public function unblockMyContactFromId($iduser)
    {
        //delete de la table des user blocked
        $this->db->query("DELETE FROM we__usersblocked WHERE fk_iduser = ? AND id_userBlocked = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);

        //mise a jour de la table contact
        $this->db->query('UPDATE we__contacts SET blocked = ? WHERE fk_iduser = ? AND id_contact = ?', [
            0,
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);
    }

    public function askContact($iduser)
    {
        //ajout ds la table des user blocked
        $this->db->query("INSERT INTO we__contactsask SET fk_iduserfrom = ?, fk_iduserto = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);

        //NOTIFICATION
        App::getNotificationManager()->createNotification($this->session->read('auth')->pk_iduser, $iduser, 12, NULL);
    }

    public function deleteAskContact($iduser)
    {
        //ajout ds la table des user blocked
        $this->db->query("DELETE FROM we__contactsask WHERE fk_iduserfrom = ? AND fk_iduserto = ? OR (fk_iduserfrom = ? AND fk_iduserto = ?)",[
            $this->session->read('auth')->pk_iduser,
            $iduser,
            $iduser,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function declineInvitation($iduser)
    {
        //ajout ds la table des user blocked
        $this->db->query("DELETE FROM we__contactsask WHERE fk_iduserfrom = ? AND fk_iduserto = ?",[
            $iduser,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function remainInvitations()
    {
        //ajout ds la table des user blocked
        $count = $this->db->query("SELECT COUNT(fk_iduserfrom) as nbRemain FROM we__contactsask WHERE fk_iduserto = ?",[
            $this->session->read('auth')->pk_iduser
        ])->fetch()->nbRemain;
        if($count)
        {
            return $count;
        }
        return 0;
    }

    public function getMyNbContacts()
    {
        $count = $this->db->query("SELECT COUNT(id_contact) as nbContacts FROM we__contacts WHERE id_contact = ?",[
            $this->session->read('auth')->pk_iduser
        ])->fetch()->nbContacts;
        if($count)
        {
            return $count;
        }
        return 0;
    }

    public function getMyNbFollowers()
    {
        $count = $this->db->query("SELECT COUNT(id_userfolowed) as nbFollowers FROM we__folowingusers WHERE id_userfolowed = ?",[
            $this->session->read('auth')->pk_iduser
        ])->fetch()->nbFollowers;
        if($count)
        {
            return $count;
        }
        return 0;
    }

    public function getNbContactsFromIduser($iduser)
    {
        $count = $this->db->query("SELECT COUNT(id_contact) as nbContacts FROM we__contacts WHERE id_contact = ?",[
            $iduser
        ])->fetch()->nbContacts;
        if($count)
        {
            return $count;
        }
        return 0;
    }

    public function getNbFollowersFromIdUser($iduser)
    {
        $count = $this->db->query("SELECT COUNT(fk_iduser) as nbFollowers FROM we__folowingusers WHERE id_userfolowed = ?",[
            $iduser
        ])->fetch()->nbFollowers;
        if($count)
        {
            return $count;
        }
        return 0;
    }

    public function amIFollowingFromIduser($iduser)
    {
        $personnalState = $this->db->query("SELECT fk_iduser FROM we__folowingusers WHERE fk_iduser = ? AND id_userfolowed = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser
        ])->fetch();
        if($personnalState)
        {
            return 1;
        }
        else{
            return 0;
        }
    }

    public function saveFollowFromIdUser($iduser)
    {
        $this->db->query("INSERT INTO we__folowingusers SET fk_iduser = ?, id_userfolowed = ?", [
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);

        //CREATE NOTIFICATION
        App::getNotificationManager()->createNotification($this->session->read('auth')->pk_iduser, $iduser, 10, NULL);
    }

    public function removeFollowFromIdUser($iduser)
    {
        $this->db->query("DELETE FROM we__folowingusers WHERE fk_iduser = ? AND id_userfolowed = ?", [
            $this->session->read('auth')->pk_iduser,
            $iduser
        ]);
    }

    public function amIContactFromIdUser($iduser)
    {
        $contact = $this->db->query("SELECT fk_iduser FROM we__contacts WHERE fk_iduser = ? AND id_contact = ?",[
            $this->session->read('auth')->pk_iduser,
            $iduser
        ])->fetch();
        if($contact)
        {
            return 1;
        }
        else{
            return 0;
        }
    }
}