<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Messages\Message\displayMessage;
use core\Session\Session;
use app\Table\Messages\Conversations\Conversation\displayConversation;
use app\Table\Messages\Conversations\displayConversations;
use app\Table\Messages\Messages\displayMessages;

if(isset($_POST['typeNotifs']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $typeNotif =    $_POST['typeNotifs'];
    $model =        new ConversationModel();

    //quand la page se charge, charger les messages des convs
    if($typeNotif == 'messagesChatBoxes')
    {
        $result = $model->getNotifArrayChatBoxes();

        //remplacer le contenu objet sql par le html du message
        $length = sizeof($result);
        for($i = 0; $i < $length; $i++)
        {
            $messages   = $result[$i]['messages'];
            $display    = new displayMessages($messages, 'chatBoxe');
            //on remplace le tableau de messages SQL par un tableau de messages html, nescessaire pour l'affichage coté client car chaque message va se scale to 1 individuelement
            $result[$i]['messages'] = $display->show();
        }
        //increase refresh messages
        echo(json_encode($result));
        exit();
    }
    
    //quand la page est chargée, chartgher les messages d'une conv
    if($typeNotif == 'messagesChatBoxe')
    {
        $idconv             = $_POST['idconv'];
        $dateLastMessage    = $_POST['dateLastMess'];
        $result             = array();
        if($model->isMyConv($idconv))
        {
            //on get l'array qui va servir a l'affichage des notifs convs lor du chargement des messages de la chatBox
            $result     = $model->getNotifArrayChatBoxe($idconv, $dateLastMessage);
            $messages   = $result['messages'];
            $display    = new displayMessages($messages, 'chatBoxe');
            //on remplace le tableau de messages SQL par un tableau de messages html, nescessaire pour l'affichage coté client car chaque message va se scale to 1 individuelement
            $result['messages'] = $display->show();
        }
        echo(json_encode($result));
        exit();
    }

    //quand la page se charge, get les notifs des messages des coversations closed
    if($typeNotif == 'convsNotifs')
    {
        $model = new ConversationModel();
        $unreadedConvs = $model->getUnreadedConvs();
        $display = new displayConversations($unreadedConvs, 'UmConversations');
    }
    
    //quand une conversation est crée lorsqu'un
    if($typeNotif == 'newConv')
    {
        $idconv =   $_POST['idconv'];
        if($model->isMyConv($idconv))
        {
            $conv = $model->getConversationFromId($idconv);
            $display = new displayConversation($conv, 'chatBox');
            $convHtml = $display->show();
            echo($convHtml);
            exit();
        }
        else{
            echo('err');
        }
    }
}
else{
    echo('err');
    exit();
}