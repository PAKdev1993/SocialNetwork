<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\Conversation\displayConversation;

require_once '../Autoloader.php';
Autoloader::register();

//recuperer les conversations ouvertes, minimized et grouped
$model =  new ConversationModel();
//id conv to display notif message
$idconv = $_POST['idconv'];
//iduser message author
$iduser = $_POST['iduser'];

//met le html des conversations ds un tableau json: conversations[open (open or minimized][htmlConv]
//[grouped][htmlConv]
if($model->isMyConv($idconv))
{
    //si le message viens de 
    $conv = $model->getConversationFromId($idconv);
    $display = new displayConversation($conv, 'UmConversations');
    $result['apConvHtml'] = $display->showApercuDiscution();
    $result['notifConvHtml'] = $display->show();
    echo(json_encode($result));
    exit();
}
else{
    echo("pas de conv");
    exit();
}
