<?php
use inc\Autoloader;

use app\App;
use core\Files\Images\Images;
use app\Table\UserModel\UserModel;

//#todo fichier qui fait la meme chose que imgprofile mais pour la timeline, fait qq chose a propose de ca
/*
 * To display Teams images
 */
if(isset($_GET['imgname']) && isset($_GET['sl']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $model = new UserModel(App::getDatabase());
    $user = $model->getUserFromSlug($_GET['sl']);
    $img =      new Images(false, $user->pk_iduser);
    
    $fileFolder =       $img->getPublicProfileDir();
    $coverImgFolder =   $img->getCoverPicUploadDir();
    $thumbFolder =      $img->getThumbsDir();
    $imageName =        'thumb_w1200_' . $_GET['imgname']; //#todo dynamiser ca
    $img =              $fileFolder . $coverImgFolder . $thumbFolder . $imageName;

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


