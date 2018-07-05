<?php
use inc\Autoloader;

use core\Files\Images\Images;
use app\Table\UserModel\UserModel;

use app\Table\Images\ImagesModel;

/*
 * To display posted images
 */
if(isset($_GET['imgname']) && isset($_GET['d']) && isset($_GET['u']))
{
    //#todo ajouter des sécurités au niveau des images
    require_once '../Autoloader.php';
    Autoloader::register();

    $userModel =    new UserModel();
    $userid =       $userModel->getIdFromSlug($_GET['u']);
    $img =          new Images(false, $userid);
    $imgModel =     new ImagesModel();

    $publicPicFolder =  $img->getPublicPicturesDir();
    $dateDir =          $_GET['d'] . '/';
    $thumbsDir =        $img->getThumbsDir();
    $imageName =        'thumb_w200_' . $imgModel->getImageNameFromSlugAndIduser($_GET['imgname'], $userid);
    $img =  $publicPicFolder . $dateDir . $thumbsDir . $imageName;

    if(file_exists($img))
    {
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
        die('err2');
    }
}
else{
    die('err');
}