<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Comments\displayComment;
use app\Table\Comments\CommentModel;

if(isset($_POST['index']) && isset($_POST['beginAt']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $postids = Session::getInstance()->read('PostDisplayed');
    $postid = $postids[$_POST['index']];

    $model = new CommentModel();
    $comments = $model->getCommentsFromPost($postid, (int)$_POST['beginAt']);
    $content = [];

    foreach($comments as $comment)
    {
        $display = new displayComment($comment);
        array_push($content, $display->show());
    }

    echo json_encode($content);
}