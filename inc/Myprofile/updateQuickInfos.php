<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\Profile\Quickinfos\QuickInfosModel;
use app\Table\Profile\Quickinfos\displayQuickinfos;

if(isset($_POST['dateofbirth']) && isset($_POST['nationnality']) && isset($_POST['language']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //test si l'etat courant est bien celui de propriétaire du profil
    if(Session::getInstance()->read('current-state')['state'] == 'owner')
    {
        //create date format to insert
        $model = new QuickInfosModel();
        $birthdate = date("Y-m-d", strtotime($_POST['dateofbirth']));

        //Update my quick infos
        $model->updateBasicsQuickInfos($birthdate, $_POST['nationnality'], $_POST['language']);
        $quickinfoObj = $model->getMyQuickInfos();
        $display = new displayQuickinfos($quickinfoObj);
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