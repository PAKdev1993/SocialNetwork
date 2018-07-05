<?php
use inc\Autoloader;
use app\Displays\displayContact;
use core\Email\EmailManager;

if(isset($_POST['object']) && isset($_POST['message']) && isset($_POST['action']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $action =   $_POST['action'];
    $object =   $_POST['object'];
    $message =  $_POST['message'];
    $MailManager = new EmailManager();

    if($action == 'send-report')
    {
        $MailManager->sendReportEemail($object, $message);
        exit();
    }
    if($action == 'send-contact')
    {
        $MailManager->sendContactEemail($object, $message);
        exit();
    }
    $display = new displayContact($todisplay);
    echo($display->show());
    exit();
}
else{
    echo('err');
}