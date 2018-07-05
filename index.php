<?php
require_once('app/Autoloader.php');
use app\Autoloader;
use app\App;

use app\Controllers\Landing\Landing\LandingController;
use app\Controllers\Landing\Parrain\LandingParrainController;
use app\Controllers\Home\HomeController;
use app\Controllers\Profile\ProfileGamerController;
use app\Controllers\Profile\ProfileEmployeeController;
use app\Controllers\Profile\AlbumController;
use app\Controllers\Community\CommunityController;
use app\Controllers\ProfileView\ProfileViewController;
use app\Table\ProfileViewers\ProfileViewModel;
use app\Controllers\SearchPage\SearchPageController;
use app\Controllers\ManageAccount\ManageAccountController;
use app\Controllers\Notifications\NotificationController;
use app\Controllers\PageNotFound\PageNotFoundController;
use app\Controllers\Landing\Invitation\LandingInvitationController;
use app\Controllers\ComingSoon\Lobby\ComingSoonLobbyController;
use app\Controllers\ComingSoon\Opportunities\ComingSoonOpportunitiesController;
use app\Controllers\Legals\TermsAndConditionsController;
use app\Controllers\Legals\PrivacyController;
use app\Controllers\Legals\CodeOfConductController;
use app\Controllers\Aboutus\AboutUsController;
use core\MessageCenter\Message;
use app\Controllers\MessageCenter\MessageCenterController;
use core\Auth\Auth;
use core\Tmp\Tmp;
use core\Session\Session;

use app\Table\UserModel\UserModel;

Autoloader::register();
//DEFINES
define('ROOT', App::loadConfigAJAX()->getROOT());

//AUTH, SESSION, COOKIES
$auth = App::getAuth();
//var_dump($auth);
App::getCookie()->connectFromCookie();
//setcookie('remember-me', null, -1);
$session =      Session::getInstance();
$sessionAuth =  $session->read('auth');
$userModel =    new UserModel();

//$model = new Auth();
//$model->generateSponsorToken();

//SESSION PREPARE
$session->delete('current-action');
$session->delete('current-state');

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

//$updir = $tmp->createIfNotExistPostTmp(15);
//var_dump($updir);
//var_dump($_SESSION['chatHistory']);
//var_dump($_SESSION['tsChatBoxes']);
//var_dump($_SESSION['openChatBoxes']);
//var_dump($_SESSION);
//die('ok');
//Session::getInstance()->delete('auth');
//var_dump($_COOKIE);
//var_dump($auth->isLogged());
//var_dump($auth->isAdmin());
//var_dump(ROOT);
//var_dump($daylyDate = date('d \/ m \/ Y'));
/*$db = App::getDatabase();
$date = $db->query("SELECT date FROM we__posts WHERE fk_iduser = ? ORDER BY date ASC LIMIT 0,1",[
    Session::getInstance()->read('auth')->pk_iduser
])->fetch()->date;
var_dump($date);
var_dump(date("Y-m-d H:i:s"));
$timeFirst  = strtotime($date);
var_dump($timeFirst);
$timeSecond = strtotime(date("Y-m-d H:i:s"));
var_dump($timeSecond);
$differenceInSeconds = $timeSecond - $timeFirst;
var_dump($differenceInSeconds);
$hour = 60*60;
$day = $hour * 24;
$week = $day * 24;
$month = $day * 31;
$year = $month * 12;
var_dump("hour", $differenceInSeconds/$hour);
var_dump("day", $differenceInSeconds/$day);
var_dump("week", $differenceInSeconds/$week);
var_dump("month", $differenceInSeconds/$month);
die('ok');*/

//redirect
/*
 * AUTH IS LOGGED
 */
