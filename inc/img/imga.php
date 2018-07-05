<?php
use inc\Autoloader;
use core\Files\AppFiles;
use core\Functions;
use app\Table\UserModel\UserModel;
use core\Session\Session;
use app\App;

/*
 * To display Avatar of current user
 */
require_once '../Autoloader.php';
Autoloader::register();

$userModel = new UserModel(App::getDatabase());
$appFiles = new AppFiles();
$userid = Session::getInstance()->read('auth')->pk_iduser;

$HASHPrefixeType = 'file';

$fileFolder = $appFiles->getFilesDir();
$userFolder = $appFiles->getPrefixeiduserHashed().Functions::HASH($HASHPrefixeType, $userid, 'sha1') .'/';
$avatarFolder =  'Avatar';
$imageName = $userModel->getAvatar($userid);
$img =  '../../' . $fileFolder . $userFolder . $avatarFolder . '/' . $imageName;

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


