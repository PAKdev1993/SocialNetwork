<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Displays\DeleteUserMessagerie\displayUsersToDelete;

if(isset($_POST['idconv']) && isset($_POST['idusers']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv = $_POST['idconv'];
    $idusers = explode(',', $_POST['idusers']);
    $model = new ConversationModel();

    if($model->isMyConv($idconv))
    {
        if($model->getTypeConv($idconv) == 'groupConv')
        {
            $idusersInConv =    $model->getIdParticipants($idconv);
            $iduserRestants =   array_diff($idusersInConv, $idusers);
            $newTitleConv =     $model->getTitleForGroupedConv($idconv, $iduserRestants);
            echo($newTitleConv);
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
