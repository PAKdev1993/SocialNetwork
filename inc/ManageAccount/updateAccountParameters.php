<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\UserModel\UserModel;
use app\Table\ManageAccount\ManageAccountModel;
use app\Table\ManageAccount\displayManageAccountOptions;

if(isset($_POST['email']) 
    && isset($_POST['pwd']) 
    && isset($_POST['notifOnAsk']) 
    && isset($_POST['notifOnAskAccepted'])
    && isset($_POST['notifOnLike'])
    && isset($_POST['notifOnComment'])
    && isset($_POST['emailOnAsk'])
    && isset($_POST['emailOnAskAccepted'])
    && isset($_POST['emailOnLike'])
    && isset($_POST['emailOnComment'])
    && isset($_POST['emailOnFollow'])
    && isset($_POST['weeklyEmailVisitors'])
    && isset($_POST['weeklyRecmdContacts']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $valid = true;
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURITY CONTROLS
     */
    if(Session::getInstance()->read('current-state')['state'] != 'owner')
    {
        $valid = false;
    }
    //#todo AJOUTER des securités a ce niveaus
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * BLOCK
     */
    if($valid)
    {
        $notifOnAsk =           $_POST['notifOnAsk'] == 'true'          ? 1 : 0;
        $notifOnAskAccepted =   $_POST['notifOnAskAccepted'] == 'true'  ? 1 : 0;
        $notifOnLike =          $_POST['notifOnLike'] == 'true'         ? 1 : 0;
        $notifOnComment =       $_POST['notifOnComment'] == 'true'      ? 1 : 0;
        $emailOnAsk =           $_POST['emailOnAsk'] == 'true'          ? 1 : 0;
        $emailOnAskAccepted =   $_POST['emailOnAskAccepted'] == 'true'  ? 1 : 0;
        $emailOnLike =          $_POST['emailOnLike'] == 'true'         ? 1 : 0;
        $emailOnComment =       $_POST['emailOnComment'] == 'true'      ? 1 : 0;
        $emailOnFollow =        $_POST['emailOnFollow'] == 'true'       ? 1 : 0;
        $weeklyEmailVisitors =  $_POST['weeklyEmailVisitors'] == 'true' ? 1 : 0;
        $weeklyRecmdContacts =  $_POST['weeklyRecmdContacts'] == 'true' ? 1 : 0;


        $UserModel =            new UserModel();
        $AccountManagingModel = new ManageAccountModel();
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * UPDATE EMAIL
         */
        if($_POST['email'] != "")
        {
            $existEmail = $UserModel->getUserFromEmail($_POST['email']);
            if(!$existEmail || $existEmail->email == Session::getInstance()->read('auth')->email)
            {
                $UserModel->updateMyEmail($_POST['email']);
                Session::getInstance()->read('auth')->email = $_POST['email'];
            }
            else{
                echo('err_email');
                exit();
            }
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * UPDATE PASSWORD
         */
        if($_POST['pwd'] != "")
        {
            $pwdCrypted = password_hash($_POST['pwd'], PASSWORD_BCRYPT);
            $UserModel->updateMyPassword($pwdCrypted);
        }
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * UPDATE ACCOUNT PARAMETERS
         */
        $AccountManagingModel->saveAccountParameters(
            $notifOnAsk,
            $notifOnAskAccepted,
            $notifOnLike,
            $notifOnComment,
            $emailOnAsk,
            $emailOnAskAccepted,
            $emailOnLike,
            $emailOnComment,
            $emailOnFollow,
            $weeklyEmailVisitors,
            $weeklyRecmdContacts);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * DISPLAY
         */
        $manageAccountOptions = array_merge($AccountManagingModel->getWishReceiveNotifWhen(), $AccountManagingModel->getWishReceiveEmailWhen(), $AccountManagingModel->getWishReceiveWeeklyEmailWhen());
        $display = new displayManageAccountOptions($manageAccountOptions);
        echo($display->showBody());
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