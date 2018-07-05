<?php
/* design parthern factory: classe qui charge les autres classes*/
namespace app;

use app\Table\appDates;
use core\Database\Database;
use core\Notifications\NotificationsManager;
use core\Session\Session;
use core\Cookie\Cookie;
use core\Auth\FbLogin\FbAPIManager;
use core\Auth\Auth;
use core\Langs\Langs;
use core\ConfigAJAX;
use app\Table\UserModel\UserModel;
use app\Table\LangModel\LangModel;

class App
{
    static $db          = null;
    static $auth        = null;
    static $cookie      = null;
    static $session     = null;
    static $lang        = null;
    static $facebookAPIManager = null;
    static $appDates;

    static $langModel   = null;
    static $userModel   = null;

    static $configAJAX  = null;
    static $mainuser    = null;

    static $notifManager = null;

    static $invitationLink = null;

    static $weLink = null;

    static function getDatabase()
    {
        if(!self::$db)
        {
            self::$db = new Database(/*optionnal parameters*/);
        }
        return self::$db;
    }

    static function getAuth()
    {
        if(!self::$auth)
        {
            self::$auth = new Auth();
        }
        return self::$auth;
    }

    static function getCookie()
    {
        if(!self::$cookie)
        {
            self::$cookie = new Cookie(Session::getInstance());
        }
        return self::$cookie;
    }

    static function getLang()
    {
        if(!self::$lang)
        {
            self::$lang = new Langs(App::getDatabase(), App::getCookie());
        }
        return self::$lang;
    }

    static function redirect($page)
    {
        header("Location:". $page);
        exit();
    }

    static function redirectHomeTmp()
    {
        $url = "localhost/WEindev/index.php?p=home";
        header("Location:$url");
    }

    static function redirectHome()
    {
        $url = "/WEindev/index.php?p=home";
        header("Location:$url");
    }

    static function redirectNotFound($errCode)
    {
        $url = "/WEindev/index.php?p=notfound&err=$errCode";
        header("Location:$url");
    }

    static function redirectLanding()
    {
        $url = App::loadConfigAJAX()->getROOT() . "index.php";
        header("Location:$url");
    }

    //init facebook object to return loginUrl that use in LandingController
    static function getFacebookAPIManager()
    {
        if(!self::$facebookAPIManager)
        {
            self::$facebookAPIManager = new FbAPIManager();
        }
        return self::$facebookAPIManager;
    }

    static function getLangModel()
    {
        if(!self::$langModel)
        {
            self::$langModel = new LangModel(App::getDatabase());
        }
        return self::$langModel;
    }

    static function getUsergModel()
    {
        if(!self::$userModel)
        {
            self::$userModel = new UserModel(App::getDatabase());
        }
        return self::$userModel;
    }

    static function loadConfigAJAX()
    {
        if(!self::$configAJAX)
        {
            self::$configAJAX = new ConfigAJAX();
        }
        return self::$configAJAX;
    }

    static function getMainUser()
    {
        if(!self::$mainuser)
        {
            $model = new UserModel();
            self::$mainuser = $model->getUserFromId(1);
        }
        return self::$mainuser;
    }

    static function getNotificationManager()
    {
        if(!self::$notifManager)
        {
            self::$notifManager = new NotificationsManager();;
        }
        return self::$notifManager;
    }

    static function getWeInvitationLink()
    {
        if(!self::$invitationLink)
        {
            self::$invitationLink = 'http://worldesport.com/index.php?p=invitation';
        }
        return self::$invitationLink;
    }

    static function getWElink()
    {
        if(!self::$weLink)
        {
            self::$weLink = 'http://worldesport.com/index.php';
        }
        return self::$weLink;
    }
}