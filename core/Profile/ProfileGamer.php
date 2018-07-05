<?php

namespace core\Profile;

use app\Table\Live\displayLives;
use app\Table\Live\LiveModel;
use app\Table\MyCommunity\MyCommunityModel;
use app\Table\Profile\Quickinfos\QuickInfosModel;
use app\Table\Profile\Quickinfos\displayQuickinfos;

use app\Table\Profile\Career\CareerModel;
use app\Table\Profile\Career\displayCareer;

use app\Table\Profile\Events\EventModel;
use app\Table\Profile\Events\displayEvents;

use app\Table\Profile\Games\GamesModel;
use app\Table\Profile\Games\displayGames;

use app\Table\Profile\Equipments\EquipmentModel;
use app\Table\Profile\Equipments\displayEquipments;

use app\Table\UserModel\displayUser;

#todo decharger cette classe
class ProfileGamer extends AppProfile
{
    private $profilePart;

    public function __construct($user = false)
    {
        parent::__construct($user);
        $this->profilePart = 'gamer';
    }
    
    public function getProfilePart()
    {
        return $this->profilePart;
    }

    /****************************************************************************\
     *                          MY PROFILE PART                                 *
    \****************************************************************************/
    public function getMyQuickInfos()
    {
        $model = new QuickInfosModel();
        $quickinfosObj = $model->getMyQuickInfos();
        $display = new displayQuickinfos($quickinfosObj, $this->profilePart);
        return $display->show();
    }

    public function getMySummary()
    {
        $display = new displayUser($this->myself, 'gamer.summary');
        return $display->show();
    }
    
    public function getMyCareer()
    {
        //get career
        $model = new CareerModel();
        $teamsObj = $model->getMyGamerCareer();
        $display = new displayCareer($teamsObj, false);
        $careerContent = $display->showGamerCareer();

        //write ids dans session pour les controle d'edition
        $idArray = $this->getArrayTeamsIdFromDBobj($teamsObj);
        $this->updateTeamsDisplayed($idArray);

        //retour
        return $careerContent;
    }

    public function getMyEvents()
    {
        //get events
        $model = new EventModel();
        $eventsObj = $model->getMyEvents();
        $display = new displayEvents($eventsObj, false);
        $eventContent = $display->showGamerEvents();
        //write ids dans session pour les controle d'edition
        $idsArray = $this->getArrayEventsIdFromDBobj($eventsObj);
        $this->updateEventsDisplayed($idsArray);

        //retour
        return $eventContent;
    }
    
    public function getMyGames()
    {
        //get games
        $model = new GamesModel();
        $gamesObj = $model->getMyGames();
        $display = new displayGames($gamesObj);

        //write ids dans session pour les controle d'edition
        $idsArray = $this->getArrayGamesIdFromDBobj($gamesObj);
        $this->updateGamesDisplayed($idsArray);

        //retour
        return $display->show();
    }

    public function getMyEquipment()
    {
        //get equipments
        $model = new EquipmentModel();
        $equipmentsObj = $model->getMyEquipments();
        $display = new displayEquipments($equipmentsObj);

        //write ids dans session pour les controle d'edition
        $idsArray = $this->getArrayEquipmentsIdFromDBobj($equipmentsObj);
        $this->updateEquipmentsDisplayed($idsArray);

        //retour
        return $display->show();
    }

    public function getMyBlocLive()
    {
        $model = new LiveModel();
        $live = $model->getMyLive();
        $display = new displayLives($live, 'ProfileLive');
        return $display->show();
    }

    //get l'array de teams displayed pour l'ajouter a la session via "this->writeGamerProfileElemsIdInSession"
    public function getArrayTeamsIdFromDBobj($teamsObj)
    {
        $teamsIds = [];
        foreach($teamsObj as $team)
        {
            array_push($teamsIds, $team->pk_idteam);
        }
        return $teamsIds;
    }

    public function getArrayEventsIdFromDBobj($eventsObj)
    {
        $eventsIds = [];
        foreach($eventsObj as $eventObj)
        {
            array_push($eventsIds, $eventObj->pk_idevent);
        }
        return $eventsIds;
    }

    public function getArrayGamesIdFromDBobj($gamesObj)
    {
        $gameIds = [];
        foreach($gamesObj as $gameObj)
        {
            array_push($gameIds, $gameObj->pk_idgame);
        }
        return $gameIds;
    }

    public function getArrayEquipmentsIdFromDBobj($equipmentsObj)
    {
        $equipmentsIds = [];
        foreach($equipmentsObj as $equipmentObj)
        {
            array_push($equipmentsIds, $equipmentObj->pk_idgear);
        }
        return $equipmentsIds;
    }

    //#todo ne faire qu'une seul action de tt ca
    //nescessaire aux test de sécurité de : est-ce que la team que j'essaye de modifier est bien affiché sur mon ecran, voir Timeline pour le meme test concernant les posts
    public function updateTeamsDisplayed($idsArray)
    {
        //write ids dans session pour les controle d'edition
        $this->session->write('TeamsDisplayed', $idsArray);
    }

