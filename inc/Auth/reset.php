<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;

Autoloader::register();
if(!empty($_POST['pwd-reset']) && $_POST['pwd-reset'] == $_POST['pwd-match-reset'])
{
    $pwd =      $_POST['pwd-reset'];
    $userid =   $_GET['id'];
    $auth =     App::getAuth();

    $auth->resetPasswordFromPageReset($pwd, $userid);
    $auth->connect($user);

    App::redirectHome();
    exit();
}