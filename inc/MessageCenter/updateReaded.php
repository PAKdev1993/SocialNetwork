<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Messages\Message\displayMessage;
use core\Session\Session;

if(isset($_POST['idConv']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv =       $_POST['idConv'];
    $model =        new ConversationModel();

    if($model->isMyConv($idconv))
    {
        $model->readConv($idconv);
        $idusers = $model->getIdParticipants($idconv, true);
        $stringIdUsers = implode(',',$idusers);
        echo($stringIdUsers);
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