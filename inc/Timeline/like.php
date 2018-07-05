<?php
use inc\Autoloader;
use core\Timeline\Like;
use core\Session\Session;

if(isset($_POST['datas']) && isset($_POST['rate']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $datasArray = json_decode($_POST['datas']);
    $postid = $datasArray->wepid;

    $like = new Like();

    if(Session::getInstance()->checkValueInSession($postid, 'PostDisplayed'))
    {
        if($_POST['rate'] == '+')
        {
            $like->LikePost($postid);
        }
        if($_POST['rate'] == '-')
        {
            $like->unLikePost($postid);
        }
    }
}