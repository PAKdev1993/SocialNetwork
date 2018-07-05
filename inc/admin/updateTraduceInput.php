<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;

Autoloader::register();

if(isset($_POST['newTraduce']) && isset($_POST['dataLang']) && isset($_POST['lang']) && isset($_POST['page']))
{
    App::getLangModel()->updateTraduce($_POST['newTraduce'], $_POST['dataLang'], $_POST['lang'], $_POST['page']);
}
else{
    App::redirect('../../index');
}