    public function updateEventsDisplayed($idsArray)
    {
        //write ids dans session pour les controle d'edition
        $this->session->write('EventsDisplayed', $idsArray);
    }

    public function updateGamesDisplayed($idsArray)
    {
        //write ids dans session pour les controle d'edition
        $this->session->write('GamesDisplayed', $idsArray);
    }

    public function updateEquipmentsDisplayed($idsArray)
    {
        //write ids dans session pour les controle d'edition
        $this->session->write('EquipmentsDisplayed', $idsArray);
    }

    public function deleteFromTeamsDisplayed($teamid)
    {
        $idArray = $this->session->read('TeamsDisplayed');
        $indexValue = array_search($teamid, $idArray);
        unset($idArray[$indexValue]);
        $this->session->write('TeamsDisplayed', $idArray);
    }

    public function deleteFromGamesDisplayed($gameid)
    {
        $idArray = $this->session->read('GamesDisplayed');
        $indexValue = array_search($gameid, $idArray);
        unset($idArray[$indexValue]);
        $this->session->write('GamesDisplayed', $idArray);
    }

    public function updateEventsIdSession($eventid)
    {
        //creer le tableau s'il n'existe pas = le mec n'as aucune team affichée ds sa carrière
        if(!isset($_SESSION['EventsDisplayed']))
        {
            $this->session->write('EventsDisplayed', []);
        }
        //ajouter l'id du nouveau post au tableau, l'ajouter en premier pour conserver l'ordre par rapport a celui d'affichage
        $IdArray = $this->session->read('EventsDisplayed');
        array_push($oldIdArray, $eventid);
        $this->session->write('EventsDisplayed', $IdArray);
    }

    public function deleteFromEventsDisplayed($eventid)
    {
        $idArray = $this->session->read('EventsDisplayed');
        $indexValue = array_search($eventid, $idArray);
        unset($idArray[$indexValue]);
        $this->session->write('EventsDisplayed', $idArray);
    }

    public function deleteFromEquipmentsDisplayed($equipmentid)
    {
        $idArray = $this->session->read('EquipmentsDisplayed');
        $indexValue = array_search($equipmentid, $idArray);
        unset($idArray[$indexValue]);
        $this->session->write('EquipmentsDisplayed', $idArray);
    }
    
    /****************************************************************************\
     *                          SOMEONE ELESE PART                              *
    \****************************************************************************/
    public function getUserQuickInfos()
    {
        $model = new QuickInfosModel();
        $quickinfosObj = $model->getQuickInfosFromIdUser($this->userToDisplay->pk_iduser); //#todo, encore une requette de plus qui aurai peux etre été résolue par un joi nde l'user sur ses quickinfos, a voir a ce niveau la pour optimiser
        $display = new displayQuickinfos($quickinfosObj, $this->profilePart);
        return $display->show();
    }

    public function getUserSummary()
    {
        $display = new displayUser($this->userToDisplay, 'gamer.summary');
        return $display->show();
    }

    public function getUserCareer()
    {
        //get career
        $model = new CareerModel();
        $teamsObj = $model->getGamerCareerFromIduser($this->userToDisplay->pk_iduser);
        $display = new displayCareer($teamsObj, false, $this->userToDisplay);
        $careerContent = $display->showGamerCareer();

        //write ids dans session pour les controle d'edition
        //$idArray = $this->getArrayTeamsIdFromDBobj($teamsObj);
        //$this->updateTeamsDisplayed($idArray);

        //retour
        return $careerContent;
    }

    public function getUserEvents()
    {
        //get events
        $model = new EventModel();
        $eventsObj = $model->getEventsFromId($this->userToDisplay->pk_iduser);
        $display = new displayEvents($eventsObj, false, $this->userToDisplay);
        $eventContent = $display->showGamerEvents();

        //retour
        return $eventContent;
    }

    public function getUserGames()
    {
        //get games
        $model = new GamesModel();
        $gamesObj = $model->getGamesFromUserId($this->userToDisplay->pk_iduser);
        $display = new displayGames($gamesObj, $this->userToDisplay);

        //write ids dans session pour les controle d'edition
        //$idsArray = $this->getArrayGamesIdFromDBobj($gamesObj);
        //$this->updateGamesDisplayed($idsArray);

        //retour
        return $display->show();
    }

    public function getUserEquipments()
    {
        //get equipments
        $model = new EquipmentModel();
        $equipmentsObj = $model->getUserEquipmentsFromUserId($this->userToDisplay->pk_iduser);
        $display = new displayEquipments($equipmentsObj, $this->userToDisplay);

        //write ids dans session pour les controle d'edition
        //$idsArray = $this->getArrayEquipmentsIdFromDBobj($equipmentsObj);
        //$this->updateEquipmentsDisplayed($idsArray);

        //retour
        return $display->show();
    }
    
    public function getUserBlocLive()
    {
        $model = new LiveModel();
        $live = $model->getUserLiveFromId($this->userToDisplay->pk_iduser);
        $display = new displayLives($live, 'ProfileLive', $this->userToDisplay);
        return $display->show();
    }
}