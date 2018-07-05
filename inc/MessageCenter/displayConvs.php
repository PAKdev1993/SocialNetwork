<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\Conversation\displayConversation;

require_once '../Autoloader.php';
Autoloader::register();

//recuperer les conversations ouvertes, minimized et grouped
$model =            new ConversationModel();
$conversations =    $model->getConversationChatBoxes();

//met le html des conversations ds un tableau json: conversations[open (open or minimized][htmlConv]
                                                               //[grouped][htmlConv]
$arrayConvGrouped = array();
$arrayConvOpen =    array();
if($conversations)
{
    foreach($conversations as $conversation)
    {
        $display = new displayConversation($conversation, 'chatBox');

        //tri des conversations ds le tableau
        //cas des conversations grouped
        if($conversation->state == 3)
        {
            array_push($arrayConvGrouped, $display->show());
        }
        //cas des conversation open ou minimized
        else{
            array_push($arrayConvOpen, $display->show());
        }
    }
    $arrayHTML = array(
        "groupedConversations" => $arrayConvGrouped,
        "openConversations" => $arrayConvOpen
    );

    echo(json_encode($arrayHTML));
}
else{
    echo("pas de conv");
}
