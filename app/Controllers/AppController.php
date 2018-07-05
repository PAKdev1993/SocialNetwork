<?php

namespace app\Controllers;

use app\App;

use core\Controller\Controller;
use core\Session\Session;

class AppController extends Controller
{
    public function __construct($pageName, $user = false)
    {
        $this->UserToDisplay =      $user;
        $this->myself =             Session::getInstance()->read('auth');
        $this->langFile =           App::getLangModel()->getPageLangFile($pageName);
        $this->langErrorFiles =     App::getLangModel()->getLangFilesError();
        $this->langFooter =         App::getLangModel()->getLangFooter();

        $this->we_langsArray =      App::getLangModel()->getLangsFromDb();

        $this->viewPath =           ROOT . 'app/Views/';
        $this->viewPathAdmin =      ROOT . 'app/Views/Admin/';

        $this->displayPath =        ROOT . 'app/Displays/';
        $this->displayPathAdmin =   ROOT . 'app/Displays/Admin/';

        $this->defaultTemplate =    ROOT . 'app/Views/Templates/default/';
        $this->adminTemplate =      ROOT . 'app/Views/Templates/Admin/';
    }
}