<?php
use inc\Autoloader;
use core\Session\Session;
use app\Displays\displayAsk;
use app\Table\Posts\PostModel;

if(isset($_POST['datas']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $postids = Session::getInstance()->read('PostDisplayed');
    $datasArray = json_decode($_POST['datas']);
    $postid = $datasArray->wepid;

    //verification de l'existence du postid dans le tableau PostDisplayed n'est pas faites ici car faites lor du dusplay du mode edition
    
    //test si l'action courante est toujours le mode editing pour parer aux modifications html
    if(Session::getInstance()->read('current-action')['actionname'] == 'editingPost' && Session::getInstance()->read('current-action')['idelem'] == $postid)
    {
        //get le suppress token pour l'inscrire en session
        $model = new PostModel();
        $suppressToken = $model->getPostSuppressToken($postid);

        Session::getInstance()->write('postSuppressToken', $suppressToken);

        //affiche le viewer de confirmation
        $display = new displayAsk('deleting-post');
        echo($display->show());
        exit();
    }
    else{
        echo('err');
    }
}
else{
    echo('err');
}