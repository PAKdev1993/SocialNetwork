<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\UserModel\UserModel;
use core\Email\EmailManager;
use app\Table\Invitation\InvitationModel;

if(isset($_POST['email']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $valid = true;
    $model = new UserModel();
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURITY CONTROLS
     */
    if(Session::getInstance()->read('current-state')['state'] != 'owner')
    {
        $valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURITY CONTROLS
     */
    if($valid)
    {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SEND INVITATIONS
         */
        $emailManager = new EmailManager();
        $emailManager->sendInvitationEmail($_POST['email']);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * WRITE INVITATION IN DATABASE
         */
        $model = new InvitationModel();
        $model->saveInvitation($_POST['email']);
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