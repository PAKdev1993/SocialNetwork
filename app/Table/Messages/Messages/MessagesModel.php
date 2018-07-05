<?php

namespace app\Table\Messages\Messages;

use app\App;
use core\Session\Session;

class MessagesModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMessagesFromConversation($idconv, $beginAt)
    {
        $messages = $this->db->query("SELECT * FROM we__Message WHERE fk_idconversation = ? ORDER BY date DESC LIMIT $beginAt,25",[
            $idconv
        ])->fetchAll();

        if($messages)
        {
            return $messages;
        }
        else{
            return [];
        }
    }

    /**
     * Cette fonction sert a recuperer le tableaux de vars nescessaires a l'affichage d'une notifConv de
     * @param $idconv
     * @param $iduser
     */
    public function getNotifsInfosFromIdConv($idconv)
    {
        $result = array();
        //get la date de la dernière consult de la conv pour le calcul du nb de notifs
        $date = $this->db->query("SELECT consultedAt FROM we__EnregConversation WHERE fk_idconversation = ? AND fk_iduser = ?",[
            $idconv,
            $this->session->read('auth')->pk_iduser
        ])->fetch()->consultedAt;

        //get les derniers message non lu de la conv
        $lastMessages = $this->db->query("SELECT * FROM we__Message WHERE date > ? AND fk_idconversation = ? ORDER BY date ASC",[
            $date,
            $idconv
        ])->fetchAll();

        //get le nb de messages non lus
        $nbMessagesNonLus = sizeof($lastMessages);
        if($nbMessagesNonLus == 0)
        {
            $result = array();
        }
        else{
            $lastMessage = array_pop($lastMessages);

            //implement result array()
            $result['lastMessage']  = $lastMessage;
            $result['nbNotifs']     = $nbMessagesNonLus;
        }
        return $result;
    }

    public function getApercuConvArray($idconv)
    {
        $result = array();

        //get la date de la dernière consult de la conv pour le calcul du nb de notifs
        $date = $this->db->query("SELECT consultedAt FROM we__EnregConversation WHERE fk_idconversation = ? AND fk_iduser = ?",[
            $idconv,
            $this->session->read('auth')->pk_iduser
        ])->fetch()->consultedAt;

        //get les derniers message non lu de la conv
        $unreadMessages = $this->db->query("SELECT * FROM we__Message WHERE date > ? AND fk_idconversation = ? ORDER BY date ASC",[
            $date,
            $idconv
        ])->fetchAll();

        //s'il y a des messages non lus
        if($unreadMessages)
        {
            //get le nb de messages non lus
            $nbMessagesNonLus = sizeof($unreadMessages);
            $lastMessage = reset($lastMessages);

            //implement result array()
            $result['lastMessage']  = $lastMessage;
            $result['nbNotifs']     = $nbMessagesNonLus;
        }
        else{
            //get le dernier message de la conv
            $lastMessage = $this->db->query("SELECT * FROM we__Message WHERE fk_idconversation = ? ORDER BY date DESC LIMIT 0,1",[
                $idconv
            ])->fetch();
            if($lastMessage)
            {
                $result['lastMessage']  = $lastMessage;
                $result['nbNotifs']     = '';
            }
            else{
                $result['lastMessage']  = array();
                $result['nbNotifs']     = '';
            }
        }
        return $result;
    }

    /**
     * This function is used to know the id of the author of precedent message in the conversation
     * @param $convid
     * @param $dateMessage
     * @return bool
     */
    public function getPrevMessageAuthIdFromMessage($convid, $dateMessage)
    {
        $prevMessage = $this->db->query("SELECT * FROM we__Message WHERE date < ? AND fk_idconversation = ? ORDER BY date DESC LIMIT 0,1",[
            $dateMessage,
            $convid
        ])->fetch();
        if($prevMessage)
        {
            return $prevMessage->fk_iduser;
        }
        else{
            return false;
        }
    }

    /**
     * This function is used to get string of idusers who read the message wich was wrote at the date specified
     * @param $idconv
     * @param $dateMessage
     * @return string of idusers
     */
    public function getReadedUserIds($idconv, $dateMessage, $idMessAuthor)
    {
        //si le message est le miens on get les id des users different du miens
        if($idMessAuthor == $this->session->read('auth')->pk_iduser)
        {
            $results = $this->db->query("SELECT fk_iduser FROM we__EnregConversation WHERE fk_idconversation = ? AND consultedAt >= ? AND fk_iduser != ?",[
                $idconv,
                $dateMessage,
                $idMessAuthor
            ])->fetchAll();
        }
        //sinon on get les id de users différent du miens et de l'user
        else{
            $results = $this->db->query("SELECT fk_iduser FROM we__EnregConversation WHERE fk_idconversation = ? AND consultedAt >= ? AND fk_iduser != ? AND fk_iduser != ?",[
                $idconv,
                $dateMessage,
                $this->session->read('auth')->pk_iduser,
                $idMessAuthor
            ])->fetchAll();
        }
        $idusersString = '';
        if($results)
        {
            $idusersArr = array();
            foreach ($results as $result)
            {
                array_push($idusersArr, $result->fk_iduser);
            }
            $idusersString = implode(',',$idusersArr);
        }
        return $idusersString;
    }

    //#todo fonction lourde et très souvent répétée, trouver un moyen d'eco des resources
    public function getReadebyTrad()
    {
        $langUser   = $this->session->read('auth')->langWebsite;
        $readBy     = $this->db->query('SELECT readedBy FROM we__lang__messagecenter WHERE fk_langname = ?', [$langUser])->fetch();
        if($readBy->readedBy)
        {
            return $readBy->readedBy;
        }
        else{
            $defaultLang = 'en';
            return $this->db->query('SELECT readedBy FROM we__lang__messagecenter WHERE fk_langname = ?', [$defaultLang])->fetch();
        }
    }
}