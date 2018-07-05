<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Messages\displayMessages;

if(isset($_POST['idconv']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv =   $_POST['idconv'];
    $result =   array();
    $model =    new ConversationModel();
    if($model->isMyConv($idconv))
    {
        //on get l'array qui va servir a l'affichage des notifs convs lor du chargement des messages de la chatBox
        $result     = $model->getConvMessages($idconv);
        $messages   = $result['messages'];
        $display    = new displayMessages($messages, 'discussion');
        //on remplace le tableau de messages SQL par un tableau de messages html, nescessaire pour l'affichage coté client car chaque message va se scale to 1 individuelement
        $result['messages'] = $display->show();
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
