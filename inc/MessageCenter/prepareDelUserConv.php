<?php
use inc\Autoloader;
use app\Table\btmodifs\btmodifsDisplay;
use app\Table\Messages\Conversations\ConversationModel;

if(isset($_POST['idconv']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv = $_POST['idconv'];
    $model = new ConversationModel();

    if($model->isMyConv($idconv))
    {
        $status = 'edit-conv-chatbox';
        $display = new btmodifsDisplay($status);
        echo($display->show());
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