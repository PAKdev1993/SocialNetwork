<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Displays\DeleteUserMessagerie\displayUsersToDelete;

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
            $users = $model->getParticipants($idconv, false);
            $display = new displayUsersToDelete($users, 'chatBoxe');
            echo($display->show());
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
