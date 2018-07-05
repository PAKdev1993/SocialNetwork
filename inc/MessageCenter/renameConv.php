<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;

if(isset($_POST['idconv']) && isset($_POST['newName']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv =   $_POST['idconv'];
    $newName =  $_POST['newName'];
    $model =    new ConversationModel();

    if($model->isMyConv($idconv))
    {
        if($model->getTypeConv($idconv) == 'groupConv')
        {

            $model->renameConv($idconv, $newName);
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