<?php
use inc\Autoloader;
use core\Session\Session;
use core\Timeline\ProfileTimeline;
use app\Table\UserModel\UserModel;

require_once '../Autoloader.php';
Autoloader::register();
$valid = true;
if(Session::getInstance()->read('current-state')['state'] == 'owner')
{
    $coreTimeline = new ProfileTimeline(Session::getInstance()->read('auth'));
    $nbPostsDisplayed = $coreTimeline->getNbPostDisplayed(); //#todo: cas non traité: le cas ou un user a share qqchose, alors le nombre de post display est incrémenté de 1 ce qui fera chercher la fonction a partir n+1 user et non a partir du n
    $content = $coreTimeline->getNextMyTimeline($nbPostsDisplayed);
    echo($content);
    exit();
}
if(Session::getInstance()->read('current-state')['state'] == 'viewer')
{
    //get user to display his nexts posts
    $model = new UserModel();
    $userid = Session::getInstance()->read('current-state')['userid'];
    $user = $model->getUserFromId($userid);

    //Display nexts posts
    $coreTimeline = new ProfileTimeline($user);
    $nbPostsDisplayed = $coreTimeline->getNbPostDisplayed(); //#todo: cas non traité: le cas ou un user a share qqchose, alors le nombre de post display est incrémenté de 1 ce qui fera chercher la fonction a partir n+1 user et non a partir du n
    $content = $coreTimeline->getNextTimeline($nbPostsDisplayed);
    echo($content);
    exit();
}
else{
    echo('err');
    exit();
}