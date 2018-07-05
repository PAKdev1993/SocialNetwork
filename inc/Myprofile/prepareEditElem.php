<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\btmodifs\btmodifsDisplay;
use core\Tables\Career\CareerModel;
use core\Tables\Event\EventModel;
use core\Tables\Event\EmployeeEventModel;
use core\Tables\Game\GameModel;
use core\Tables\Equipment\EquipmentModel;
use core\Tables\Company\CompanyModel;

if(isset($_POST['idelem']) && isset($_POST['type']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idelem = $_POST['idelem'];
    $type = $_POST['type'];
    $valid = true;

    //--------------------------------------------------------------------------------------------------------------------
    /*
     * TEAM DISPLAY EDIT OPTIONS
     */
    //controles de sécurité
    if($type == 'team')
    {
        $model = new CareerModel();
        //check si la team est affiché a l'ecran
        if(!Session::getInstance()->checkValueInSession($idelem, 'TeamsDisplayed'))
        {
            $valid = false;
        }
        //check si l'user est bien le propriétaire du profil
        if(!Session::getInstance()->isOwner())
        {
            $valid = false;
        }
        //check si la team en cours est bien la mienne
        if(!$model->isMyTeam($idelem))
        {
            $valid = false;
        }
        //les tests passés, set du current action
        Session::getInstance()->setCurentAction('editingTeam',$idelem);
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * EVENTS DISPLAY EDIT OPTIONS
     */
    if($type == 'event')
    {
        $model = new EventModel();
        //check si la team est affiché a l'ecran
        if(!Session::getInstance()->checkValueInSession($idelem, 'EventsDisplayed'))
        {
            $valid = false;
        }
        //check si l'user est bien le propriétaire du profil
        if(!Session::getInstance()->isOwner())
        {
            $valid = false;
        }
        //check si la team en cours est bien la mienne
        if(!$model->isMyEvent($idelem))
        {
            $valid = false;
        }
        //les tests passés, set du current action
        Session::getInstance()->setCurentAction('editingEvent',$idelem);
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * GAME DISPLAY EDIT OPTIONS
     */
    if($type == 'game')
    {
        $model = new GameModel();
        //check si la team est affiché a l'ecran
        if(!Session::getInstance()->checkValueInSession($idelem, 'GamesDisplayed'))
        {
            $valid = false;
        }
        //check si l'user est bien le propriétaire du profil
        if(!Session::getInstance()->isOwner())
        {
            $valid = false;
        }
        //check si la team en cours est bien la mienne
        if(!$model->isMyGame($idelem))
        {
            $valid = false;
        }
        //les tests passés, set du current action
        Session::getInstance()->setCurentAction('editingGame',$idelem);
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * EQUIPMENT DISPLAY EDIT OPTIONS
     */
    //controles de sécurité
    if($type == 'equipment')
    {
        $model = new EquipmentModel();
        $valid = true;

        //check si la team est affiché a l'ecran
        if(!Session::getInstance()->checkValueInSession($idelem, 'EquipmentsDisplayed'))
        {
            $valid = false;
        }
        //check si l'user est bien le propriétaire du profil
        if(!Session::getInstance()->isOwner())
        {
            //#todo il est arrivé icci que le current state ne soit pas definis, savoir pourquoi et ds quel cas
            $valid = false;
        }
        //check si la team en cours est bien la mienne
        if(!$model->isMyEquipment($idelem))
        {
            $valid = false;
        }
        //les tests passés, set du current action
        Session::getInstance()->setCurentAction('editingEquipment',$idelem);
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * COMPANY DISPLAY EDIT OPTIONS
     */
    //controles de sécurité
    if($type == 'company')
    {
        $model = new CompanyModel();
        $valid = true;

        //check si la team est affiché a l'ecran
        if(!Session::getInstance()->checkValueInSession($idelem, 'CompaniesDisplayed'))
        {
            $valid = false;
        }
        //check si l'user est bien le propriétaire du profil
        if(!Session::getInstance()->isOwner())
        {
            $valid = false;
        }
        //check si la team en cours est bien la mienne
        if(!$model->isMyCompany($idelem))
        {
            $valid = false;
        }
        //les tests passés, set du current action
        Session::getInstance()->setCurentAction('editingCompany',$idelem);
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * EVENTS DISPLAY EDIT OPTIONS
     */
    if($type == 'empevent')
    {
        $model = new EmployeeEventModel();
        //check si la team est affiché a l'ecran
        if(!Session::getInstance()->checkValueInSession($idelem, 'EmpEventsDisplayed'))
        {
            $valid = false;
        }
        //check si l'user est bien le propriétaire du profil
        if(!Session::getInstance()->isOwner())
        {
            $valid = false;
        }
        //check si la team en cours est bien la mienne
        if(!$model->isMyEvent($idelem))
        {
            $valid = false;
        }
        //les tests passés, set du current action
        Session::getInstance()->setCurentAction('editingEmployeeEvent',$idelem);
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * DISPLAY
     */
    if($valid)
    {
        $status = 'edit-profile-elem';
        $display = new btmodifsDisplay($status);
        echo($display->show());
        exit();
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