<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\Profile\Events\EventModel;
use app\Table\Profile\Events\displayEvents;

use core\Profile\ProfileEmployee;

use core\Files\Images\Images;

if(isset($_POST['eventname']) && isset($_POST['jobtitle']) && isset($_POST['startDate']) && isset($_POST['endDate']) && isset($_POST['company']) && isset($_POST['desc']) && isset($_POST['typeaction'])) {
    require_once '../Autoloader.php';
    Autoloader::register();

    $model = new EventModel();
    $EmployeeProfile = new ProfileEmployee();

    //create date format to insert (end date is below)
    $startdate = date("Y-m-d", strtotime($_POST['startDate']));
    $enddate = date("Y-m-d", strtotime($_POST['endDate']));

    /****************************************************************************\
     *                      CREATION D'UN EMPLOYEE EVENT                        *
    \****************************************************************************/
    if ($_POST['typeaction'] == 'create') {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        $valid = true;
        //test si l'etat courant est bien celui de propriétaire du profil
        if (Session::getInstance()->read('current-state')['state'] != 'owner') {
            $valid = false;
        }
        if ($valid) {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            //si l'image est definie on fais les operation dessus
            $ImgName = '';
            if (isset($_FILES['logo'])) {
                //save game image
                $images = new Images();
                $images->uploadEmployeeEventsImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            } //sinon l'image default est mise
            else {
                $ImgName = $_POST['logo'];
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * SAVE NEW EMPLOYEE EVENT
             */
            $content = '';
            $model->saveMyEmployeeEvent($_POST['eventname'], $ImgName, $_POST['jobtitle'], $startdate, $enddate, $_POST['company'], $_POST['desc']);
            $employeeEvents = $model->getMyEmployeeEvents();
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * MAJ EMPLOYEE EVENT DISPLAYED (SECURITY)
             */
            //update displayed array for security checks
            $idsArray = $EmployeeProfile->getArrayEmployeeEventsIdFromDBobj($employeeEvents);
            $EmployeeProfile->updateEmployeeEventsDisplayed($idsArray);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * NOTIFY MY NETWORK IF NESCESSARY
             */
            $typeaction = $EmployeeProfile->getProfilePart();
            $action = "action_updated_employeeprofile";
            $elemType = NULL;
            $elemid = NULL;
            $EmployeeProfile->notifyMyNetwork($typeaction, $action, $elemType, $elemid);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $displayEvents = new displayEvents(false, $employeeEvents);
            echo($displayEvents->showEmployeeEvents());
            exit();
        }
    }
    /****************************************************************************\
     *                      UPDATE D'UN EMPLOYEE EVENT                          *
    \****************************************************************************/
    if ($_POST['typeaction'] == 'update') {
        $elemid =  Session::getInstance()->read('current-action')['idelem'];
        $valid = true;

        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        if($elemid != $_POST['eventid'])
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-state')['state'] != 'owner')
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-action')['actionname'] != 'editingEmployeeEvent')
        {
            $valid = false;
        }
        if ($valid)
        {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            //si l'image est definie on fais les operation dessus
            $ImgName = '';
            if (isset($_FILES['logo'])) {
                //save company image
                $images = new Images();
                $images->uploadEmployeeEventsImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            }
            else {
                $ImgName = 'didnttouch';
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * UPDATE NEW EMPLOYEE EVENT
             */
            //save company et affichage de la vouvelle carrière
            $model->updateMyEmployeeEvent($elemid, $_POST['eventname'], $ImgName, $_POST['jobtitle'], $startdate, $enddate, $_POST['company'], $_POST['desc']);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $careerCompanies = $model->getMyEmployeeEvents();
            $displayCareer = new displayEvents(false, $careerCompanies);
            echo($displayCareer->showEmployeeEvents());
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