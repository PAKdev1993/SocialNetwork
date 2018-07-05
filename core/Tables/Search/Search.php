<?php

namespace core\Tables\Search;

use app\App;
use app\Table\Messages\Conversations\ConversationModel;
use core\Functions;
use core\Session\Session;


class Search
{
    private $db;
    private $session;

    private $filters = []; //Tableau contenant le nom des filtres

    private $results = []; //Tableau de results, le type de resultats est stocké dans la propriété 'type' du result: ex pour des resultats user: $results[x]->type == 'user'

    private $stringToSearch;

    private $from; //Indique l'endroit d'ou on chercher, sert a determiner le nombre de resultats:  = SearchBarHeader : 4 resultats || SearchBar : 50 resultats

    private $begin;
    private $end;

    public function __construct($filters, $stringToSearch, $from = false, $begin = false, $nbResults = false)
    {
        $this->db =         App::getDatabase();
        $this->session =    Session::getInstance();

        $this->filters =    $filters;
        $this->stringToSearch = Functions::secureVarSQL($stringToSearch);

        $this->from = $from;

        $this->begin = $begin;
        $this->end = $nbResults;
    }

    /**
     * @param array $params tableau associatif contenant ds variales additionnelles a passer aux fonction de recherche par filtre
     * @return array
     */
    public function search($params = array())
    {
        foreach($this->filters as $filter)
        {
            switch ($filter['filterName'])
            {
                case 'users':
                    if(!empty($params))
                    {

                    }
                    else {
                        $results = $this->searchUsers();
                        $this->results['users'] = $results;
                    }
                    break;
                case 'friends':
                    if($params['convid'])
                    {
                        $results = $this->searchFriendsToAddToConv($params['convid'], $filter['idToExlude']);
                        $this->results['friends'] = $results;
                    }
                    else{
                        $results = $this->searchFriends();
                        $this->results = array_merge($this->results, $results);
                    }
                    break;
                case 'convs':
                    $results = $this->searchConvs();
                    $this->results['convs'] = $results;
                    break;
            }
        }
        return $this->results;
    }

    public function searchUsers()
    {
        $users = $this->db->query("SELECT * FROM we__user as users LEFT JOIN we__quickinfos as qi ON users.pk_iduser = qi.fk_iduser WHERE users.firstname LIKE '%" . $this->stringToSearch ."%' OR users.lastname LIKE '%". $this->stringToSearch ."%' OR users.nickname LIKE '%". $this->stringToSearch ."%' LIMIT $this->begin,$this->end",[
        ])->fetchAll();
        if($users)
        {
            $mainUserId = App::getMainUser()->pk_iduser;
            foreach($users as $key => $user)
            {
                //permet de retirer le main user des recherches
                if($user->pk_iduser == $mainUserId) unset($users[$key]);

                //change le type du resultat pour pouvoir par la suite les classer
                $user->typeResult = 'user';
            }
            return $users;
        }
        else{
            return [];
        }
    }

    public function searchFriendsToAddToConv($idconv, $userAlredyAdded = array())
    {
        //recupère les id des participant a la conversation pour le pas les afficher ds les resultats de la recherche
        $model = new ConversationModel();
        $idusers = $model->getIdParticipants($idconv);

        //merge les array d'id a ne pas rechercher
        $userAlrdyAdded =   array_merge($userAlredyAdded, $idusers);
        $userAlrdyAdded =   "(" . implode(',',$userAlrdyAdded) .")";

        //important pour eviter les cas de (,ID) etc
        $userAlrdyAdded =   str_replace('(,','(',$userAlrdyAdded);

        //get tout mes users amis qui ne m'ont pas bloqué ds un tableau
        $friends = $this->db->query("SELECT * FROM we__Contacts as contacts JOIN we__User as users ON contacts.id_contact = users.pk_iduser WHERE contacts.fk_iduser = ? AND (users.firstname LIKE '%" . $this->stringToSearch ."%' OR users.lastname LIKE '%". $this->stringToSearch ."%' OR users.nickname LIKE '%". $this->stringToSearch ."%') AND users.pk_iduser NOT IN $userAlrdyAdded AND users.pk_iduser NOT IN(
                                     SELECT fk_iduser FROM we__usersblocked WHERE id_userBlocked = ?) LIMIT $this->begin,$this->end",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        if($friends)
        {
            return $friends;
        }
        else{
            return array();
        }
    }

    public function searchConvs()
    {
        $convs = $this->db->query("SELECT * FROM we__enregConversation LEFT JOIN we__user ON we__enregConversation.fk_iduser = we__user.pk_iduser WHERE we__enregConversation.fk_iduser = ? AND we__user.firstname LIKE '%" . $this->stringToSearch ."%' OR we__user.lastname LIKE '%". $this->stringToSearch ."%' OR we__user.nickname LIKE '%". $this->stringToSearch ."%' LIMIT $this->begin,$this->end",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        if($convs)
        {
            return $convs;
        }
        else{
            return array();
        }
    }
}