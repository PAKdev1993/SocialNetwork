<?php
require_once('app/Autoloader.php');

use app\Autoloader;
use app\App;

use app\Controllers\Landing\Landing\LandingController;
use app\Controllers\Permalink\Post\PermaPostController;
use app\Table\Posts\PostModel;

use core\Tmp\Tmp;
use core\Session\Session;

Autoloader::register();

//DEFINES
define('ROOT', App::loadConfigAJAX()->getROOT());

//AUTH, SESSION, COOKIES
$auth =         App::getAuth();
$session =      Session::getInstance();
$sessionAuth =  Session::getInstance()->read('auth');

//SESSION PREPARE
Session::getInstance()->delete('current-action');
Session::getInstance()->delete('current-state');


//redirect
/*
 * AUTH IS LOGGED
 */
if($auth->isLogged())
{
    //vider tmp folder
    $tmp = new Tmp();
    $tmp->deleteTmpFolder();

    if(isset($_GET['p']))
    {
        $page = $_GET['p'];
        /*
         * LANDING
         */
        if ($page == 'post')
        {
            if($_GET['elem'])
            {
                $elemid = $_GET['elem'];
                $model = new PostModel();
                if($model->isMyPostFromId($elemid))
                {
                    $session->setCurentState('owner');
                    $authority = 'Owner';
                    $controller = new PermaPostController($elemid, $authority);
                    $controller->index();
                    exit();
                }
                else{
                    App::redirectNotFound(3);
                }
            }
            else{
                App::redirectNotFound(3);
            }
        }
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
else{
    App::redirectHome();
    exit();
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
    $page = 'landing';
    $controller = new LandingController();
    $controller->index($errors = [], $admin = false);
}

