<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;

Autoloader::register();

//envoyé en post par AJAX: email
if(!empty($_POST))
{
    $msg = App::getAuth()->resetPassword($_POST['email']);
    echo($msg);
    exit();
}
else
{
    App::redirectHome();
    exit();
}