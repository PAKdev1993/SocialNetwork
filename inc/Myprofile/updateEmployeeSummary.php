<?php
use inc\Autoloader;
use app\App;
use core\Session\Session;
use app\Table\UserModel\UserModel;
use app\Table\UserModel\displayUser;

use core\Profile\ProfileEmployee;

if(isset($_POST['summary']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //test si l'etat courant est bien celui de propriétaire du profil
    if(Session::getInstance()->read('current-state')['state'] == 'owner')
    {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * UPDATE SUMMARY
         */
        $model = new UserModel(App::getDatabase());
        $model->saveMyEmployeeSummary($_POST['summary']);

        //on met a jour l'auth
        Session::getInstance()->read('auth')->employeesummary = $_POST['summary'];
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * NOTIFY MY NETWORK IF NESCESSARY
         */
        $EmployeeProfile = new ProfileEmployee();
        $typeaction = $EmployeeProfile->getProfilePart();
        $action = "action_updated_employeeprofile";
        $elemType = NULL;
        $elemid = NULL;
        $EmployeeProfile->notifyMyNetwork($typeaction, $action, $elemType, $elemid);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * DISPLAY
         */
        $summaryContent = $model->getMyEmployeeSummary();
        $display = new displayUser(Session::getInstance()->read('auth'), 'employee.summary');
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