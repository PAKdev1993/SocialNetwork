<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Posts\PostModel;
use app\Table\btmodifs\btmodifsDisplay;

if(isset($_POST['datas']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $datasArray = json_decode($_POST['datas']);
    $postid = $datasArray->wepid;

    $model = new PostModel();

    if(Session::getInstance()->checkValueInSession($postid, 'PostDisplayed'))
    {
        if($model->getPostAuthor($postid) == Session::getInstance()->read('auth')->pk_iduser)
        {
            //sert pour le controle de l'action en cours au cas ou quelqu'un s'amuserai a mettre post-text-active dans le html pout activer le mode edition
            Session::getInstance()->setCurentAction('editingPost',$postid);
            $post = $model->getPostFromid($postid);

            //le post est de type notifyMyNetwork
            if($post->typeNotifyMyNetwork)
            {
                $display = new btmodifsDisplay('edit-post-notify-my-network');
                echo($display->show());
                exit();
            }
            //le post est un post normal
            else{
                $display = new btmodifsDisplay('author');
                echo($display->show());
                exit();
            }
        }
        else{
            //sert pour le controle de l'action en cours au cas ou quelqu'un s'amuserai a mettre post-text-active dans le html pout activer le mode edition
            Session::getInstance()->setCurentAction('viewingPost', $postid);
            $display = new btmodifsDisplay('viewer');
            echo($display->show());
            exit();
        }
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