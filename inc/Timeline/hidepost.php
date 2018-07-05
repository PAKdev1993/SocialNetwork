<?php
use inc\Autoloader;
use core\Session\Session;
use core\Timeline\Posts;

if(isset($_POST['datas']))
{
    //si on est ici c'est que le text a forcément été modifié
    require_once '../Autoloader.php';
    Autoloader::register();

    $datasArray = json_decode($_POST['datas']);
    $postid = $datasArray->wepid;
    $userid = $datasArray->weid;
    $currentUser = Session::getInstance()->read('auth');

    //on veridfie que le post que l'user essaye de modifier fait bien partis des posts affichés
    if(Session::getInstance()->checkValueInSession($postid, 'PostDisplayed'))
    {
        //controle que le post de l'user a masquer n'est pas un de nos posts
        //pas plus de controle nescessaire si l'user veux se foutre ds la merde en masquant les post des autres a ses yeux qu'il le fasse
        if($userid != $currentUser)
        {
            //sert pour le controle de l'action en cours au cas ou quelqu'un s'amuserai a mettre post-text-active dans le html pout activer le mode edition
            if(Session::getInstance()->read('current-action')['actionname'] == 'viewingPost' && Session::getInstance()->read('current-action')['idelem'] == $postid)
            {
                $post = new Posts();
                $post->hidePost($postid);
                echo('valid');
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