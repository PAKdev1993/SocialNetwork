<?php
use inc\Autoloader;
use core\Session\Session;

require_once '../Autoloader.php';
Autoloader::register();

$session = Session::getInstance();
$myid =             $session->read('auth')->pk_iduser;
$iduserDisplayed =  $session->read('whois')['id'];



if($myid != $iduserDisplayed)
{
    echo(json_encode($session->read('whois')));
}
else{
    exit();
}