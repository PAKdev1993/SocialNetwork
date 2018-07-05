<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;

Autoloader::register();

if(isset($_POST['dataLangs']) && isset($_POST['lang']) && isset($_POST['page']))
{
    $arrayDatalangTraduce = array();
    //recupère les dataLangs comprenant ceux avec le suffixe -text
    $dataLangsWithSuffixe = explode(" ", $_POST['dataLangs']);
    foreach ($dataLangsWithSuffixe as $dataLangWithSuffixe){
        $pieces = explode("-", $dataLangWithSuffixe);
        $dataLang = $pieces[0];
        $traduce = App::getLangModel()->getTraduceFromDb($dataLang, $_POST['lang'], $_POST['page']);
        $arrayDatalangTraduce[$dataLang] = $traduce;
    };
    print json_encode($arrayDatalangTraduce);
}
else{
    App::redirect('../../index');
}