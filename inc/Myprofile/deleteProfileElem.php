<?php
use inc\Autoloader;

use core\Session\Session;
use core\Profile\ProfileGamer;
use core\Profile\ProfileEmployee;

use app\Table\Profile\Quickinfos\QuickInfosModel;

use app\Table\Profile\Career\displayCareer;
use app\Table\Profile\Career\CareerModel;

use app\Table\Profile\Events\EventModel;
use app\Table\Profile\Events\displayEvents;

use app\Table\Profile\Games\GamesModel;
use app\Table\Profile\Games\displayGames;

use app\Table\Profile\Equipments\EquipmentModel;
use app\Table\Profile\Equipments\displayEquipments;


require_once '../Autoloader.php';
Autoloader::register();

if(isset($_POST['type']))
{
    $type = $_POST['type'];
    $elemid =  Session::getInstance()->read('current-action')['idelem'];

    $valid = true;
    $msg = '';
    //--------------------------------------------------------------------------------------------------------------------
    /*
     *  DELETE TEAM
     */
    //test de sécurités
    if($type == 'team')
    {
        $suppressToken = Session::getInstance()->read('teamSuppressToken');
        $model = new CareerModel();

        if($suppressToken != $model->getTeamSuppressTokenFromId($elemid))
        {
            $valid = false;
        }
        if($valid)
        {
            //retirer la team des teams affichées
            $coreProfile = new ProfileGamer();
            $coreProfile->deleteFromTeamsDisplayed($elemid);

            //reinitialiser la current-action
            Session::getInstance()->delete('current-action');

            //delete team de la BDD
            $team = $model->getMyTeamFromId($elemid);
            $model->deleteTeam($elemid);

            //MAJ du quick infos
            $qimodel = new QuickInfosModel();

                //cas current team
            if($team->currentplayhere)
            {
                $qimodel->updateOnDeleteCurrentTeam();
                $msg = 'deleted//current';
            }
            else{
                $qimodel->updateOnDeleteTeam();
                $msg = 'deleted//normal';
            }
                //cas previous team
            $newPrevTeamString = $qimodel->getMyPreviousTeamString(2);
            $msg = $msg . '//' . $newPrevTeamString;

            //cas ou la carrière entière est delete
            $career = $model->getMyGamerCareer();
            if(!$career)
            {
                $displayCareer = new displayCareer($career);
                $msg = 'deleted//all////' . $displayCareer->showGamerCareer(); //"todo bricolage
            }

            //display
            echo($msg);
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     *  DELETE EVENT
     */
    //test de sécurités
    if($type == 'event')
    {
        $suppressToken = Session::getInstance()->read('eventSuppressToken');
        $model = new EventModel();

        if($suppressToken != $model->getEventSuppressTokenFromId($elemid))
        {
            $valid = false;
        }
        if($valid)
        {
            //retirer la team des teams affichées
            $coreProfile = new ProfileGamer();
            $coreProfile->deleteFromEventsDisplayed($elemid);

            //reinitialiser la current-action
            Session::getInstance()->delete('current-action');

            //delete team de la BDD
            $model->deleteMyEvent($elemid);

            //cas ou tout les events sont delete
            $events = $model->getMyEvents();
            if(!$events)
            {
                $displayEvents = new displayEvents($events);
                $msg = 'deleted//all////' . $displayEvents->showGamerEvents(); //"todo bricolage
            }

            //display
            echo($msg);
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     *  DELETE GAME
     */
    //test de sécurités
    if($type == 'game')
    {
        $suppressToken = Session::getInstance()->read('gameSuppressToken');
        $model = new GamesModel();

        if($suppressToken != $model->getGameSuppressTokenFromId($elemid))
        {
            $valid = false;
        }
        if($valid)
        {
            //retirer la team des teams affichées
            $coreProfile = new ProfileGamer();
            $coreProfile->deleteFromGamesDisplayed($elemid);

            //reinitialiser la current-action
            Session::getInstance()->delete('current-action');

            //delete team de la BDD
            $model->deleteGame($elemid);

            //cas ou tout les events sont delete
            $games = $model->getMyGames();
            if(!$games)
            {
                $display = new displayGames($games);
                $msg = 'deleted//all////' . $display->show(); //"todo bricolage
            }

            //display
            echo($msg);
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     *  DELETE EQUIPMENT
     */
    //test de sécurités
    if($type == 'equipment')
    {
        $suppressToken = Session::getInstance()->read('equipmentSuppressToken');
        $model = new EquipmentModel();

        if($suppressToken != $model->getEquipmentSuppressTokenFromId($elemid))
        {
            $valid = false;
        }
        if($valid)
        {
            //retirer la team des teams affichées
            $coreProfile = new ProfileGamer();
            $coreProfile->deleteFromEquipmentsDisplayed($elemid);

            //reinitialiser la current-action
            Session::getInstance()->delete('current-action');

            //delete team de la BDD
            $model->deleteEquipment($elemid);

            //cas ou tout les events sont delete
            $equipments = $model->getMyEquipments();
            if(!$equipments)
            {
                $display = new displayEquipments($equipments);
                $msg = 'deleted//all////' . $display->show(); //"todo bricolage
            }

            //display
            echo($msg);
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     *  DELETE COMPANY
     */
    //test de sécurités
    if($type == 'company')
    {
        $suppressToken = Session::getInstance()->read('companySuppressToken');
        $model = new CareerModel();

        if($suppressToken != $model->getCompanySuppressTokenFromId($elemid))
        {
            $valid = false;
        }
        if($valid)
        {
            //retirer la team des teams affichées
            $coreProfile = new ProfileEmployee();
            $coreProfile->deleteFromCompaniesDisplayed($elemid);

            //reinitialiser la current-action
            Session::getInstance()->delete('current-action');

            //delete team de la BDD
            $team = $model->getMyCompanyFromId($elemid);
            $model->deleteCompany($elemid);

            //MAJ du quick infos
            $qimodel = new QuickInfosModel();

            //cas current team
            if($team->currentlyWorkHere)
            {
                $qimodel->updateOnDeleteCurrentCompany();
                $msg = 'deleted//current';
            }

            //cas ou la carrière entière est delete
            $career = $model->getMyEmployeeCareer();
            if(!$career)
            {
                $displayCareer = new displayCareer($career);
                $msg = 'deleted//all////' . $displayCareer->showEmployeeCareer(); //"todo bricolage
            }

            //display
            echo($msg);
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     *  DELETE EMPLOYEE EVENT
     */
    //test de sécurités
    if($type == 'empevent')
    {
        $suppressToken = Session::getInstance()->read('empeventSuppressToken');
        $model = new EventModel();

        if($suppressToken != $model->getEmpEventSuppressTokenFromId($elemid))
        {
            $valid = false;
        }
        if($valid)
        {
            //retirer la team des teams affichées
            $coreProfile = new ProfileEmployee();
            $coreProfile->deleteFromEmpEventsDisplayed($elemid);

            //reinitialiser la current-action
            Session::getInstance()->delete('current-action');

            //delete team de la BDD
            $model->deleteEmployeeEvent($elemid);

            //display
            $events = $model->getMyEmployeeEvents();
            $displayEvents = new displayEvents(false, $events);
            echo($displayEvents->showEmployeeEvents());
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
}
else{
    echo('err');
    exit();
}