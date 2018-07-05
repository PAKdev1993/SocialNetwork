<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;

if(isset($_POST['idconv']) && isset($_POST['iduser']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $convid =   $_POST['idconv'];
    $iduser =   $_POST['iduser'];
    $model =    new ConversationModel();
    if($model->isUserInConv($iduser, $convid))
    {
        $state = $model->getConversationState($convid);
        if($state == 0)
        {
            echo('addMsgNotif');
            exit();
        }
        else{
            echo('openInChatBox');
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




