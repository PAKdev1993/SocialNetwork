<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\Conversation\displayConversation;

if(isset($_POST['idconv']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv =       $_POST['idconv'];
    $model =        new ConversationModel();

    if($model->isMyConv($idconv))
    {
        $conv = $model->getConversationFromId($idconv);

        if(isset($_POST['typeResult']))
        { 
            $generateConvNotif = $_POST['generateConvNotif'];
            $typeResult = $_POST['typeResult'];
            if($typeResult == 'discussion')
            {
                $display = new displayConversation($conv, 'discussion', true);
                $result['convHtml'] =   $display->show();
            }
            else if($typeResult == 'notif/apDiscu')
            {
                //get la conv et la notif
                $display = new displayConversation($conv, 'UmConversations', true);
                $result['notifHtml'] =  $display->show();
                $result['apDiscuHtml'] =   $display->showApercuDiscution();
            }
            else if($typeResult == 'apercuDiscutions')
            {
                //get la conv et la notif
                $display = new displayConversation($conv, 'apercuDiscutions', true);
                $result['apDiscuHtml'] = $display->showApercuDiscution();
            }
            else if($typeResult == 'chatBox')
            {
                $display = new displayConversation($conv, $typeResult, true);
                $result['convHtml'] = $display->show();
            }
            echo(json_encode($result));
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