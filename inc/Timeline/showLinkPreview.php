<?php
use inc\Autoloader;
use app\Displays\LinkPreview\displayLinkPreviews;

if(isset($_POST['link']) && isset($_POST['type']) && isset($_POST['sitename']) && isset($_POST['title']) && isset($_POST['imageLink']) && isset($_POST['description']) && isset($_POST['empty']) && isset($_POST['post']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //defini si la preview est disponnible
    if($_POST['empty'] == '1'){
        $empty = true;
    }
    else{
        $empty = false;
    }

    //defini si la preview est celle de l'edition d'un post ou non
    if($_POST['post'] == '1'){
        $post = true;
    }
    else{
        $post = false;
    }

    $display = new displayLinkPreviews($_POST['type'], $_POST['link'], $_POST['sitename'], $_POST['title'], $_POST['description'], $_POST['imageLink'], $post, $empty);
    echo($display->show());
    exit();
}
else{
    echo('err');
}