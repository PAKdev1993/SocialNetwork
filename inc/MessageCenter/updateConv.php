<?php
use inc\Autoloader;
use core\MessageCenter\Message;
use app\Table\Messages\Conversations\ConversationModel;

require_once '../Autoloader.php';
Autoloader::register();

$chat = new Message();

if (isset($_POST['action']))
{
    if($_POST['action'] == "addUsers")
    {
        if(isset($_POST['usersString']))
        {
            $userToAdd =    explode(',', $_POST['userAdedToExclude']);
            $idConv =       $_POST['idConv'];
            $model =        new ConversationModel();
            $result =       $model->addUsersToConversation($userToAdd, $idconv);
            echo(json_encode($result));
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    if($_POST['action'] == "changeConvName")
    {
        if(isset($_POST['usersString']))
        {

        }
        else{
            echo('err');
            exit();
        }
    }


}