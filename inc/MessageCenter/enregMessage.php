<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use core\Session\Session;

if(isset($_POST['idconv']) && isset($_POST['message']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $idconv =       $_POST['idconv'];
    $message =      $_POST['message'];
    $model =        new ConversationModel();

    if($model->isMyConv($idconv))
    {
        //check if conv  wwas already activated
        $convIsActivated = $model->isConvIsActivated($idconv);

        //save msg in BD and active convs
        $model->enregMessage($idconv, $message);

        //if conv n'etait pas activée
        if(!$convIsActivated)
        {
            $idusers = $model->getIdParticipants($idconv);
            //remove user courant (car c'est lui qui active la conv, donc c'est lui le createur de la conv, donc la conv est deja activée chez lui)
            $idusers = array_diff($idusers, array(Session::getInstance()->read('auth')->pk_iduser));
            $results['users'] = $idusers;
            //on retourne les users pour lesquel la conv doit desormais etre affichée
            echo(json_encode($results));
            exit();
        }
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