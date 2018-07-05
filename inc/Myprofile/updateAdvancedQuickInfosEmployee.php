<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Profile\Quickinfos\displayQuickinfos;
use app\Table\Profile\Quickinfos\QuickInfosModel;

if(isset($_POST['jobtitle']) && isset($_POST['company']) && isset($_POST['city']) && isset($_POST['country']) && isset($_POST['state']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $elemid =  Session::getInstance()->read('current-action')['idelem'];
    $valid = true;
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURE CHECK
     */
    if(Session::getInstance()->read('current-state')['state'] != 'owner')
    {
        $valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * UPDATE QI
     */
    //check si la companie modifiée est la current company
    if($valid)
    {
        //preparation de la mise a jour des quick information liés a la career de l'user
        $CurrentComp =          $_POST['company'];
        $CityCurrentComp =      $_POST['city'];
        $CountryCurrentComp =   $_POST['country'];
        $Jobtitle =             $_POST['jobtitle'];

        //creation de la chaine location
        $display = new displayQuickinfos();
        $locationString = $display->createLocationString($CityCurrentComp, $CountryCurrentComp);

        //nouvelle current company
        if($_POST['state'] == '01')
        {
            //mise aj our des quickinformation avancées
            $model = new QuickInfosModel();
            $model->updateAdvancedQuickInfosEmployee($CurrentComp, $Jobtitle, $locationString);
        }
        //plus de current company
        if($_POST['state'] == '10')
        {
            //mise aj our des quickinformation avancées
            $model = new QuickInfosModel();
            $model->updateAdvancedQuickInfosEmployee(NULL, NULL, NULL);
        }
        //simple modification des infos nom ou jobtitle
        if($_POST['state'] == 'default')
        {
            //mise aj our des quickinformation avancées
            $model = new QuickInfosModel();
            $model->updateAdvancedQuickInfosEmployee($CurrentComp, $Jobtitle, $locationString);
        }
        //display new quickinfos
        $quickinfoObj = $model->getMyQuickInfos();
        $display = new displayQuickinfos($quickinfoObj, 'employee');
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