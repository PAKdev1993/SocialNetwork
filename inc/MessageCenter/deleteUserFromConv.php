<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;

if(isset($_POST['idconv']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv = $_POST['idconv'];
    $model = new ConversationModel();
    $action = $_POST['action'];

    //delete l'user courant de la conv
    if($action == 'getDeleted')
    {
        if($model->isMyConv($idconv))
        {
            if ($model->getTypeConv($idconv) == 'groupConv')
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

    //delete user from conv
    if($action == 'delete')
    {
        $iduser = $_POST['iduser'];
        if($model->isUserInConv($iduser, $idconv))
        {
            //on test egalement si l'user courant fait partie de la conv afin d'empecher qqun de delete un user d'une conv a la quelle il n'appartient pas
            if($model->isMyConv($idconv))
            {
                if ($model->getTypeConv($idconv) == 'groupConv')
                {
                    $model->removeUserFromConversation($iduser, $idconv);
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
    }
}
else{
    echo('err');
    exit();
}
