<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;

if(isset($_POST['idchat']) && isset($_POST['action']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $convid =   $_POST['idchat'];
    $state =    $_POST['action'];
    $model = new ConversationModel();
    if($model->isMyConv($convid))
    {
        $model->changeConversationState($state, $convid);
    }
    else{
        echo('not my conv');
        exit();
    }
}
else{
    echo('err');
    exit();
}




