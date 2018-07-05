<?php

namespace core\Tables\RecommendedContacts;

use app\App;
use app\Table\UserModel\UserModel;
use core\Session\Session;
use core\Functions;

class MyRecommendedContacts
{
    private $db;
    private $session;

    private $recommendedContacts;

    //CRITERES DE DANS L'ORDRE D'IMPORTANCE
    private $filtersArray = array(
            0 => 'current_team',
            1 => 'current_comp',
            2 => 'prev_team',
            3 => 'prev_comp',
            4 => 'events.games',
            5 => 'events.company',
            6 => 'event',
            7 => 'games.nationnality',
            8 => 'games',
            9 => 'nationnality'
    );
    private $useridArray;

    //CURRENT PAGE TO DISPLAY RESULT
    private $page;

    //NB RESULT CHOOSEN
    private $nbResultsWanted;

    //INTERMEDIARY VARS
    private $remainToFound;
    private $idAlreadyFound;
    private $loopCount;
    private $maxLoop;

    //STARTS ID
    private $Marcid;
    private $Alexid;
    private $PAKid;
    private $Mydid;

    public function __construct($page)
    {
        $this->db =                     App::getDatabase();
        $this->session =                Session::getInstance();
        $this->recommendedContacts =    [];
        $this->currentUser =            $this->session->read('auth');

        //VARS USED FOR SIZE OF ARRAY DEPEND ON THE PAGE WHERE DISPLAYED THE RESULT
        $this->page = $page;
        if($page == 'Community' || $page == 'Home' || $page == 'ProfileEmployee' || $page == 'ProfileGamer' || $page == 'ProfileViews' || $page == 'Notifications' || $page == 'Permalink' || $page == 'ComingSoon' || $page == 'SearchPage')
        {
            $this->nbResultsWanted =    3;
        }
        if($page == 'MyRecommendedContacts')
        {
            $this->nbResultsWanted =    50;
        }

        //INIT VARS
        $this->remainToFound = $this->nbResultsWanted;

        $model =                    new UserModel();
        $this->mycontacts =         $model->getMyContactsIdsInArray();
        $this->mypending =          $model->getMyContactsPendingInArray();
        $this->idAlreadyFound =     [];
        $this->idAlreadyFound =     array_merge($this->idAlreadyFound, $this->mycontacts, $this->mypending); array_push($this->idAlreadyFound, App::getMainUser()->pk_iduser);
        $this->loopCount =          1;
        $this->maxLoop =            sizeof($this->filtersArray);

        //PREPARE DATA TO SORT ARRAY
        $this->useridArray = [];

        //STARTS IDS
        $this->Marcid = 28;
        $this->Alexid = 30;
        $this->PAKid =  36;
        $this->Mydid =  38;
    }

