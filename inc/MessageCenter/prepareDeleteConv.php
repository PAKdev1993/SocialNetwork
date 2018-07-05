<?php
use inc\Autoloader;
use core\Session\Session;
use app\Displays\displayAsk;
use app\Table\Messages\Conversations\ConversationModel;

if(isset($_POST['idelem']) && isset($_POST['type']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv =   $_POST['idelem'];
    $type =     $_POST['type'];
    $valid = true;

    //--------------------------------------------------------------------------------------------------------------------
    /*
     * DELETE CONV
     */
    //controles de sécurité
    $model = new ConversationModel();
    if($model->isMyConv($idconv))
    {
        if($type == 'conv')
        {
            $actionName = 'deletingConv';

            //set current action
            Session::getInstance()->setCurentAction($actionName, $idconv);

            //display ask
            $display = new displayAsk('deleting-conv');
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