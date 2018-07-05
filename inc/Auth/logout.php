<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;
use core\Session\Session;

Autoloader::register();

$session = Session::getInstance();
App::getAuth()->logout($session);
Session::getInstance()->setFlash('success', 'Vous etes maintenant déconnecté');
App::redirect('../../index.php');