    /*
     * ORDRE DE TRI
     * 1) same current company
     * 2) one of the same previous comapny
     * 3) same esport event
     * 4) same game + same nationnlity
     * 5) same game
     * 6) same nationnality
     */
    /**
     * @param bool $filter nom du filtre par lequel on veut debuter la recherche, la suite s'effectue de facon ordonnée par les index du tableau de critère
     * @param int $page nom de la page sur laquelle va etre
     * @param int $remainToFound nombre de resultat qu'il reste a trouver
     * @return array
     */
    public function getMyRecommendedContacts($filter = false)
    {
        if($this->currentUser->pk_iduser == 1)
        {
            return []; //#todo CHANGER CA, permet au master acount de ne pas buguer
        }

        //tant qu'il reste des contacts a trouver on filtre avec le filtre suivant
        while($this->remainToFound >= 0 && $this->loopCount <= $this->maxLoop)
        {
            switch($filter){
                case 'current_comp':
                    $idsObj = $this->filterCurrentComp();
                    break;
                case 'prev_team':
                    $idsObj = $this->filterPreviousTeams();
                    break;
                case 'prev_comp':
                    $idsObj = $this->filterPrevComp();
                    break;
                case 'events.games':
                    $idsObj = $this->filterEventsGames();
                    break;
                case 'events.company':
                    $idsObj = $this->filterEventsComp();
                    break;
                case 'event':
                    $idsObj = $this->filterEvents();
                    break;
                case 'games.nationnality':
                    $idsObj = $this->filterGameNationnality();
                    break;
                case 'games':
                    $idsObj = $this->filterGames();
                    break;
                case 'nationnality':
                    $idsObj = $this->filterNationnality();
                    break;
                default:
                    $idsObj = $this->filterCurrentTeam();
                    $filter = 'current_team';
                    break;
            }

            //traiter les resultat du filtre
            if($idsObj)
            {
                //transform results in array 1 dimension
                $results = Functions::getArrayFromObjectProperty($idsObj, 'fk_iduser');

                //update remain to found
                $this->updateRemainToFound($results);

                $sizeOfResult = sizeof($results);
                for($i = 0; $i < $sizeOfResult; $i++)
                {
                    $this->useridArray[$filter][$i] = $results[$i];
                }
            }

            //passer au filtre suivant
            $filter = $filter ? $filter : $this->filtersArray[0];
            $this->loopCount = $this->loopCount + 1;
            $newfilter = $this->nextFilter($filter);
            $this->getMyRecommendedContacts($newfilter);
        }
        if(sizeof($this->useridArray) == 0)
        {
            $this->addStartsIdsToRecommendedContacts();
        }
        return $this->useridArray;
    }

