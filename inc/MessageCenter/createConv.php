<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\Conversation\displayConversation;

require_once '../Autoloader.php';
Autoloader::register();

$session = Session::getInstance();

//on ajoute l'id de l'user courant au tableau pour l'inscrire ds enregConvers
$idUserToChat =     $session->read('whois')['id'];
$idusersToChat =    array($session->read('whois')['id'], $session->read('auth')->pk_iduser);

$typeConvers =      "chatBox";
$model =            new ConversationModel();

//test si la conv existe ou non
$conv = $model->conversExistBetweenTwoUsers($idUserToChat);

//si la conv existe on change son etat pour l'user si lle est affichée ou on l'affiche si elle ne l'est pas
if($conv)
{
    $idconv = $conv->pk_idconversation;
    
    //recupère l'etat de la conversation
    $state = $model->getConversationState($idconv);
    
    //recupère la conversation mise a jour
    $conversation = $model->getConversationFromId($idconv);
    
    //determination de l'action a realiser en fonction de l'etat de la conversation
    $result = array();

    switch($state){
        //conversation non open
        case 0:
            $display = new displayConversation($conversation, $typeConvers);
            $result['state'] =  'closed';
            $result['conv'] =   $display->show();
            break;
        //conversation already opened
        case 1:
            $result['state'] =  'opened';
            $result['id'] =     $idconv;
            break;
        //conversation minimized
        case 2:
            $result['state'] =  'minimized';
            $result['id'] =     $idconv;
            break;
        //conversation grouped
        case 3:
            $result['state'] =  'grouped';
            $result['id'] =     $idconv;
            break;
        //conversation deleted
        case 4:
            $result['state'] =  'deleted';
            $display =          new displayConversation($conversation, $typeConvers, false);
            $result['id'] =     $idconv;
            $result['user'] =   $idUserToChat;
            $result['conv'] =   $display->show();
            break;
    }
    echo(json_encode($result));
    exit();
}
//si la conversation n'existe pas on la crée
else{
    $conversation =     $model->createConversation($idusersToChat, false);
    $display =          new displayConversation($conversation, $typeConvers, false);
    $result['state'] =  'created';
    $result['id'] =     $conversation->pk_idconversation;
    $result['user'] =   $idUserToChat;
    $result['conv'] =   $display->show();
    echo(json_encode($result));
    exit();
}