if($auth->isLogged())
{
    //vider tmp folder
    $tmp = new Tmp();
    $tmp->deleteTmpFolder();
    //$chat = new Message();
    //if (isset($_GET['action']) && $_GET['action'] == "chatheartbeat") { $chat->chatHeartbeat(); }
   // if (isset($_GET['action']) && $_GET['action'] == "sendchat") { $chat->sendChat(); }
    //if (isset($_GET['action']) && $_GET['action'] == "closechat") { $chat->closeChat(); }

    if (!$session->read('chatHistory'))
    {
        $session->write('chatHistory', array());
    }

    if (!$session->read('openChatBoxes')) {
        $session->write('openChatBoxes', array());
    }
    if(isset($_GET['p']))
    {
        $page = $_GET['p'];
        /*
         * LANDING
         */
        if ($page == 'landing')
        {
            $page = 'home';
        }
        /*
         * HOME
         */
        if ($page == 'home')
        {
            $session->setCurentState('owner'); //#todo peut etre changer ca en mettant un troisème etat, genre 'personnalpart' ou je sais pas quoi ds ce gout la
            $controller = new HomeController();
            $controller->index();
            exit();
        }
        /*
         * MY COMMUNITY
         */
        if ($page == 'mycommunity')
        {
            $session->setCurentState('owner');
            $controller = new CommunityController();
            $controller->index();
            exit();
        }
        /*
         * MY PROFILE VIEWERS
         */
        if ($page == 'profileviews')
        {
            $session->setCurentState('owner');
            $controller = new ProfileViewController();
            $controller->index();
            exit();
        }
        /*
         * MY NOTIFICATIONS
         */
        if ($page == 'notifications')
        {
            $session->setCurentState('owner');
            $controller = new NotificationController();
            $controller->index();
            exit();
        }
        /*
         * MY SEARCH PAGE
         */
        if ($page == 'search')
        {
            $session->setCurentState('owner');
            $controller = new SearchPageController();
            $controller->index();
            exit();
        }
        /*
         * MY MANAGE ACCOUNT
         */
        if ($page == 'manageaccount')
        {
            $session->setCurentState('owner');
            $controller = new ManageAccountController();
            $controller->index();
            exit();
        }
        /*
         * 404
         */
        if ($page == 'notfound')
        {
            $codeErr = $_GET['err'] ? $_GET['err'] : false;
            $session->setCurentState('viewer');
            $controller = new PageNotFoundController($codeErr);
            $controller->index();
            exit();
        }
        /*
         * COMING SOON LOBY
         */
        if ($page == 'coming-soon-opportunities')
        {
            $session->setCurentState('viewer');
            $controller = new ComingSoonOpportunitiesController();
            $controller->index();
            exit();
        }
        /*
        * COMING SOON OPORTUNITIES
        */
        if ($page == 'coming-soon-lobby')
        {
            $session->setCurentState('viewer');
            $controller = new ComingSoonLobbyController();
            $controller->index();
            exit();
        }
        /*
         * TERMS AND CONDITIONS
         */
        if ($page == 'terms-and-conditions')
        {
            $session->setCurentState('viewer');
            $controller = new TermsAndConditionsController();
            $controller->index();
            exit();
        }
        /*
         * PRIVACY
         */
        if ($page == 'privacy')
        {
            $session->setCurentState('viewer');
            $controller = new PrivacyController();
            $controller->index();
            exit();
        }
        /*
         * CODE OF CONDUCT
         */
        if ($page == 'code-of-conduct')
        {
            $session->setCurentState('viewer');
            $controller = new CodeOfConductController();
            $controller->index();
            exit();
        }
        /*
         * ABOUT US
         */
        if ($page == 'about-us')
        {
            $session->setCurentState('viewer');
            $controller = new AboutUsController();
            $controller->index();
            exit();
        }
        /*
         * ABOUT US
         */
        if ($page == 'messages')
        {
            $session->setCurentState('owner');
            $controller = new MessageCenterController();
            $controller->index();
            exit();
        }
        /*
         * CHAT instance
         */
        /*
         * USER
         */
        if($page == 'profile')
        {
            if(isset($_GET['u']))
            {
                $userslug = $_GET['u'];
                $model = new UserModel();
                if($model->userExistFromSlug($_GET['u']))
                {
                    if(isset($_GET['s']))
                    {
                        $part = $_GET['s'];

                        /*
                         * MY PROFILE
                         */
                        if($userslug == $sessionAuth->slug)
                        {
                            /*
                             * MY PROFILE EMPLOYEE
                             */
                            if($part == 'employee')
                            {
                                $session->setCurentState('owner');
                                $controller = new ProfileEmployeeController();
                                $controller->index();
                                exit();
                            }
                            /*
                             * MY PROFILE ALBUM
                             */
                            if($part == 'album')
                            {
                                $session->setCurentState('owner');
                                $controller = new AlbumController();
                                $controller->index();
                                exit();
                            }
                            /*
                             * MY PROFILE GAMER
                             */
                            else{
                                $session->setCurentState('owner');
                                $controller = new ProfileGamerController();
                                $controller->index();
                                exit();
                            }
                        }
                        /*
                         * SOMEONE ELSE PROFILE
                         */
                        if($userslug != $sessionAuth->slug)
                        {
                            /*
                             * PROFILE EMPLOYEE
                             */
                            if($part == 'employee')
                            {
                                //Add viewers
                                $viewerModel =  new ProfileViewModel();
                                $userModel =    new UserModel();
                                $userid =       $userModel->getIdFromSlug($userslug);
                                $viewerModel->addViewerToUser($userid);

                                //add iduser to current state to display timeline show next
                                $session->setCurentState('viewer', $userid);

                                $controller = new ProfileEmployeeController($userslug);
                                $controller->index();
                                exit();
                            }
                            /*
                             * PROFILE ALBUM
                             */
                            if($part == 'album')
                            {
                                $model = new UserModel();
                                $userid = $model->getIdFromSlug($userslug);
                                $session->setCurentState('viewer', $userid);

                                //display
                                $controller = new AlbumController($userslug);
                                $controller->index();
                                exit();
                            }
                            /*
                             * PROFILE GAMER
                             */
                            else{
                                //Add viewers
                                $viewerModel =  new ProfileViewModel();
                                $userModel =    new UserModel();
                                $userid =       $userModel->getIdFromSlug($userslug);
                                $viewerModel->addViewerToUser($userid);

                                //add iduser to current state to display timeline show next
                                $session->setCurentState('viewer', $userid);

                                //display
                                $controller = new ProfileGamerController($userslug);
                                $controller->index();
                                exit();
                            }

                        }
                    }
                    else{
                        /*
                         * YOUR PROFILE DEFAULT (Gamer)
                         */
                        if($userslug == $sessionAuth->slug)
                        {
                            $session->setCurentState('owner');
                            $controller = new ProfileGamerController();
                            $controller->index();
                            exit();
                        }
                        /*
                         * SOMEONE ELSE DEFAULT (Gamer)
                         */
                        if($userslug != $sessionAuth->slug)
                        {
                            //Add viewers
                            $viewerModel =  new ProfileViewModel();
                            $userModel =    new UserModel();
                            $userid =       $userModel->getIdFromSlug($userslug);
                            $viewerModel->addViewerToUser($userid);

                            //add iduser to current state to display timeline show next
                            $session->setCurentState('viewer', $userid);

                            //Display page
                            $controller = new ProfileGamerController($userslug);
                            $controller->index();
                            exit();
                        }
                    }
                }
                else{
                    App::redirectNotFound(1);
                    exit();
                }
            }
            else{
                /*
                 * DEFAULT YOUR PROFILE
                 */
                $session->setCurentState('owner');
                $controller = new ProfileGamerController();
                $controller->index();
                exit();
            }
        }
        /*
         *  HOME
         */
        else{
            App::redirectNotFound(3);
            exit();
        }
    }
    /*
     * DEFAULT HOME
     */
    else{
        App::redirectHome();
        exit();
    }
}

