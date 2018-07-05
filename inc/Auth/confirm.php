<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;
use core\Session\Session;

Autoloader::register();

$auth = App::getAuth();
$session = Session::getInstance();
$parrainage = false;
//-------------------------------------------
// TEST SI LE LIEN EST UN LIEN DE PARRAINNAGE
if(isset($_GET['tokenParrain']))
{
    $parrainage = true;
}
//-------------------------------------------
// CONFIRMATION
if($parrainage)
{
    if($auth->confirm($_GET['id'], $_GET['token'], $_GET['tokenParrain']))
    {
        $session->setFlash('success', "Votre compte a bien été validé");
        App::redirect('../../index.php?p=home');
        exit();
    }
    else
    {
        $session->setFlash('danger', "Ce token n'est plus valide");
        App::redirect('../../index.php');
        exit();
    }
}
else{
    if($auth->confirm($_GET['id'], $_GET['token']))
    {
        $session->setFlash('success', "Votre compte a bien été validé");
        App::redirect('../../index.php?p=home');
        exit();
    }
    else
    {
        $session->setFlash('danger', "Ce token n'est plus valide");
        App::redirect('../../index.php');
        exit();
    }
}