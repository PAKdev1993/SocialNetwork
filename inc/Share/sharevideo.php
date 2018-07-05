<?php
use inc\Autoloader;
use core\Files\Images\Videos;

if(isset($_FILES))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $file = end($_FILES); //#todo coriger ca: important pour le fonctionnement de l'add photo mis en place, car avec un seul champ 'File' tou les fichiers uploadé sont mis les un a la suite des autres dans la variable $_FILE
    $video = new Video($file);
    $msg = $video->checkFile($finfoObject);
    echo($msg);
    unset($_FILES);
}