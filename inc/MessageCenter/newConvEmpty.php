<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\Conversation\displayConversation;

require_once '../Autoloader.php';
Autoloader::register();

//display empty conv
$model =        new ConversationModel();
$conv =         $model->getEmptyConv();

if($conv){
    //get idconv
    $idconv = $conv->pk_idconversation;

    //recupère l'etat de la conversation
    $state = $model->getConversationState($idconv);

    //determination de l'action a realiser en fonction de l'etat précédent de la conversation
    $content = '';
    $typeConvers =  "chatBox";
    switch($state){
        //conversation non open
        case 0:
            $display = new displayConversation($conv, $typeConvers);
            $content = $display->show();
            break;
        //conversation already opened
        case 1:
            $content = 'opened//'. $idconv;
            break;
        //conversation minimized
        case 2:
            $content = 'minimized//'. $idconv;
            break;
        //conversation grouped
        case 3:
            $content = 'grouped//'. $idconv;
            break;
    }
    echo($content);
    exit();
}
//si la conversation n'existe pas on la crée
else{
    $conv =     $model->createEmptyConversation();
    $display =  new displayConversation($conv, 'chatBox');
    echo($display->show());
    exit();
}


