<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\Profile\Quickinfos\displayQuickinfos;
use app\Table\Profile\Quickinfos\QuickInfosModel;

use app\Table\Profile\Career\displayCareer;

use app\Table\Profile\Events\displayEvents;

use app\Table\Profile\Games\displayGames;

use app\Table\Profile\Equipments\displayEquipments;

use app\Table\UserModel\displayUser;

use app\Table\Live\displayLives;

if(isset($_POST['blocname']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    if(Session::getInstance()->read('current-state')['state'] == 'owner')
    {
        //display quick infos edit mode
        if($_POST['blocname'] == 'qi')
        {
            $model = new QuickInfosModel();
            $DBobj = $model->getMyQuickInfos();
            $display = new displayQuickinfos($DBobj);
            echo($display->showEdit());
            exit();
        }

        //display addTeam to career edit mode
        if($_POST['blocname'] == 'mts')
        {
            $display = new displayCareer();
            echo($display->showEdit());
            exit();
        }

        //display AddEvent edit mode
        if($_POST['blocname'] == 'mevts')
        {
            $display = new displayEvents();
            echo($display->showEdit());
            exit();
        }

        //display AddGame edit mode
        if($_POST['blocname'] == 'mga')
        {
            $display = new displayGames();
            echo($display->showEdit());
            exit();
        }

        //display AddEquipment edit mode
        if($_POST['blocname'] == 'meq')
        {
            $display = new displayEquipments();
            echo($display->showEdit());
            exit();
        }

        //display Summary edit mode
        if($_POST['blocname'] == 'sum')
        {
            $display = new displayUser(Session::getInstance()->read('auth'));
            echo($display->showSumEdit());
            exit();
        }

        //display Interest edit mode
        if($_POST['blocname'] == 'int')
        {
            $display = new displayUser(Session::getInstance()->read('auth'));
            echo($display->showIntEdit());
            exit();
        }

        //display Company edit mode
        if($_POST['blocname'] == 'mc')
        {
            $display = new displayCareer();
            echo($display->showEmployeeEdit());
            exit();
        }
        //display Company edit mode
        if($_POST['blocname'] == 'empe')
        {
            $display = new displayEvents();
            echo($display->showEmployeeEdit());
            exit();
        }
        //display Employee summary edit mode
        if($_POST['blocname'] == 'sumemp')
        {
            $display = new displayUser(Session::getInstance()->read('auth'), 'employee.summary');
            echo($display->showSumEmployeeEdit());
            exit();
        }
        //display Live edit mode
        if($_POST['blocname'] == 'live')
        {
            $display = new displayLives();
            echo($display->displayLiveEdit());
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