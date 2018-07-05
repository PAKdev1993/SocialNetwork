<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;
use core\Cookie;
use app\Table\UserModel\UserModel;

Autoloader::register();

//appel lor du chargement de la page la première fois que l'utilisateur vient sur le site
if(isset($_POST['lang'])){

    $cookie = App::getCookie();
    $cookie->write('langwe', $_POST['lang'], 'month');

    //ajout de la langue a la table user pour l'user s'il est defini
    $model = new UserModel();
    $model->updateUserLanguage($_POST['lang']);
}

//appel lor de l'ajout d'une langue dans l'utilitaire
if(isset($_POST['newlang'])){
    $langModel = App::getLangModel();
    $langModel->addLanguageToLanguages($_POST['newlang']);
    $langModel->addLanguageToPage('landing', $_POST['newlang']);
}