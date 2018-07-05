<?php
use inc\Autoloader;
use app\App;
use core\Session\Session;
use app\Table\Profile\Quickinfos\QuickInfosModel;
use app\Table\Profile\Quickinfos\displayQuickinfos;

if(isset($_POST['newCurrentTeam']) && isset($_POST['role']) && isset($_POST['game']) && isset($_POST['platform']) && isset($_POST['state']))
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
    if($valid)
    {
        //vars
        $qiNameCurrentTeam =    $_POST['newCurrentTeam'];
        $qiRoleCurrentTeam =    $_POST['role'];
        $qiGameCurrentTeam =    $_POST['game'];
        $qPlatformCurrentTeam = $_POST['platform'];
        $action =               $_POST['state'];

        //creation de la chaine previous teams
        $qimodel = new QuickInfosModel();
        $qiPreviousTeams = $qimodel->getMyPreviousTeamString(2);

        //nouvelle current team
        if($_POST['state'] == '01')
        {
            //mise aj our des quickinformation avancées
            $qimodel->updateAdvancedQuickInfos($qiRoleCurrentTeam, $qiGameCurrentTeam, $qiNameCurrentTeam, $qiPreviousTeams, $qPlatformCurrentTeam, $action);
        }
        //plus de current team
        if($_POST['state'] == '10')
        {
            //mise aj our des quickinformation avancées
            $qimodel->updateAdvancedQuickInfos(NULL, NULL, NULL, $qiPreviousTeams, NULL, $action);
        }
        //rien a changé
        if($_POST['state'] == 'default')
        {
            //mise aj our des quickinformation avancées
            $qimodel->updateAdvancedQuickInfos($qiRoleCurrentTeam, $qiGameCurrentTeam, $qiNameCurrentTeam, $qiPreviousTeams, $qPlatformCurrentTeam, $action);
        }
        //display new quickinfos
        $quickinfoObj = $qimodel->getMyQuickInfos();
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