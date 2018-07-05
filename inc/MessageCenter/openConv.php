<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;

require_once '../Autoloader.php';
Autoloader::register();

$iduser = $_POST['iduser'];
$idconv = $_POST['convid'];
$model = new ConversationModel();
if($model->isUserInConv($iduser, $idconv))
{
    $model->openConvForUser($iduser, $idconv);
    exit();
}
else{
    echo('err');
    exit();
}