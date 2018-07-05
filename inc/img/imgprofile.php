<?php
use inc\Autoloader;
use core\Files\Images\Images;

/*
 * To display Teams images
 */
if(isset($_GET['imgname']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $img = new Images();

    $fileFolder =       $img->getPublicProfileDir();
    $profileImgFolder = $img->getProfilePicUploadDir();
    $thumbFolder =      $img->getThumbsDir();
    $imageName =        'thumb_w200_' . $_GET['imgname']; //#todo dynamiser ca
    $img =              $fileFolder . $profileImgFolder . $thumbFolder . $imageName;

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


