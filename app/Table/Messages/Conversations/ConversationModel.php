<?php

namespace app\Table\Messages\Conversations;

use app\App;
use app\Table\UserModel\displayUser;
use app\Table\UserModel\UserModel;
use core\Functions;
use core\Session\Session;

class ConversationModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();

        //CONSTANTES
        //conv state
        $this->varDeletedConv =    4; //4: deleted (jamais utilisé lor de la creation d'une conversation), la conversation ne s'affiche plus ni ds les chatboxes ni das le msg center
        $this->varGroupedConv =    3; //3: grouped (jamais utilisé lor de la creation d'une conversation), la conversation est ouverte mais elle est grouped, c'est a dire que l'user a bcp de convers open et ne peux plus en afficher d'autre pour des raisons de places sur l'ecran donc les conversations en trop passent en mode grouped
        $this->varMinimizedConv =  2; //2: minimized (jamais utilisé lor de la creation d'une conversation), la conversation chatBoxe est ouverte mais est minimisées
        $this->varOpenConv =       1; //1: opened, la converssation est ouverte et elle s'affiche ds les chatBoxes ouvertes lor de la connection de l'user
        $this->varClosedConv =     0; //0: non open conversation, signifie que la conversation ne s'affichera pas ds les conversation chatBox lor de la connection de l'user a son compte
        
        //type conv
        $this->varEmptyConv =       "emptyConv";
        $this->varUserToUserConv =  "userTouser";
        $this->varGroupConv =       "groupConv";
        
        //range vars
        $this->nbMaxConvOpen =      4;
        $this->nbMessagesToOpen =   25;
    }

    public function getConvsByRange($begin = 0, $nbToDisplay = 25)
    {
        //get les convs join enreg conv
        $convs = $this->db->query("SELECT * FROM we__EnregConversation JOIN we__Conversation ON we__EnregConversation.fk_idconversation = we__Conversation.pk_idconversation WHERE we__EnregConversation.fk_iduser = ? AND we__EnregConversation.state != ? AND typeConv != ? ORDER BY consultedAt DESC LIMIT $begin, $nbToDisplay", [
            $this->session->read('auth')->pk_iduser,
            $this->varDeletedConv,
            'emptyConv'
        ])->fetchAll();

        //get l'empty conv to put on top of array
        $emptyConv = $this->db->query("SELECT * FROM we__EnregConversation JOIN we__Conversation ON we__EnregConversation.fk_idconversation = we__Conversation.pk_idconversation WHERE we__EnregConversation.fk_iduser = ? AND typeConv = ? ORDER BY consultedAt DESC LIMIT $begin, $nbToDisplay", [
            $this->session->read('auth')->pk_iduser,
            'emptyConv'
        ])->fetchAll();

        $convs = array_merge($emptyConv, $convs);

        return $convs;
    }

    /**
     * sert a recuperer un object conversation/enregconversation pour le passer a displayConversation
     * @param $idconv
     * @return objet join conversation/enregConversation
     */
    public function getConversationFromId($idconv)
    {
        $conversation = $this->db->query("SELECT * FROM we__Conversation JOIN we__EnregConversation ON we__Conversation.pk_idconversation = we__EnregConversation.fk_idconversation  WHERE we__Conversation.pk_idconversation = ? AND we__EnregConversation.fk_iduser = ?",[
            $idconv,
            $this->session->read('auth')->pk_iduser
        ])->fetch();
        return $conversation;
    }

    /**
     * sert a recuperer un object conversation
     * @param $idconv
     * @return objet conversation
     */
    public function getOnlyConversationFromId($idconv)
    {
        $conversation = $this->db->query("SELECT * FROM we__Conversation WHERE pk_idconversation = ?",[
            $idconv,
        ])->fetch();
        return $conversation;
    }

    public function getEnregConvFromId($idconv)
    {
        $enregConversation = $this->db->query("SELECT * FROM we__enregConversation WHERE fk_idconversation = ? AND fk_iduser = ?",[
            $idconv,
            $this->session->read('auth')->pk_iduser
        ])->fetch();
        return $enregConversation;
    }

    /**
     * Cette fonction retourne la conversation vide de l'user courant, il ne peux y en avoir qu'une seule, c'est la conversation avec pour createur l'user courant et le nbParticipant a 1
     */
    public function getEmptyConv()
    {
        $conversation = $this->db->query("SELECT * FROM we__Conversation JOIN we__EnregConversation ON we__Conversation.pk_idconversation = we__EnregConversation.fk_idconversation WHERE we__Conversation.fk_iduser = ? AND we__Conversation.nbParticipants = ?",[
            $this->session->read('auth')->pk_iduser,
            1
        ])->fetch();
        return $conversation;
    }
    
    /**
     * @param $idusers array(): tableau d'user id a rajouter a la conversation
     * @param $addUsersToConv bool: indique si on doit ajouter les users a la conv tout de suite, sert lors de la creation de nouvelles conv depuis la fonction prepareAddUser pour permettre d'accelerer le temps de reponse de la fonction prepareAddUser to conv et permettre d'ajouter les users plus tard
     * @return sql_object last conversation inserted
     */
    public function createConversation($idusers, $addUsersToConv = true)
    {
        //get type of conv
        $nbParticipants = sizeof($idusers);
        $typeConv = '';
        if($nbParticipants == 1)
        {
            $typeConv = $this->varEmptyConv;
        }
        if($nbParticipants == 2)
        {
            $typeConv = $this->varUserToUserConv;
        }
        if($nbParticipants > 2)
        {
            $typeConv = $this->varGroupConv;
        }

        //creer une conversation
        $this->db->query("INSERT INTO we__Conversation SET fk_iduser = ?, typeConv = ?", [
            $this->session->read('auth')->pk_iduser,
            $typeConv
        ]);

        //get l'id de la conv nouvelement crée
        $idConvers = $this->db->lastInsertId();

        //ajouter l'user courant a la conv (important car sinon get conv from id echoue, car on fait un whhere fk_user = curent user ds enregConv)
        $this->addUserToConv(array($this->session->read('auth')->pk_iduser), $idConvers, true, true);

        //si addUserToConv alors on ajoute les users a la conv
        if($addUsersToConv)
        {
            //ajoute les users a la conv
            $this->addUserToConv($idusers, $idConvers, false, true);
        }

        //return conversation nouvelelement crée:
        $conv = $this->getConversationFromId($idConvers);
        return $conv;
    }

    /**
     * Cette foncton creer les entrées ds la table enreg_conversation une fois la conversation crée, conversation fixée au state 0 (closed par default) modifié ensuite lor de l'affichage
     * @param $idusers
     * @param $idconv
     */
    public function addUserToConv($idusers, $idconv, $addCurrentUser = true, $increaseNbParticipants = false)
    {
        if($addCurrentUser)
        {
            $myid = $this->session->read('auth')->pk_iduser;
            foreach($idusers as $iduser)
            {
                if($idusers == $myid)
                {
                    $this->db->query("INSERT INTO we__EnregConversation SET fk_iduser = ?, fk_idconversation = ?, state = ?, date = ?, consultedAt = ?", [
                        $iduser,
                        $idconv,
                        $this->varOpenConv,
                        date("Y-m-d H:i:s"),
                        date("Y-m-d H:i:s")
                    ]);
                }
                else{
                    $this->db->query("INSERT INTO we__EnregConversation SET fk_iduser = ?, fk_idconversation = ?, state = ?, date = ?, consultedAt = ?", [
                        $iduser,
                        $idconv,
                        $this->varClosedConv,
                        date("Y-m-d H:i:s"),
                        date("Y-m-d H:i:s")
                    ]);
                }

                //increase nbParticipants if nescessary
                if($increaseNbParticipants)
                {
                    $this->db->query('UPDATE we__Conversation SET nbParticipants = nbParticipants + 1 WHERE pk_idconversation = ?', [
                        $idconv
                    ]);
                }
            }
        }
        else{
            //retirer l'user courant de l'array
            $idusers = array_diff($idusers, array($this->session->read('auth')->pk_iduser));
            foreach($idusers as $iduser)
            {
                $this->db->query("INSERT INTO we__EnregConversation SET fk_iduser = ?, fk_idconversation = ?, state = ?, date = ?, consultedAt = ?", [
                    $iduser,
                    $idconv,
                    $this->varClosedConv,
                    date("Y-m-d H:i:s"),
                    date("Y-m-d H:i:s")
                ]);

                //increase nbParticipants if nescessary
                if($increaseNbParticipants)
                {
                    $this->db->query('UPDATE we__Conversation SET nbParticipants = nbParticipants + 1 WHERE pk_idconversation = ?', [
                        $idconv
                    ]);
                }
            }
        }
    }

    /**
     * Cette fonction sert a inserer des users dans une conversation et mettre a jour les conversations des users pour faire en sorte que la conversation
     * @param $idusers array() des users a rajouter a la conversation
     * @param $idConv int id de la conversation nouvelement crée et a laquelle il faut rajouter les users
     */
    public function putOpenConvForUsers($idusers, $idConv)
    {
        //se rajouter a la list des users
        array_push($idusers, $this->session->read('auth')->pk_iduser);

        //ajouter chaques idusers (celui de l'user courant inclus) a la conversation en ecrivant les entrées de enregConversation
        //definis la valeur open par default pour tout les users
        foreach($idusers as $iduser)
        {
            //compter combien de conv open ou minimized compte l'user
            $nbOpenConv = $this->db->query("SELECT COUNT(*) FROM we__EnregConversation JOIN we__Conversation ON we__Conversation.pk_idconversation = we__EnregConversation.fk_idconversation WHERE we__Conversation.activated = ? AND (we__EnregConversation.fk_iduser = ? AND (sate = ? OR state = ?))", [
                $iduser,
                $this->varOpenConv,
                $this->varMinimizedConv
            ])->fetchAll();

            if($nbOpenConv >= $this->nbMaxConvOpen)
            {
                //get idconv to change in grouped
                $idconv = $this->db->query("SELECT fk_idconversation FROM we__EnregConversation JOIN we__Conversation ON we__Conversation.pk_idconversation = we__EnregConversation.fk_idconversation WHERE we__Conversation.activated = ? AND (we__EnregConversation.fk_iduser = ? AND (sate = ? OR state = ?) LIMIT 0,1)", [
                    $iduser,
                    $this->varOpenConv,
                    $this->varMinimizedConv
                ]);

                //update conv et la passer en grouped pour l'user
                $this->db->query('UPDATE we__EnregConversation SET state = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                    $this->varGroupConv,
                    $idconv,
                    $iduser
                ]);
            }
            else{
                $this->db->query("INSERT INTO we__EnregConversation SET fk_iduser = ?, fk_idconversation = ?, state = ?, date = ?", [
                    $iduser,
                    $idConv,
                    $this->varOpenConv,
                    date("Y-m-d H:i:s")
                ]);
            }
        }
    }

    /**
     * Cette fonction sert a inserer un users dans une conversation et mettre a jour l'etat des conversations de l'user
     * @param $iduser int id de l'user a rajouter a la conversation
     * @param $idConv int id de la conversation nouvelement crée et a laquelle il faut rajouter les users
     */
    public function openConvForUser($iduser, $idConv)
    {
        //compter combien de conv open ou minimized compte l'user
        $nbOpenConv = $this->db->query("SELECT COUNT(*) as nbConvOpen FROM we__EnregConversation JOIN we__Conversation ON we__Conversation.pk_idconversation = we__EnregConversation.fk_idconversation WHERE we__Conversation.activated = ? AND (we__EnregConversation.fk_iduser = ? AND (we__EnregConversation.state = ? OR we__EnregConversation.state = ?))", [
            1,
            $iduser,
            $this->varOpenConv,
            $this->varMinimizedConv
        ])->fetch()->nbConvOpen;

        if(intval($nbOpenConv) >= $this->nbMaxConvOpen)
        {
            //get idconv to change in grouped
            $idconv = $this->db->query("SELECT fk_idconversation FROM we__EnregConversation JOIN we__Conversation ON we__Conversation.pk_idconversation = we__EnregConversation.fk_idconversation WHERE we__Conversation.activated = ? AND (we__EnregConversation.fk_iduser = ? AND (we__EnregConversation.sate = ? OR we__EnregConversation.state = ?) LIMIT 0,1)", [
                1,
                $iduser,
                $this->varOpenConv,
                $this->varMinimizedConv
            ]);

            //update conv et la passer en grouped pour l'user
            $this->db->query('UPDATE we__EnregConversation SET state = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                $this->varGroupConv,
                $idconv,
                $iduser
            ]);

            //update conv to open
            $this->db->query('UPDATE we__EnregConversation SET state = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                $this->varOpenConv,
                $idconv,
                $iduser
            ]);
        }
        else{
            //update conv to open
            $this->db->query('UPDATE we__EnregConversation SET state = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                $this->varOpenConv,
                $idConv,
                $iduser
            ]);
        }
    }

    /**
     * @return sql_object conversation
     */
    public function createEmptyConversation()
    {
        //creer la conversation empty
        $this->db->query("INSERT INTO we__Conversation (`pk_idconversation`, `fk_iduser`, `title`, `nbParticipants`, `typeConv`) VALUES (NULL, ?, NULL, ?, ?)", [
            $this->session->read('auth')->pk_iduser,
            1,
            $this->varEmptyConv
        ]);

        //get l'id de l'empty conv juste crée
        $idConvers = $this->db->lastInsertId();

        //ajouter l'user courant inclus a la conversation en ecrivant les entrées de enregConversation
        $this->db->query("INSERT INTO we__EnregConversation SET fk_iduser = ?, fk_idconversation = ?, state = ?, date = ?, consultedAt = ?", [
            $this->session->read('auth')->pk_iduser,
            $idConvers,
            $this->varClosedConv,
            date("Y-m-d H:i:s"),
            date("Y-m-d H:i:s")
        ]);

        //return conversation nouvelelement crée:
        $result = $this->getConversationFromId($idConvers);

        return $result;
    }

    /**
     * Cette fonction sert a traiter le cas des ajouts d'users et retourner les conv associées
     * L'ajour des enregistrement ds enreg_conv est efféctué plus tard (via addUserToConv) pour optimiser la vitesse d'affichage
     * Le titre de la conversation est généré après
     * La conversation nouvelement crée est retournée
     * @param $idusersToAdd, tableau d'idusers a rajouter a la conv
     * @param $idConversation
     * @return sql_object $result
     */
    public function prepareAddUsersToConversation($idusersToAdd, $idConversation)
    {
        //retirer du tableau les users rajouté participant deja a la conv (cas du hack html)
        $usersALreadyIn = $this->getIdParticipants($idConversation);
        $idusers = array_diff($idusersToAdd, $usersALreadyIn);

        //retirer du tableau les users qui ne sont pas mes amis
        $userModel = new UserModel();
        $friendsIdArray = $userModel->getMyContactsIdsInArray();
        $idusers = array_intersect($idusers, $friendsIdArray);

        $result = array();

        //get conv type of conv from wich we use add user function to define what to do
        $convType = $this->getTypeConv($idConversation);

        //si la conv est une empty conv
        if($convType == 'emptyConv')
        {
            //get all users of new convers in array
            $idusersNewConv = array_merge($idusers, $usersALreadyIn);

            //on test l'existence de la conv
            $conv = '';
            if(sizeof($idusersNewConv) == 2)
            {
                $conv = $this->conversExistBetweenTwoUsers($idusersNewConv[0]);
            }
            if(sizeof($idusersNewConv) > 2)
            {
                $conv = $this->checkConvExistFromIdusers($idusersNewConv);
            }

            //si la conv existe deja
            if($conv)
            {
                $result['state'] =      'convExist';
                $result['conv'] =       $conv;
                $result['convid'] =     $conv->pk_idconversation;
                $result['convHtml'] =   ''; //filled after
                return $result;
            }
            //sinon
            else{
                //on crée une nouvelle conv
                $conv = $this->createConversation($idusersNewConv, false);
                $result['state'] =      'newConvCreated';
                $result['toAdd'] =      implode($idusers,',');
                $result['conv'] =       $conv;
                $result['convid'] =     $conv->pk_idconversation;
                $result['convHtml'] =   ''; //filled after
                return $result;
            }
        }

        //si la conv est une conv user To user
        if($convType == 'userTouser')
        {
            //get all users of new convers in array
            $idusersNewConv = array_merge($idusers, $usersALreadyIn);

            $conv = $this->checkConvExistFromIdusers($idusersNewConv);
            if($conv)
            {
                $result['state'] =      'convExist';
                $result['conv'] =       $conv;
                $result['convid'] =     $conv->pk_idconversation;
                $result['convHtml'] =   ''; //filled after
                return $result;
            }
            else{
                //on crée une nouvelle conv
                $conv = $this->createConversation($idusersNewConv, false);
                $result['state'] =      'newConvCreated';
                $result['toAdd'] =      implode($idusersNewConv,',');
                $result['conv'] =       $conv;
                $result['convid'] =     $conv->pk_idconversation;
                $result['convHtml'] =   ''; //filled after
                return $result;
            }
        }

        //sinon (deja une conv de groupe)
        if($convType == 'groupConv')
        {
            //get all users of new convers in array
            $idusersNewConv = array_merge($idusers, $usersALreadyIn);

            $conv = $this->checkConvExistFromIdusers($idusersNewConv);
            
            if($conv)
            {
                $result['state'] =      'convExist';
                $result['conv'] =       $conv;
                $result['convid'] =     $conv->pk_idconversation;
                $result['convHtml'] =   ''; //filled after
                return $result;
            }
            else{
                $userNicknames = Functions::getUsersNicknameInArray($idusers);
                $stringToDisplay = '';
                foreach($userNicknames as $userNickname)
                {
                    $stringToDisplay .= $userNickname . ', ';
                }
                $stringToDisplay .= 'have been aded to conv';

                //check si la conv modifiée existe deja
                $result['state'] =      'todoAddUser';
                $result['toAdd'] =      implode($idusersToAdd,',');
                $result['conv'] =       null;
                $result['convid'] =     $idConversation;
                $result['text'] =       $stringToDisplay;
                return $result;
            }
        }
    }

    /**
     * Cette fonction retourne l'id de la conv si elle existe ou false
     * @param $idusers array(id) des users particpants a la conv dont on veux tester l'existence, l'id de l'user courant est un elem du tableau
     * @return mysqli_result conversation
     */
    public function checkConvExistFromIdusers($idusers)
    {
        //on add l'id de l'user courant au tableau
        $nbParticipants = sizeof($idusers);

        //recupère les convs auxquelle je participe et qui ont le meme nombre de participants que celle dont on veux tester l'existence
        $convs = $this->db->query("SELECT * FROM we__enregconversation JOIN we__conversation ON we__enregconversation.fk_idconversation = we__conversation.pk_idconversation WHERE we__enregconversation.fk_iduser = ? AND we__conversation.nbParticipants = ? AND typeConv = ? AND title IS ?", [
            $this->session->read('auth')->pk_iduser,
            $nbParticipants,
            $this->varGroupConv,
            NULL
        ])->fetchAll();

        if($convs)
        {
            $convFound = '';
            //pour chaques id conversation on compare les participants a la liste des users
            foreach($convs as $conv)
            {
                //on recupère les participant de la conv
                $idusersConv = $this->getIdParticipants($conv->pk_idconversation);

                //on sort les array pour les comparer ensuite
                $result = array_diff($idusersConv, $idusers);
                if(sizeof($result) == 0)
                {
                    $convFound = $conv;
                    break;
                }
            }
            return $convFound;
        }
        else{
            return false;
        }
    }

    /**
     * @param $iduser
     * @param $idConversation
     */
    public function removeUserFromConversation($iduser, $idConversation)
    {
        //get state of conv to delete before
        $stateConvBeforeDelete = $this->db->query("SELECT state FROM we__EnregConversation WHERE fk_iduser = ? AND fk_idconversation = ?", [
            $iduser,
            $idConversation
        ])->fetch();

        if($stateConvBeforeDelete)
        {
            //si la conv fesait partie des conv ouvertes ou minimized, on change l'etat d'une de ses conv grouped s'il en possède
            if($stateConvBeforeDelete->state == $this->varOpenConv || $stateConvBeforeDelete->state == $this->varMinimizedConv)
            {
                //si l'user a des conv grouped, on get la conv et on la passe en minimized
                $lastGroupedConv = $this->db->query("SELECT * FROM we__EnregConversation WHERE fk_iduser = ? AND state = ? ORDER BY dateLastModif ASC LIMIT 0,1", [
                    $iduser,
                    $this->varGroupedConv
                ])->fetch();

                if($lastGroupedConv)
                {
                    $this->db->query('UPDATE we__EnregConversation SET state = ?, dateLastModif = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                        $this->varMinimizedConv,
                        date("Y-m-d H:i:s"),
                        $lastGroupedConv->fk_idconversation,
                        $iduser
                    ]);
                }
            }
            //delete conv
            $this->db->query("DELETE FROM we__EnregConversation WHERE fk_iduser = ? AND fk_idconversation = ?", [
                $iduser,
                $idConversation
            ]);

            //update conv nbParticpants - 1
            $this->db->query('UPDATE we__Conversation SET nbParticipants = nbParticipants - 1 WHERE pk_idconversation = ?', [
                $idConversation
            ]);
        }
    }

    /**
     * Cette fonction permet de leave une conv (disponnible uniquement pour les conversations de groupe), checking du nb participants de la conv pour supprimer la conv si le nbParticipants est nul
     * @param $idconv
     */
    public function leaveConv($idconv)
    {
        //delete enregConv
        $this->db->query("DELETE FROM we__EnregConversation WHERE fk_iduser = ? AND fk_idconversation = ?", [
            $this->session->read('auth')->pk_iduser,
            $idconv
        ]);

        //update conv nbParticpants - 1
        $this->db->query('UPDATE we__Conversation SET nbParticipants = nbParticipants - 1 WHERE pk_idconversation = ?', [
            $idconv
        ]);

        //si conv nbParticipants == 0
        $conv = $this->getOnlyConversationFromId($idconv);
        if($conv->nbParticipants == 0)
        {
            //delete Conv
            $this->db->query("DELETE FROM we__Conversation WHERE pk_idconversation = ?", [
                $idconv
            ]);
        }
    }

    /**
     * @param $message
     * @param $idconversation
     */
    public function writeMessageInConversation($message, $idconversation)
    {
        $this->db->query("INSERT INTO we__Message SET fk_idconversation = ?, fk_iduser = ?, texte = ?, date", [
            $idconversation,
            $this->session->read('auth')->pk_iduser,
            $message
        ]);
    }

    /**
     * Cette fonction definie une 'dateDisplayed', si nescessaire, par rapport au dernier message de la conversation en fonction de l'idconversation
     * @param $idconversation
     * @return string donnant la plus grande unité d'interval entre les deux dates => heures, jours, semaine, mois, années
     */
    public function getDateDisplayed($idconversation)
    {
        //other method tu sub dates
        /*$date = $this->db->query("SELECT date FROM we__Message WHERE fk_idconversation = ? ORDER BY date ASC LIMIT 0,1",[
            Session::getInstance()->read('auth')->pk_iduser
        ])->fetch()->date;
        $dateLastMessage = new \DateTime($date);
        $dateNow = new \DateTime(date("Y-m-d H:i:s"));
        $datesub = $dateLastMessage->diff($dateNow);
        var_dump($datesub);*/

        //recupère le dernier message de la conversation
        $datePreviousMessage = $this->db->query("SELECT FIRST(date) FROM we__message WHERE fk_idconversation = ? ORDER BY date ASC",[
            $idconversation
        ])->fetch()->date;

        //calcul l'ecart en unité de temps de la date et de time()
        $hour =     60*60;
        $day =      $hour * 24;
        $week =     $day * 7;
        $month =    $day * 31;
        $year =     $month * 12;

        $timeFirst  = strtotime($datePreviousMessage);
        $timeSecond = strtotime(date("Y-m-d H:i:s"));
        $differenceInSeconds = $timeSecond - $timeFirst;

        $hours =    $differenceInSeconds/$hour;
        $days =     $differenceInSeconds/$day;
        $weeks =    $differenceInSeconds/$week;
        $months =   $differenceInSeconds/$month;

        $value =    $datePreviousMessage;
        $type =     '';
        if($hours >= 1 && $hours < 24)
        {
            $type =  "hours";
            return $this->getTraduceFromInterval($type, $value);
        }
        if($days >= 1 && $days < 7)
        {
            $type =  "days";
            return $this->getTraduceFromInterval($type, $value);
        }
        if($weeks >= 1 && $weeks < 5)
        {
            $type =  "weeks";
            return $this->getTraduceFromInterval($type, $value);
        }
        if($months >= 1 && $months < 12)
        {
            $type =  "months";
            return $this->getTraduceFromInterval($type, $value);
        }
        else{
            $type =  "years";
            return $this->getTraduceFromInterval($type, $value);
        }
    }

    /**
     * @param $type hours, days, weeks, months, years
     * @param $value date a partir de laquelle on definiele message a display
     * @return valeur a afficher par le message
     */
    public function getTraduceFromInterval($type, $value)
    {
        if($type == "hours")
        {

        }
    }

    public function changConversationName($newName, $idconversation)
    {
        $this->db->query('UPDATE we__Conversation SET title = ? WHERE pk_idconversation = ?', [
            $newName,
            $idconversation
        ]);
    }

    /**
     * change l'etat de la conversation
     * RAPPEL: l'etat de la conversation sert a definir sous quelle forme la chatBox s'affichera lor de la connection
     * pas ouverte (state == 0), ouvertes (state == 1), minimized (state == 2), grouped (state == 3)
     * @param $convid
     * @param $state
     */
    public function changeConversationState($state, $idconversation)
    {
        $this->db->query('UPDATE we__EnregConversation SET state = ?, dateLastModif = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
            $state,
            date("Y-m-d H:i:s"),
            $idconversation,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    /**
     * cette fonction retourne les conversations ouvertes (state == 1), minimized (state == 2), grouped (state == 3)
     * @return sql_conversation_join_enregconversation
     */
    public function getConversationChatBoxes()
    {
        $conversations = $this->db->query("SELECT * FROM we__Conversation JOIN we__EnregConversation ON we__Conversation.pk_idconversation = we__EnregConversation.fk_idconversation WHERE we__EnregConversation.fk_iduser = ? AND (state = ? OR state = ? OR state = ?) AND (we__Conversation.activated = ? OR we__Conversation.fk_iduser = ?) AND we__Conversation.nbParticipants != 0 ORDER BY we__EnregConversation.dateLastModif ASC",[
            $this->session->read('auth')->pk_iduser,
            1,
            2,
            3,
            1,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $conversations;
    }

    /**
     * cette fonction sert a savoir si une conversation entre deux user existe ou non: prend le titre de la convers qui est toujours egale a firstname "nickname" lastname ds le cas d'une conversation user to user
     * @param $userCompleteName
     * @return id de la conversation existante
     */
    public function conversExistBetweenTwoUsers($iduser)
    {
        //verifier si l'user a une conv commune et que cette conversation comune comporte bien 2 participants
        $conv = $this->db->query("SELECT * FROM we__enregconversation JOIN we__conversation ON we__enregconversation.fk_idconversation = we__conversation.pk_idconversation WHERE we__enregconversation.fk_iduser = ? AND we__conversation.typeConv = ? AND we__enregconversation.fk_idconversation IN (
                                  SELECT we__enregconversation.fk_idconversation FROM we__enregconversation WHERE we__enregconversation.fk_iduser = ?)", [
            $this->session->read('auth')->pk_iduser,
            $this->varUserToUserConv,
            $iduser
        ])->fetch();
        if($conv)
        {
            return $conv;
        }
        else{
            return false;
        }
    }

    /**
     * cette fonction sert a savoir si la conversation est ouverte ou non, ouverte = qu'elle s'affiche ds les chat boxes
     * @param $idconv
     */
    public function isConversationOpen($idconv)
    {
        $state = $this->db->query("SELECT state FROM we__EnregConversation WHERE fk_iduser = ? AND fk_idconversation = ?", [
            $this->session->read('auth')->pk_iduser,
            $idconv
        ])->fetch()->state;
        if($state == 1)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function getConversationState($idconv)
    {
        $state = $this->db->query("SELECT state FROM we__EnregConversation WHERE fk_iduser = ? AND fk_idconversation = ?", [
            $this->session->read('auth')->pk_iduser,
            $idconv
        ])->fetch()->state;
        return $state;
    }
    
    /**
     * return si la conversation existe
     * @param $idconv idconversation
     * @return bool, indiquant si la conversation existe (pour l'user courant)
     */
    public function isMyConv($idconv)
    {
        $found = $this->db->query("SELECT * FROM we__EnregConversation WHERE fk_iduser = ? AND fk_idconversation = ?", [
            $this->session->read('auth')->pk_iduser,
            $idconv
        ])->fetch();
        if($found)
        {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * retourne le nom complet de l'user a qui l'on s'adresse ds le cas d'une conversation user to user, car ds le cas d'une conv user to user le titre est a NULL car relatif
     * @param $idconv idconversation
     * @return string, $title nom complet de l'user a qui l'on s'adresse
     */
    public function getTitleForUsertoUserConv($idconv)
    {
        //get l'id de l'user
        $iduser = $this->db->query("SELECT fk_iduser FROM we__EnregConversation WHERE fk_iduser != ? AND fk_idconversation = ?", [
            $this->session->read('auth')->pk_iduser,
            $idconv
        ])->fetch()->fk_iduser;

        $completeName = Functions::getCompleteNameFromIduser($iduser);
        $maxLengthTitle =   22;

        if(strlen($completeName) > $maxLengthTitle)
        {
            $completeName = substr($completeName, 0, $maxLengthTitle) . '...';
        }

        //generate link profile
        $model = new UserModel();
        $user = $model->getUserFromId($iduser);
        $display = new displayUser($user);
        $title = $display->showUserProfileLinkForChatBox($completeName);

        return $title;
    }
    
    public function getTitleForGroupedConv($idconv = false, $idusers = array())
    {
        //si la conv possède deja un nom on retourne le titre
        $titleConv = $this->getConvTitle($idconv);
        if($titleConv)
        {
            return $titleConv;
        }
        //sinon on génèrte le titre
        else{
            if(empty($idusers))
            {
                $idusers = $this->getIdParticipants($idconv);
            }

            //get nickname array from idusers
            $arrayNicknames =   Functions::getUsersNicknameInArray($idusers);

            //si tout les users n'on pas été supprimés de la conv et que l'user courant n'est pas le seul a y etre résté
            if(sizeof($arrayNicknames) > 1)
            {
                //retir le pseudo de l'user courant de l'array des nicknames
                $arrayNicknames = array_diff($arrayNicknames, array(Session::getInstance()->read('auth')->nickname));
            }

            //reindex le tableau pour que sa numerotation commence bien a 0 et que le for ne genere pas d'erreur a cause de ca
            $arrayNicknames = array_values($arrayNicknames);

            $length = sizeof($arrayNicknames);

            //define parameters en fonction du nombre d'user a afficher
            $titleConv =        '';
            $maxLengthTitle =   21; //nombre de caractère max autotrisé ds la chaine de titre
            $remainChar =       $maxLengthTitle; //nombre de car restant avant coupure de la chaine
            $nickLengthMax =       ''; //taille max d'un nickname ds la chaine de titre
            if(sizeof($idusers) < 2)
            {
                $nickLengthMax = $maxLengthTitle;
            }
            if(sizeof($idusers) == 2)
            {
                $nickLengthMax = $maxLengthTitle / 2;
            }
            if(sizeof($idusers) == 3)
            {
                $nickLengthMax = 10;
            }
            if(sizeof($idusers) > 3)
            {
                $nickLengthMax = 7;
            }

            //$nicklenthTmp reporte les retenue des caractères non utilisé et est utilisé comme element de comparaison
            $nicklenthTmp = $nickLengthMax;
            for($i = 0; $i < $length; $i++)
            {
                $nickLength = strlen($arrayNicknames[$i]);

                //si le nickname est inferieur a la taille autorizée d'un nom
                if($nickLength <= $nicklenthTmp)
                {
                    $nicklenthTmp = $nickLengthMax + ($nicklenthTmp - $nickLength);

                    //update title
                    $titleConv .= $arrayNicknames[$i] . ', ';
                }
                //sinon on cut le nom
                else{
                    //cut du nom
                    $nicknameCuted = substr($arrayNicknames[$i], 0, $nicklenthTmp);

                    //update title
                    $titleConv .= str_replace(' ','',$nicknameCuted) .'., ';

                    //reset nickTmp
                    $nicklenthTmp = $nickLengthMax;
                }

                //on maj remain chars to display
                $remainChar = $remainChar - $nickLengthMax;

                //var_dump($remainChar);

                //si remain char
                if($remainChar <= 0)
                {
                    //si le dernier caractère est une virgule, l'enlever
                    if(substr($titleConv, -2) == ', ')
                    {
                        $titleConv = substr($titleConv, 0, strlen($titleConv) - 2);
                    }

                    //on rajoute le +X a la fin
                    if($length - $i > 0)
                    {
                        $titleConv .= " +" . ($length - $i);
                    }
                    break;
                }
            }

            //si le dernier caractère est une virgule, l'enlever
            if(substr($titleConv, -2) == ', ')
            {
                $titleConv = substr($titleConv, 0, strlen($titleConv) - 2);
            }
            //echo($titleConv);
            return $titleConv;
        }
    }

    public function getConvTitle($idconv)
    {
        $titleConv = $this->db->query("SELECT title FROM we__Conversation WHERE pk_idconversation = ?", [
            $idconv
        ])->fetch()->title;

        return $titleConv;
    }

    public function renameConv($idconv, $newName)
    {
        $this->db->query('UPDATE we__Conversation SET title = ? WHERE pk_idconversation = ?', [
            $newName,
            $idconv
        ]);
    }

    /**
     * Cette fonction retourn les ids des participants sous form d'array
     * @param $idconv
     * @return array
     */
    public function getIdParticipants($idconv, $removeCurrentUser = false)
    {
        if($removeCurrentUser)
        {
            $idusers = $this->db->query("SELECT fk_iduser FROM we__EnregConversation WHERE fk_idconversation = ? AND fk_iduser != ?", [
                $idconv,
                $this->session->read('auth')->pk_iduser
            ])->fetchAll();
        }
        else{
            $idusers = $this->db->query("SELECT fk_iduser FROM we__EnregConversation WHERE fk_idconversation = ?", [
                $idconv
            ])->fetchAll();
        }
        
        $arrayIdUsers = Functions::getArrayFromObjectProperty($idusers, 'fk_iduser');

        return $arrayIdUsers;
    }

    /**
     * Cette fonction retourne le tableau sql d'users participants
     * @param $idconv
     * @return array tablea d'users
     */
    public function getParticipants($idconv, $deleteCurrentUser = false)
    {
        if($deleteCurrentUser)
        {
            $users = $this->db->query("SELECT * FROM we__User WHERE pk_iduser IN (SELECT fk_iduser FROM we__EnregConversation WHERE fk_idconversation = ? AND fk_iduser != ?)", [
                $idconv,
                $this->session->read('auth')->pk_iduser
            ])->fetchAll();
        }
        else{
            $users = $this->db->query("SELECT * FROM we__User WHERE pk_iduser IN (SELECT fk_iduser FROM we__EnregConversation WHERE fk_idconversation = ?)", [
                $idconv
            ])->fetchAll();
        }

        return $users;
    }

    public function deleteConv($idconv)
    {
        $this->db->query('UPDATE we__EnregConversation SET state = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
            $this->varDeletedConv,
            $idconv,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    /**
     * @param $idconv
     * @return string:
     */
    public function getTypeConv($idconv)
    {
        $typeConv = $this->db->query("SELECT typeConv FROM we__Conversation WHERE pk_idconversation = ?", [
            $idconv
        ])->fetch();

        if($typeConv)
        {
            return $typeConv->typeConv;
        }
    }

    public function getNbParticipants($idconv = false)
    {
        $nbUsers = $this->db->query("SELECT nbParticipants FROM we__Conversation WHERE pk_idconversation = ?", [
            $idconv
        ])->fetch()->nbParticipants;

        return (int)$nbUsers;
    }

    /**
     * @param $idusers array : id des users a rajouter a la conversation et dont il faut affiche les nicknames
     * @return $msg string : message a afficher pour confirmer l'ajout des users
     */
    public function getMessageUserAded($idusers)
    {
        
    }

    /**
     * Cette fonction recupère les derniers messages des convs
     * calcul le nb de notifs
     * @return array
     */
    public function getNotifArrayChatBoxes()
    {
        //get les dates consultetAt des conv pour definir le moment a partir duquel on va afficher les messages
        $enregConversations = $this->db->query("SELECT * FROM we__EnregConversation WHERE fk_iduser = ? AND state != ?", [
            $this->session->read('auth')->pk_iduser,
            $this->varClosedConv
        ])->fetchAll();

        //get les messages
        $result = array();
        foreach ($enregConversations as $enregConversation)
        {
            //get les messages de chaques convs
            $messages = $this->db->query("SELECT * FROM we__message WHERE fk_idconversation = ? ORDER BY date DESC LIMIT 0,$this->nbMessagesToOpen", [
                $enregConversation->fk_idconversation
            ])->fetchAll();

            //si il y a des messages
            if($messages)
            {
                //get les 30 derniers messages de la conv
                $messages = $this->db->query("SELECT * FROM we__message WHERE fk_idconversation = ? ORDER BY date DESC LIMIT 0,$this->nbMessagesToOpen", [
                    $enregConversation->fk_idconversation
                ])->fetchAll();

                //get le nb de messages non lu depuis la dernière consultation de la conv
                $nbNotifs = $this->db->query("SELECT COUNT(*) as nbNotifs FROM we__message WHERE fk_idconversation = ? AND date > ? AND fk_iduser != ? ORDER BY date DESC", [
                    $enregConversation->fk_idconversation,
                    $enregConversation->consultedAt,
                    $this->session->read('auth')->pk_iduser
                ])->fetch()->nbNotifs;

                //get la date du message affiché le plsu recent pour reprendre l'affichage a partir de celle ci
                $dateLastMessageDisplayed = $messages[0]->date;

                //reclassement des messages ds un ordre croissant de date
                usort($messages, function($a, $b)
                {
                    return strtotime($a->date) - strtotime($b->date);
                });

                array_push($result, array(
                        'idConv' => $enregConversation->fk_idconversation,
                        'messages' => $messages,
                        'dateLastMess' => $dateLastMessageDisplayed,
                        'nbNotifs' => $nbNotifs
                    )
                );
            }
        }
        return $result;
    }
    
    /**
     *
     * Cette fonction recupère les messages d'une conv dont la date est au dela de celle d'une date idiquée
     * @param $dateLastMessageDisplayed, date du dernier message affiché ds la conversation OU =null quand la conversation est a chargé a partir de 0 (cas d'un user qui viens de vous ecrire pour la première fois)
     * @return array
     */
    public function getNotifArrayChatBoxe($idconv, $dateLastMessageDisplayed)
    {
        //definis la date d'origine des messages a recuperer
        if($dateLastMessageDisplayed)
        {
            //get les X messages depuis la date du dernier message affiché
            $messages = $this->db->query("SELECT * FROM we__message WHERE date > ? AND fk_idconversation = ? ORDER BY date DESC LIMIT 0,$this->nbMessagesToOpen", [
                $dateLastMessageDisplayed,
                $idconv
            ])->fetchAll();
        }
        else{
            //echo($idconv);
            //echo('its ok');
            //get les X derniers messages de la conv
            $messages = $this->db->query("SELECT * FROM we__message WHERE fk_idconversation = ? ORDER BY date DESC LIMIT 0,$this->nbMessagesToOpen", [
                $idconv
            ])->fetchAll();
        }

        //si il y a des messages
        if(!empty($messages))
        {
            $enregConv = $this->getEnregConvFromId($idconv);
            
            //get le nb de messages non lu depuis la dernière consultation de la conv
            $nbNotifs = $this->db->query("SELECT COUNT(*) as nbNotifs FROM we__message WHERE fk_idconversation = ? AND date > ? AND fk_iduser != ? ORDER BY date DESC", [
                $idconv,
                $enregConv->consultedAt,
                $this->session->read('auth')->pk_iduser
            ])->fetch()->nbNotifs;

            //get la date du message affiché le plsu recent pour reprendre l'affichage a partir de celle ci
            $dateLastMessageDisplayed = $messages[0]->date;

            //reclassement des messages ds un ordre croissant de date
            usort($messages, function($a, $b)
            {
                return strtotime($a->date) - strtotime($b->date);
            });

            $result = array(
                        'idConv' => $idconv,
                        'messages' => $messages,
                        'dateLastMess' => $dateLastMessageDisplayed,
                        'nbNotifs' => $nbNotifs
                    );
        }
        else{
            $result = array(
                        'idConv' => $idconv,
                        'messages' => array(),
                        'dateLastMess' => $dateLastMessageDisplayed,
                        'nbNotifs' => 0
                    );
        }
        return $result;
    }

    public function getConvMessages($idconv, $dateLastMessageDisplayed = false)
    {
        if($dateLastMessageDisplayed)
        {
            $messages = $this->db->query("SELECT * FROM we__message WHERE date > ? AND fk_idconversation = ? ORDER BY date DESC LIMIT 0,$this->nbMessagesToOpen", [
                $dateLastMessageDisplayed,
                $idconv
            ])->fetchAll();
        }
        else{
            $messages = $this->db->query("SELECT * FROM we__message WHERE fk_idconversation = ? ORDER BY date DESC LIMIT 0,$this->nbMessagesToOpen", [
                $idconv
            ])->fetchAll();
        }

        //reclasser les messages ds un ordre croissant
        if(!empty($messages))
        {
            //reclassement des messages ds un ordre croissant de date
            usort($messages, function($a, $b)
            {
                return strtotime($a->date) - strtotime($b->date);
            });
            $result['messages'] = $messages;
        }
        else{
            $result = array();
        }
        return $result;
    }

    /**
     * Cette fonction update la valeur consultedAt de enregConversation si la conv est activée
     * @param $idconv
     */
    public function readConv($idconv)
    {
        $this->db->query('UPDATE we__EnregConversation SET consultedAt = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
            date("Y-m-d H:i:s"),
            $idconv,
            $this->session->read('auth')->pk_iduser
        ]);
    }

    public function enregMessage($idconv, $message)
    {
        //active conversation if nescessary
        $conv = $this->getOnlyConversationFromId($idconv);
        if($conv->activated == 0)
        {
            //activate conv
            $this->activateConv($idconv);
            //put open conv for users putOpenConvForUsers
            //$this->activateConvForUsers($idconv);
        }

        //enreg message in DB
        $this->db->query("INSERT INTO we__Message SET fk_idconversation = ?, fk_iduser = ?, texte = ?, date = ?", [
            $idconv,
            $this->session->read('auth')->pk_iduser,
            $message,
            date("Y-m-d H:i:s")
        ]);

        //update dateLastMess conv
        $this->db->query('UPDATE we__Conversation SET dateLastMess = ? WHERE pk_idconversation = ?', [
            date("Y-m-d H:i:s"),
            $idconv
        ]);

        //readConv
        $this->readConv($idconv);
    }

    /**
     * Cette fonction active la conversation (champ activated => 1)
     * @param $idconv
     */
    public function activateConv($idconv)
    {
        //activer conv
        $this->db->query('UPDATE we__Conversation SET activated = ? WHERE pk_idconversation = ?', [
            1,
            $idconv
        ]);
    }

    /**
     * Cette fonction active
     * @param $idusers
     * @param $idconv
     */
    public function activateConvForUsers($idconv)
    {
        //activer conv pour tout les participants
        $idusers = $this->getIdParticipants($idconv);
        //retirer l'user courant de l'array
        $idusers = array_diff($idusers, array($this->session->read('auth')->pk_iduser));
        foreach($idusers as $iduser)
        {
            //si l'user a des conv ouvertes
            $nbConvOpened = $this->db->query("SELECT COUNT(*) as nbConvOpen FROM we__EnregConversation WHERE fk_iduser = ? AND state != ? AND state != ?", [
                $iduser,
                $this->varClosedConv,
                $this->varGroupedConv
            ])->fetch()->nbConvOpen;
            
            //si le nb de conv ouvertes est maximum, basculer la dernière conv ouverte en grouped et open la conv juste activée
            if($nbConvOpened == $this->nbMaxConvOpen)
            {
                //get last conv open
                $lastConvOpen = $this->db->query("SELECT fk_idconversation FROM we__EnregConversation WHERE fk_iduser = ? AND (state = ? OR state = ?) ORDER BY dateLastModif ASC LIMIT 0,1", [
                    $iduser,
                    $this->varMinimizedConv,
                    $this->varOpenConv
                ])->fetch();

                //put conv in grouped convs
                $this->db->query('UPDATE we__EnregConversation SET state = ?, dateLastModif = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                    $this->varGroupedConv,
                    date("Y-m-d H:i:s"),
                    $lastConvOpen->fk_idconversation,
                    $iduser
                ]);
                //open conv for user
                $this->db->query('UPDATE we__EnregConversation SET state = ?, dateLastModif = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                    $this->varOpenConv,
                    date("Y-m-d H:i:s"),
                    $idconv,
                    $iduser
                ]);
            }
            //sinon juste open la conv juste activée
            else{
                //open conv for user
                $this->db->query('UPDATE we__EnregConversation SET state = ?, dateLastModif = ? WHERE fk_idconversation = ? AND fk_iduser = ?', [
                    $this->varOpenConv,
                    date("Y-m-d H:i:s"),
                    $idconv,
                    $iduser
                ]);
            }
        }
    }

    /**
     * Cette fonction check si l'user fait bien partie de la conv
     * @param $iduser
     * @param $idconv
     * @return bool
     */
    public function isUserInConv($iduser, $idconv)
    {
        //get last conv open
        $conv = $this->db->query("SELECT * FROM we__EnregConversation WHERE fk_iduser = ? AND fk_idconversation = ?", [
            $iduser,
            $idconv
        ])->fetch();
        if($conv)
        {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Cette fonction check si la conv est activée ou non
     * @param $idconv
     * @return bool
     */
    public function isConvIsActivated($idconv)
    {
        //get last conv open
        $activated = $this->db->query("SELECT activated FROM we__Conversation WHERE pk_idconversation = ?", [
            $idconv
        ])->fetch()->activated;
        if($activated == 0)
        {
            return false;
        }
        else{
            return true;
        }
    }


    //#inutile pour le moment
    public function getReadedFromIdConv($idconv)
    {
        $readedConvEntry = $this->db->query("SELECT * FROM we__ReadedBy WHERE fk_idconversation = ? AND fk_iduser = ?", [
            $idconv,
            $this->session->read('autg')->pk_iduser
        ])->fetch();

        return $readedConvEntry;
    }

    //get les con
    public function getUnreadedConvs()
    {
        /*$unreadedConvs = $this->db->query("SELECT * FROM we__enregconversation JOIN we__conversation ON we__enregconversation.fk_idconversation = we__conversation.pk_idconversation WHERE we__enregconversation.fk_iduser = ? AND we__conversation.state = ? AND we__enregconversation.consultedAt < we__conversation.dateLastMess",[
            $this->session->read('autg')->pk_iduser,
            $this->varClosedConv
        ])->fetchAll();*/
        
        $unreadedConvs = $this->db->query("SELECT * FROM we__enregconversation JOIN we__conversation ON we__enregconversation.fk_idconversation = we__conversation.pk_idconversation WHERE we__enregconversation.fk_iduser = ? AND we__enregconversation.consultedAt < we__conversation.dateLastMess",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        
        return $unreadedConvs;
    }
}