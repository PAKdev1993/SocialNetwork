<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\Conversation\displayConversation;

require_once '../Autoloader.php';
Autoloader::register();

$session = Session::getInstance();

//recupère l'user courant
$iduserToChat = $session->read('whois')['id'];
$convTitle = $session->read('whois')['firstname'] .' '.$session->read('whois')['nickname'].' '.$session->read('whois')['lastname'];
$typeConvers = "chatBox";

$model = new ConversationModel();
$conv = $model->conversExistBetweenTwoUsers($convTitle);
if($idconv)
{
    $conversation = $model->getConversationFromId($idconv);
}
else{
    $conversation = $model->openConvUser($iduser);
}

//display conversation
$display = new displayConversation($conversation, "chatBox");
echo($display->show());