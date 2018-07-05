<?php
use inc\Autoloader;
use core\Files\Images\Images;
use app\Table\UserModel\UserModel;

/*
 * To display posted images
 */
if(isset($_GET['u']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $model =    new UserModel();
    $user =     $model->getUserFromId($_GET['u']);
    $img =      new Images(false, $_GET['u']);

    $fileFolder =           $img->getPublicProfileDir();
    $userProfileImgFolder = $img->getProfilePicUploadDir();
    $thumbFolder =          $img->getThumbsDir();
    $imageName =            'thumb_w200_' . $user->background_profile;
    $img =                  $fileFolder . $userProfileImgFolder . $thumbFolder . $imageName;

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
    die('err');
}