<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Messages\MessagesModel;
use core\Functions;

require_once '../Autoloader.php';
Autoloader::register();

$action = $_POST['action'];

if($action == 'updateReaded')
{
    $idconv =           $_POST['convid'];
    $iduserWhoRead =    $_POST['iduser'];
    $readExist =        $_POST['readExist'];
    $model = new ConversationModel();

    if($model->isMyConv($idconv))
    {
        if($model->isUserInConv($iduserWhoRead, $idconv))
        {
            $nickname = Functions::getUserNickname($iduserWhoRead);
            //if read exist mean that 'viewed by' is already writed, then we dont need to generate the translation of this word
            if($readExist == 'true')
            {
                echo($nickname);
                exit();
            }
            else{
                $model = new MessagesModel();
                $string = $model->getReadebyTrad().' '. $nickname;
                echo($string);
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
}