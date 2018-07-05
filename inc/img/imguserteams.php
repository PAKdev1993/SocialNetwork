<?php
use inc\Autoloader;
use core\Files\Images\Images;

/*
 * To display Teams images
 */
if(isset($_GET['imgname']) && isset($_GET['u']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $img = new Images(false, $_GET['u']);
    
    $fileFolder =       $img->getPublicProfileDir();
    $teamImgFolder =    $img->getMyTeamsUploadDir();
    $thumbFolder =      $img->getThumbsDir();
    $imageName =        'thumb_w200_' . $_GET['imgname'];
    $img =              $fileFolder . $teamImgFolder . $thumbFolder . $imageName;
    die($img);

    $extension = explode('.', $imageName)[1];
    $image = '';

    if($extension == 'jpg' || $extension == 'jpeg')
    {
        header ("Content-type: image/jpeg");
        $image = imagecreatefromjpeg($img);
        imagejpeg($image);
    }
    if($extension == 'png')
    {
        header ("Content-type: image/png");
        $image = imagecreatefrompng($img);
        imagepng($image);
    }
    if($extension == 'gif')
    {
        header ("Content-type: image/gif");
        $image = imagecreatefromgif($img);
        imagegif($image);
    }
}
else{
    die('ok');
}


