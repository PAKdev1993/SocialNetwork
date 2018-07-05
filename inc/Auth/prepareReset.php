<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;
use app\Table\UserModel\UserModel;

Autoloader::register();
if(isset($_GET['id']) && isset($_GET['token']))
{
    //test si le token est valide ET que l'utilisateur correspond bien au token
    $model = new UserModel();
    $user =  $model->getUserFromUserToken($_GET['id'], $_GET['token']);
    //$langFileRest =
    if($user)
    {
        //Si les variables de reset sont envoyés reinitialise le pwd
        if(!empty($_POST))
        {
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
        }
        //Sinon on affiche le formulaire de reset de pwd
        else{
            $langFile =     App::getLangModel()->getResetPageLangFile();
            $root =         App::loadConfigAJAX()->getROOT();
            require_once($root .'app/Views/Reset/index.php');
        }

    }
    else
    {
        App::redirectNotFound(4);
        exit();
    }
}
else
{
    App::redirect('index');
    exit();
}