    //OK //#todo on peux JOIN la table sur elle meme aussi, voir lequel est le plus optimisé
    public function filterCurrentTeam()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        //get fk iduser qui nous interressent et ne sont pas dans l'array d'id deja trouvé
        $idsObj = $this->db->query("
                SELECT DISTINCT fk_iduser 
                FROM we__quickinfos 
                WHERE current_team LIKE CONCAT('%', (SELECT current_team FROM we__quickinfos WHERE fk_iduser = ?), '%') 
                AND fk_iduser != ? 
                AND fk_iduser NOT IN ($idsAlreadyFound)
                LIMIT 0,$this->remainToFound",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK //#todo on peux JOIN la table sur elle meme aussi, voir lequel est le plus optimisé
    public function filterCurrentComp()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        //get fk iduser qui nous interressent et ne sont pas dans l'array d'id deja trouvé
        $idsObj = $this->db->query("
                SELECT DISTINCT fk_iduser 
                FROM we__quickinfos 
                WHERE current_company LIKE CONCAT('%', (SELECT current_company FROM we__quickinfos WHERE fk_iduser = ?), '%') 
                AND fk_iduser != ? 
                AND fk_iduser NOT IN ($idsAlreadyFound) 
                LIMIT 0,$this->remainToFound",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK
    public function filterPreviousTeams()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        $idsObj = $this->db->query("
                  SELECT DISTINCT t2.fk_iduser 
                  FROM we__gamercareerteam as t1 
                  LEFT JOIN 
                  we__gamercareerteam as t2 
                  ON t2.name LIKE CONCAT('%',t1.name,'%') 
                  WHERE t1.fk_iduser = ? 
                  AND t2.fk_iduser != ? 
                  AND t2.fk_iduser NOT IN ($idsAlreadyFound)
                  LIMIT 0,$this->remainToFound", [
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK
    public function filterPrevComp()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        $idsObj = $this->db->query("
                  SELECT DISTINCT t2.fk_iduser 
                  FROM we__employeecareercompany as t1 
                  LEFT JOIN 
                  we__employeecareercompany as t2 
                  ON t2.name LIKE CONCAT('%',t1.name,'%') 
                  WHERE t1.fk_iduser = ? 
                  AND t2.fk_iduser != ?
                  AND t2.fk_iduser NOT IN ($idsAlreadyFound)
                  LIMIT 0,$this->remainToFound", [
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK (peut etre a retester mais fonctionne normalment car calquée sur filterEvent comp qui elle fonctionne)
    public function filterEventsGames()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        $idsObj = $this->db->query("
                  SELECT DISTINCT t2.fk_iduser
                  FROM we__gamercareerevent as t1 
                  LEFT JOIN we__gamercareerevent as t2 
                  ON t2.name LIKE CONCAT('%',t1.name,'%')
                  AND t1.game LIKE CONCAT('%',t2.game,'%')
                  WHERE t1.fk_iduser = ? 
                  AND t2.fk_iduser != ?    
                  AND t2.fk_iduser NOT IN ($idsAlreadyFound)
                  LIMIT 0,$this->remainToFound", [
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK
    public function filterEventsComp()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        $idsObj = $this->db->query("
                  SELECT DISTINCT t2.fk_iduser
                  FROM we__employeecareerevent as t1 
                  LEFT JOIN we__employeecareerevent as t2 
                  ON t2.name LIKE CONCAT('%',t1.name,'%')
                  AND t1.company LIKE CONCAT('%',t2.company,'%')
                  WHERE t1.fk_iduser = ? 
                  AND t2.fk_iduser != ?    
                  AND t2.fk_iduser NOT IN ($idsAlreadyFound)
                  LIMIT 0,$this->remainToFound",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK
    public function filterEvents()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        //get fk iduser qui nous interressent et ne sont pas dans l'array d'id deja trouvé
        $idsObj = $this->db->query("
                SELECT DISTINCT t2.fk_iduser
                FROM we__gamercareerevent as t1 
                LEFT JOIN we__gamercareerevent as t2 
                ON t2.name LIKE CONCAT('%',t1.name,'%')                
                WHERE t1.fk_iduser = ? 
                AND t2.fk_iduser != ?    
                AND t2.fk_iduser NOT IN ($idsAlreadyFound) 
                LIMIT 0,$this->remainToFound",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK
    public function filterGameNationnality()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        $idsObj = $this->db->query("
                      SELECT fk_iduser 
                      FROM we__quickinfos 
                      WHERE nationnality = 
                            (SELECT nationnality 
                             FROM we__quickinfos 
                             WHERE fk_iduser = ?) 
                             AND fk_iduser IN 
                                  (SELECT fk_iduser 
                                   FROM 
                                    (SELECT DISTINCT name 
                                     FROM we__gamercareergames 
                                     WHERE fk_iduser = ?) as t1 
                                            LEFT JOIN we__gamercareergames as t2 
                                            ON t2.name LIKE CONCAT('%',t1.name,'%')
                                            WHERE t2.fk_iduser != ?)
                      AND fk_iduser NOT IN($idsAlreadyFound)
                                            LIMIT 0,$this->remainToFound", [
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK
    public function filterGames()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        //get fk iduser qui nous interressent et ne sont pas dans l'array d'id deja trouvé
        $idsObj = $this->db->query("
                SELECT DISTINCT t2.fk_iduser
                FROM we__gamercareergames as t1 
                LEFT JOIN we__gamercareergames as t2 
                ON t2.name LIKE CONCAT('%',t1.name,'%')                
                WHERE t1.fk_iduser = ? 
                AND t2.fk_iduser != ?    
                AND t2.fk_iduser NOT IN ($idsAlreadyFound) 
                LIMIT 0,$this->remainToFound",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        return $idsObj;
    }

    //OK //#todo on peux JOIN la table sur elle meme aussi, voir lequel est le plus optimisé
    public function filterNationnality()
    {
        $idsAlreadyFound = implode(", ", $this->idAlreadyFound);
        //get fk iduser qui nous interressent et ne sont pas dans l'array d'id deja trouvé
        $idsObj = $this->db->query("
                SELECT DISTINCT fk_iduser 
                FROM we__quickinfos 
                WHERE nationnality LIKE CONCAT('%', (SELECT nationnality FROM we__quickinfos WHERE fk_iduser = ?), '%') 
                AND fk_iduser != ? 
                AND fk_iduser NOT IN ($idsAlreadyFound) 
                LIMIT 0,$this->remainToFound",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        //incrementer l'array des idsuser trouvé lor du filtre afin de na pas les séléctionner de nouveau
        $this->addIdsToListAlreadyFound($idsObj);

        //return
        //var_dump($idsObj);
        //die('ok');
        return $idsObj;
    }

    /*
     * incrémente la liste d'id already found
     */
    public function addIdsToListAlreadyFound($idsObj)
    {
        if($idsObj)
        {
            foreach($idsObj as $idObj)
            {
                array_push($this->idAlreadyFound, $idObj->fk_iduser);
            }
        }
    }

    /*
     * selectione le prochaine filtre dans la liste ordonnée d'importance
     */
    public function nextFilter($filter)
    {
        $indexFilter = array_search($filter, $this->filtersArray);
        $nextIndex = $indexFilter + 1;
        if($nextIndex == sizeof($this->filtersArray))
        {
            return false;
        }
        else{
            return $this->filtersArray[$nextIndex];
        }
    }

    /*
     * retourne le nombre d'elements a copier en foncion de ramin to found et met a jour ce dernier
     */
    public function updateRemainToFound($results)
    {
        $nbElems = sizeof($results);
        if($nbElems < $this->remainToFound)
        {
            $this->remainToFound = $this->remainToFound - $nbElems;
        }
        else{
            $this->remainToFound = 0;
        }
    }


    /**
     * fonction qui rajoute la team worldesport aux recommended contacts ds le cas ou l'user n'en a aucun
     */
    public function addStartsIdsToRecommendedContacts()
    {
        $myid = $this->session->read('auth')->pk_iduser;

        $arrayContactsAndPending = array_merge($this->mycontacts, $this->mypending);
        //retire les ids ds le cas ou l'user et un membre de la team worldesport
        switch($myid){
            case $this->Marcid:
                if(!in_array($this->Alexid, $arrayContactsAndPending)) $this->useridArray['WEteam'][0] = $this->Alexid;
                if(!in_array($this->PAKid, $arrayContactsAndPending)) $this->useridArray['WEteam'][1] = $this->PAKid;
                if(!in_array($this->Mydid, $arrayContactsAndPending)) $this->useridArray['WEteam'][2] = $this->Mydid;
                break;
            case $this->Alexid:
                if(!in_array($this->Marcid, $arrayContactsAndPending)) $this->useridArray['WEteam'][0] = $this->Marcid;
                if(!in_array($this->PAKid, $arrayContactsAndPending)) $this->useridArray['WEteam'][1] = $this->PAKid;
                if(!in_array($this->Mydid, $arrayContactsAndPending)) $this->useridArray['WEteam'][2] = $this->Mydid;
                break;
            case $this->PAKid:
                if(!in_array($this->Marcid, $arrayContactsAndPending)) $this->useridArray['WEteam'][0] = $this->Marcid;
                if(!in_array($this->Alexid, $arrayContactsAndPending)) $this->useridArray['WEteam'][1] = $this->Alexid;
                if(!in_array($this->Mydid, $arrayContactsAndPending)) $this->useridArray['WEteam'][2] = $this->Mydid;
                break;
            case $this->Mydid:
                if(!in_array($this->Marcid, $arrayContactsAndPending)) $this->useridArray['WEteam'][0] = $this->Marcid;
                if(!in_array($this->Alexid, $arrayContactsAndPending)) $this->useridArray['WEteam'][1] = $this->Alexid;
                if(!in_array($this->PAKid, $arrayContactsAndPending)) $this->useridArray['WEteam'][2] = $this->PAKid;
                break;
            default:
                if(!in_array($this->Marcid, $arrayContactsAndPending)) $this->useridArray['WEteam'][0] = $this->Marcid;
                if(!in_array($this->Alexid, $arrayContactsAndPending)) $this->useridArray['WEteam'][1] = $this->Alexid;
                if(!in_array($this->PAKid, $arrayContactsAndPending)) $this->useridArray['WEteam'][2] = $this->PAKid;
                if(!in_array($this->Mydid, $arrayContactsAndPending)) $this->useridArray['WEteam'][3] = $this->Mydid;
                break;
        }
    }

}
