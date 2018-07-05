<?php
use inc\Autoloader;
use core\Session\Session;
use core\Comment\Comment;
use app\Table\Comments\displayComment;
use app\Table\Comments\CommentModel;

if(isset($_POST['text']) && isset($_POST['datas']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //$postids = Session::getInstance()->read('PostDisplayed');
    //$postid = $postids[$_POST['index']];
    $datasArray = json_decode($_POST['datas']);
    $postid = $datasArray->wepid;
    if(Session::getInstance()->checkValueInSession($postid, 'PostDisplayed'))
    {
        //ecrire le comment en BD
        $comment = new Comment();
        $comment->writeCommentPost($_POST['text'], $postid);

        //pour l'affichage du comment AJAX
        $model = new CommentModel();
        $comment = $model->getLastUserCommentFromPost($postid);
        $display = new displayComment($comment);
        echo($display->show());
    }
    else{
        echo('err');
    }

}