<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\Profile\Events\EventModel;
use app\Table\Profile\Events\displayEvents;
use core\Files\Images\Images;
use core\Profile\ProfileGamer;

if(isset($_POST['eventname']) && isset($_POST['game']) && isset($_POST['platform']) && isset($_POST['role']) && isset($_POST['startDate']) && isset($_POST['endDate']) && isset($_POST['team']) && isset($_POST['rank']) && isset($_POST['desc'])  && isset($_POST['typeaction']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //create date format to insert (end date is below)
    $model = new EventModel();
    $GamerProfile = new ProfileGamer();
    $startdate = date("Y-m-d", strtotime($_POST['startDate']));
    $enddate = date("Y-m-d", strtotime($_POST['endDate']));

    /****************************************************************************\
     *                          CREATION D'UN EVENT                             *
    \****************************************************************************/
    if($_POST['typeaction'] == 'create')
    {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        $valid = true;

        //test si l'etat courant est bien celui de propriétaire du profil
        if(Session::getInstance()->read('current-state')['state'] != 'owner')
        {
            $valid = false;
        }
        if($valid)
        {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            //si l'image est definie on fais les operation dessus
            $ImgName = '';
            if(isset($_FILES['logo'])){
                //save game image
                $images = new Images();
                $images->uploadEventImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            }
            //sinon l'image default est mise
            else{
                $ImgName = $_POST['logo'];
            }
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SAVE NEW EVENT
         */
        $model->saveMyEvent($_POST['eventname'], $ImgName, $_POST['game'], $_POST['team'], $_POST['role'], $startdate, $enddate, $_POST['platform'], $_POST['desc'], $_POST['rank']);
        $Events = $model->getMyEvents();
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * MAJ EVENTS DISPLAYD (SECURITY)
         */
        $idsArray = $GamerProfile->getArrayEventsIdFromDBobj($Events);
        $GamerProfile->updateEventsDisplayed($idsArray);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * NOTIFY MY NETWORK IF NESCESSARY
         */
        $typeaction = $GamerProfile->getProfilePart();
        $action = "action_updated_gamerprofile";
        $elemType = NULL;
        $elemid = NULL;
        $GamerProfile->notifyMyNetwork($typeaction, $action, $elemType, $elemid);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * DISPLAY
         */
        $displayEvents = new displayEvents($Events);
        echo($displayEvents->showGamerEvents());
        exit();
    }
    /****************************************************************************\
     *                          UPDATE D'UN EVENT                               *
    \****************************************************************************/
    if($_POST['typeaction'] == 'update')
    {
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
        if(Session::getInstance()->read('current-action')['actionname'] != 'editingEvent')
        {
            $valid = false;
        }
        if($valid)
        {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            //si l'image est definie on fais les operation dessus
            $ImgName = '';
            if (isset($_FILES['logo'])) {
                //save game image
                $images = new Images();
                $images->uploadEventImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            } //sinon l'image default est mise
            else {
                $ImgName = 'didnttouch';
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * SAVE UPDATED EVENT
             */
            $model->updateMyEvent($elemid, $_POST['eventname'], $ImgName, $_POST['game'], $_POST['team'], $_POST['role'], $startdate, $enddate, $_POST['platform'], $_POST['desc'], $_POST['rank']);
            $events = $model->getMyEvents();
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $displayEvents = new displayEvents($events);
            echo($displayEvents->showGamerEvents());
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