/*
 * AUTH IS ADMIN
 */
if($auth->isAdmin())
{
    if(isset($_GET['p']))
    {
        $page = $_GET['p'];
    }
    else{
        $page = 'landing.admin';
    }
    /*
     * LANDING
     */
    if ($page == 'landing.admin')
    {
        if($auth->isAdmin())
        {
            $controller = new LandingController($admin = true);
            $controller->index();
        }
        else{
            $controller = new LandingController();
            $controller->index();
        }
    }
    /*
     * HOME
     */
    if ($page == 'home.admin')
    {
        if($auth->isAdmin())
        {
            $controller = new LandingController();
            $controller->index($errors = [], $admin = true);
        }
        else{
            $controller = new LandingController();
            $controller->index($errors = [], $admin = false);
        }
    }
    /*
     * MY PROFILE
     */
    if ($page == 'adminWE')
    {
        if($auth->isAdmin())
        {
            $controller = new MyprofileController();
            $controller->index($errors = [], $admin = true);
        }
        else{
            $controller = new MyprofileController();
            $controller->index($errors = [], $admin = false);
        }
    }
    /*
     * MY PROFILE EMPLOYEE
     */
    if ($page == 'employee.admin')
    {
        if($auth->isAdmin())
        {
            $controller = new EmployeeController();
            $controller->index($errors = [], $admin = true);
        }
        else{
            $controller = new EmployeeController();
            $controller->index($errors = [], $admin = false);
        }
    }
    /*
     * DEFAULT LANDING ADMIN
     */
    else{
        $page = 'landing.admin';
    }
}

/*
 * AUTH IS VISITOR
 */
if($auth->isNotLogged())
{
    if(isset($_GET['p']))
    {
        $page = $_GET['p'];
        if($page == 'invitation')
        {
            if(isset($_GET['tki']))
            {
                $controller = new LandingParrainController();
                $controller->index($errors = [], $admin = false);
                exit();
            }
            else{
                $controller = new LandingInvitationController();
                $controller->index($errors = [], $admin = false);
                exit();
            }

        }
        if($page == 'notfound')
        {
            $codeErr = $_GET['err'] ? $_GET['err'] : false;
            $session->setCurentState('public');
            $controller = new PageNotFoundController($codeErr, false, false);
            $controller->index();
            exit();
        }
        else{
            $controller = new LandingController();
            $controller->index($errors = [], $admin = false);
            exit();
        }
    }
    else{
        $controller = new LandingController();
        $controller->index($errors = [], $admin = false);
        exit();
    }
}
