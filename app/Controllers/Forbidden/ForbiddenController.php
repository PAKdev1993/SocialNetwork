<?php

namespace app\Controllers\Forbidden;

use app\Controllers\AppController;
use app\App;


class ForbiddenController extends AppController
{
    private $pageName = 'Forbidden';

    public function __construct()
    {
        parent::__construct();
    }

    public function index($errors = []){
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        //$we_langsArray = App::getLangModel()->getPageLangsFromBd($this->pageName);

        $temp = $this->defaultTemplate;

        //$errorArray = $errors;
        //$variables = compact('langFile', 'dataLangs', 'facebookLoginUrl', 'errorArray', 'we_langsArray');
        $this->render($this->pageName, $variables = [], $temp);
    }
}