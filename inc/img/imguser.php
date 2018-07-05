<?php
use inc\Autoloader;

use app\App;
use app\Table\UserModel\UserModel;
use core\Files\Images\Images;

/*
 * To display Teams images
 */
if(isset($_GET['sl']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $model = new UserModel(App::getDatabase());
    $user = $model->getUserFromSlug($_GET['sl']);
    $img = new Images(false, $user->pk_iduser);

    $fileFolder =           $img->getPublicProfileDir();
    $userProfileImgFolder = $img->getProfilePicUploadDir();;
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
    echo('err');
}


