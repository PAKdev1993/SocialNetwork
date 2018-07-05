<?php
use inc\Autoloader;
use core\Files\Images\Images;
use core\Tmp\Tmp;

if(isset($_FILES))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //delete tmp folder
    $tmp = new Tmp();
    $tmp->deleteTmpFolder();

    //prepare upload: img->tmp folder
    $file = end($_FILES); //#todo coriger ca: important pour le fonctionnement de l'add photo mis en place, car avec un seul champ 'File' tou les fichiers uploadé sont mis les un a la suite des autres dans la variable $_FILE
    $finfoObject = new finfo(FILEINFO_MIME);  //#todo finfo est inaccessible dans la classe Image extension=fileinfo.so & php_fileinfo.dll should be activated on php.ini Apache
    $image = new Images($file);
    $msg = $image->checkFile($finfoObject);
    echo($msg);
    unset($_FILES);
    exit();
}