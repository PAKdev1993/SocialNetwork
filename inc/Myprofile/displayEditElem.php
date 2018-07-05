<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\Profile\Career\CareerModel;
use app\Table\Profile\Career\Team\displayTeam;
use app\Table\Profile\Career\Company\displayCompany;

use app\Table\Profile\Events\EventModel;
use app\Table\Profile\Events\Event\displayEvent;
use app\Table\Profile\Events\EmployeeEvent\displayEmployeeEvent;

use app\Table\Profile\Games\GamesModel;
use app\Table\Profile\Games\Game\displayGame;

use app\Table\Profile\Equipments\EquipmentModel;
use app\Table\Profile\Equipments\Equipment\displayEquipment;

use app\Table\UserModel\displayUser;

if(isset($_POST['idelem']) && isset($_POST['type']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    if(Session::getInstance()->read('current-state')['state'] == 'owner')
    {
        $type = $_POST['type'];
        $idelem = $_POST['idelem'];

        //--------------------------------------------------------------------------------------------------------------------
        /*
         * TEAM EDIT
         */
        if($type == 'team')
        {
            $model = new CareerModel();
            $team = $model->getMyTeamFromId($idelem);
            $display = new displayTeam($team);
            echo($display->showEdit());
            exit();
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * EVENT EDIT
         */
        if($type == 'event')
        {
            $model = new EventModel();
            $event = $model->getMyEventFromId($idelem);
            $display = new displayEvent($event);
            echo($display->showEdit());
            exit();
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * GAME EDIT
         */
        if($type == 'game')
        {
            $model = new GamesModel();
            $game = $model->getMyGameFromId($idelem);
            $display = new displayGame($game);
            echo($display->showEdit());
            exit();
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * EQUIPMENT EDIT
         */
        if($type == 'equipment')
        {
            $model = new EquipmentModel();
            $equipment = $model->getMyEquipmentFromId($idelem);
            $display = new displayEquipment($equipment);
            echo($display->showEdit());
            exit();
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * COMPANY EDIT
         */
        if($type == 'company')
        {
            $model = new CareerModel();
            $company = $model->getMyCompanyFromId($idelem);
            $display = new displayCompany($company);
            echo($display->showEdit());
            exit();
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * EMPLOYEE EVENT EDIT
         */
        if($type == 'empevent')
        {
            $model = new EventModel();
            $company = $model->getMyEmployeeEventFromId($idelem);
            $display = new displayEmployeeEvent($company);
            echo($display->showEdit());
            exit();
        }
    }
    else{
        echo('err');
        exit();
    }
}
else{
    echo('err');
    exit();
}