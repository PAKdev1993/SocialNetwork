<?php
//appel lor duy chargement de la page la première fois que l'utilisateur vient sur le site
if(isset($_POST['lang'])){
    require_once '../Autoloader.php';
    Autoloader::register();

    $cookie = App::getCookie();
    $cookie->setCookie('langwe', $_POST['lang'], 'month');
}

//appel lor de l'ajout d'une langue dans l'utilitaire
if(isset($_POST['newlang'])){
    require_once '../Autoloader.php';
    Autoloader::register();

    $langControler = App::getLangControler();
    $langControler->addLanguageToLanguages($_POST['newlang']);
    $langControler->addLanguageToPage('landing', $_POST['newlang']);
}