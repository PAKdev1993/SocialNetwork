<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Messages\MessagesModel;
use app\Table\Messages\Messages\displayMessages;

if(isset($_POST['convid']) && isset($_POST['nbMessagesLoaded'])) {
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv =           $_POST['convid'];
    $nbMesagesloaded =  $_POST['nbMessagesLoaded'];

    $model = new ConversationModel();

    if($model->isMyConv($idconv))
    {
        $model = new MessagesModel();
        $messages = $model->getMessagesFromConversation($idconv, $nbMesagesloaded);
        if($messages)
        {
            $display = new displayMessages($messages, 'chatBoxe', false);
            $messHtmlArray['messages'] = $display->show();
            echo(json_encode($messHtmlArray));
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