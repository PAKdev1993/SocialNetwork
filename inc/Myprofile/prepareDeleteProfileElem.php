<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\Profile\Career\CareerModel;
use app\Table\Profile\Events\EventModel;
use app\Table\Profile\Games\GamesModel;
use app\Table\Profile\Equipments\EquipmentModel;

use app\Displays\displayAsk;

if(isset($_POST['idelem']) && isset($_POST['type']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idelem =  $_POST['idelem'];
    $type =  $_POST['type'];
    $valid = true;

    //--------------------------------------------------------------------------------------------------------------------
    /*
     * TEAM DELETE
     */
    //controles de sécurité
    if($type == 'team')
    {
        //check si l'action est la bonne
        if(!Session::getInstance()->read('current-action')['actionname'] == 'editingTeam')
        {
            $valid = false;
        }
        //check si l'element e l'action n'a pas changé
        if(!Session::getInstance()->read('current-action')['idelem'] == $idelem)
        {
            $valid = false;
        }
        if($valid)
        {
            //les tests passés, set du current action
            $model = new CareerModel();
            $suppressToken = $model->getTeamSuppressTokenFromId($idelem);
            Session::getInstance()->write('teamSuppressToken', $suppressToken);//#todo creer une fonctiob pour ca

            //display ask
            $display = new displayAsk('deleting-team');
            echo($display->show());
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * EVENT DELETE
     */
    if($type == 'event')
    {
        //check si l'action est la bonne
        if(!Session::getInstance()->read('current-action')['actionname'] == 'editingEvent')
        {
            $valid = false;
        }
        //check si l'element e l'action n'a pas changé
        if(!Session::getInstance()->read('current-action')['idelem'] == $idelem)
        {
            $valid = false;
        }
        if($valid)
        {
            //les tests passés, set du current action
            $model = new EventModel();
            $suppressToken = $model->getEventSuppressTokenFromId($idelem);
            Session::getInstance()->write('eventSuppressToken', $suppressToken);//#todo creer une fonction pour ca

            //display ask
            $display = new displayAsk('deleting-event');
            echo($display->show());
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * GAME DELETE
     */
    if($type == 'game')
    {
        //check si l'action est la bonne
        if(!Session::getInstance()->read('current-action')['actionname'] == 'editingGame')
        {
            $valid = false;
        }
        //check si l'element e l'action n'a pas changé
        if(!Session::getInstance()->read('current-action')['idelem'] == $idelem)
        {
            $valid = false;
        }
        if($valid)
        {
            //les tests passés, set du current action
            $model = new GamesModel();
            $suppressToken = $model->getGameSuppressTokenFromId($idelem);
            Session::getInstance()->write('gameSuppressToken', $suppressToken);//#todo creer une fonction pour ca

            //display ask
            $display = new displayAsk('deleting-game');
            echo($display->show());
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * EQUIPMENT DELETE
     */
    //controles de sécurité
    if($type == 'equipment')
    {
        //check si l'action est la bonne
        if(!Session::getInstance()->read('current-action')['actionname'] == 'editingEquipment')
        {
            $valid = false;
        }
        //check si l'element e l'action n'a pas changé
        if(!Session::getInstance()->read('current-action')['idelem'] == $idelem)
        {
            $valid = false;
        }
        if($valid)
        {
            //les tests passés, set du current action
            $model = new EquipmentModel();
            $suppressToken = $model->getEquipmentSuppressTokenFromId($idelem);
            Session::getInstance()->write('equipmentSuppressToken', $suppressToken);//#todo creer une fonctiob pour ca

            //display ask
            $display = new displayAsk('deleting-equipment');
            echo($display->show());
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * COMPANY DELETE
     */
    //controles de sécurité
    if($type == 'company')
    {
        //check si l'action est la bonne
        if(!Session::getInstance()->read('current-action')['actionname'] == 'editingCompany')
        {
            $valid = false;
        }
        //check si l'element e l'action n'a pas changé
        if(!Session::getInstance()->read('current-action')['idelem'] == $idelem)
        {
            $valid = false;
        }
        if($valid)
        {
            //les tests passés, set du current action
            $model = new CareerModel();
            $suppressToken = $model->getCompanySuppressTokenFromId($idelem);
            Session::getInstance()->write('companySuppressToken', $suppressToken);//#todo creer une fonctiob pour ca

            //display ask
            $display = new displayAsk('deleting-company');
            echo($display->show());
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * EVENT EMPLOYEE DELETE
     */
    //controles de sécurité
    if($type == 'empevent')
    {
        //check si l'action est la bonne
        if(!Session::getInstance()->read('current-action')['actionname'] == 'editingEmployeeEvent')
        {
            $valid = false;
        }
        //check si l'element e l'action n'a pas changé
        if(!Session::getInstance()->read('current-action')['idelem'] == $idelem)
        {
            $valid = false;
        }
        if($valid)
        {
            //les tests passés, set du current action
            $model = new EventModel();
            $suppressToken = $model->getEmpEventSuppressTokenFromId($idelem);
            Session::getInstance()->write('empeventSuppressToken', $suppressToken);//#todo creer une fonctiob pour ca

            //display ask
            $display = new displayAsk('deleting-employee-event');
            echo($display->show());
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