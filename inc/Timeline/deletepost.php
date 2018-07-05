<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Posts\PostModel;
use core\Timeline\Timeline;

require_once '../Autoloader.php';
Autoloader::register();

$postid =  Session::getInstance()->read('current-action')['idelem'];
$suppressToken = Session::getInstance()->read('postSuppressToken');
$model = new PostModel();

//test si l'action courante est toujours le mode editing pour parer aux modifications html
if(Session::getInstance()->read('current-action')['actionname'] == 'editingPost' && Session::getInstance()->read('current-action')['idelem'] == $postid)
{
    //get le suppress token pour la verification
    $model = new PostModel();
    if($suppressToken == $model->getPostSuppressToken($postid))
    {
        //retirer le post des posts affichés
        $timeline = new Timeline();
        $timeline->deleteFromPostDisplayed($postid);

        //reinitialiser la current-action
        Session::getInstance()->delete('current-action');

        //delete le post de la BDD
        $model->deletePost($postid);
        echo('deleted');
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