<?php
use inc\Autoloader;
use core\Session\Session;
use app\Displays\displayAsk;
use app\Table\Messages\Conversations\ConversationModel;

require_once '../Autoloader.php';
Autoloader::register();

//--------------------------------------------------------------------------------------------------------------------
/*
 * CANCEL DELETE CONV
 */
//controles de sécurité
$model = new ConversationModel();
$valid = true;

//check l'action en cours
if(!Session::getInstance()->read('current-action')['actionname'] == 'deletingConv')
{
    $valid = false;
}
//check si l'element e l'action n'a pas changé
$idconv = Session::getInstance()->read('current-action')['idelem'];
if(!$model->isMyConv($idconv))
{
    $valid = false;
}
if($valid)
{
    
}
else{
    echo('err');
    exit();
}
