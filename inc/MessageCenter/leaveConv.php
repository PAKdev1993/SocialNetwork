<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;

if(isset($_POST['idconv']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv = $_POST['idconv'];
    $model = new ConversationModel();

    if($model->isMyConv($idconv))
    {
        if($model->getTypeConv($idconv) == 'groupConv')
        {
            $model->leaveConv($idconv);
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
