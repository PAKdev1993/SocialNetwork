<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Messages\Message\displayMessage;
use core\Functions;
use app\Table\appDates;

if(isset($_POST['idconv']) && isset($_POST['message']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $iduser =       $_POST['iduser'];
    $idconv =       $_POST['idconv'];
    $message =      $_POST['message'];
    $messageObj =   new stdClass();
    $model =        new ConversationModel();

    if($model->isMyConv($idconv))
    {
        if($model->isUserInConv($iduser, $idconv))
        {
            //assign value to object to display message
            $messageObj->pk_idmessage =         '';
            $messageObj->fk_idconversation =    $idconv;
            $messageObj->fk_iduser =            $iduser;
            $messageObj->texte =                $message;
            $messageObj->date =                 date("Y-m-d H:i:s");
            $display =                          new displayMessage($messageObj);

            $result['idauth'] =     $iduser;
            $result['authNick'] =   Functions::getUserNickname($iduser);
            $result['message'] =    $message;
            $date = new appDates(date("Y-m-d H:i:s"));
            $result['date'] =       $date->getDate();
            $result['html'] =       $display->show();

            echo(json_encode($result));
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
}
else{
    echo('err');
    exit();
}