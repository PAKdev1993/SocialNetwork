<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use core\Session\Session;

require_once '../Autoloader.php';
Autoloader::register();

$model = new ConversationModel();
$valid = true;

//check si l'action est la bonne
if(!Session::getInstance()->read('current-action')['actionname'] == 'deletingConv')
{
    $valid = false;
}
if($valid)
{
    $idconv = Session::getInstance()->read('current-action')['idelem'];

    if($model->isMyConv($idconv))
    {
        $model->deleteConv($idconv);
        echo($idconv);
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