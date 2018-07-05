<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Comments\displayComment;
use app\Table\Comments\CommentModel;
use core\Timeline\Timeline;

require_once '../Autoloader.php';
Autoloader::register();
$valid = true;
if(Session::getInstance()->read('current-state')['state'] != 'owner')
{
    $valid = false;
}
if($valid)
{
    $coreTimeline = new Timeline();
    $nbPostsDisplayed = $coreTimeline->getNbPostDisplayed(); //#todo: cas non traité: le cas ou un user a share qqchose, alors le nombre de post display est incrémenté de 1 ce qui fera chercher la fonction a partir n+1 user et non a partir du n
    $content = $coreTimeline->getNextTimelineElem($nbPostsDisplayed);
    echo($content);
    exit();
}
else{
    echo('err');
    exit();
}