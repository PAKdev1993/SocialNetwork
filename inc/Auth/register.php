<?php
use inc\Autoloader;
use app\App;
use app\Table\UserModel\UserModel;
use core\Session\Session;

if(!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['nickname']) && !empty($_POST['email']) && !empty($_POST['pwd']) && isset($_POST['invited']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $userModel = new UserModel(App::getDatabase());
    $user = $userModel->getUserFromEmail($_POST['email']);
    if($user)
    {
        $error = 'email already used';
        echo($error);
    }
    else{
        $auth = App::getAuth();
        $session = Session::getInstance();
        //cas ou la page est la page d'invitation classique
        $invited =      $_POST['invited'] == 'false' ? 0 : 1;
        //cas ou la page est la page d'invitation specifique user
        $invitedBy =    $_POST['invitedByToken'] == 'false' ? false : $_POST['invitedByToken'];
        $auth->register($_POST['fname'], $_POST['nickname'], $_POST['lname'], $_POST['pwd'], $_POST['email'], $invited, $invitedBy);
        $session->setFlash('success', 'un email de confirmation vous a été envoyé');
        exit();
    }
}
else
{
    App::redirect('../../index.php');
    exit();
}


//ini_set("SMTP","smtp.free.fr" );
//ini_set('sendmail_from', 'pa.krstic@worldesport.com');
//ini_set('sendmail_path', "C:\wamp\sendmail\sendmail.exe -t -i");