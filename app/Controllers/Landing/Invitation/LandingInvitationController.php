<?php

namespace app\Controllers\Landing\Invitation;

use app\Controllers\AppController;
use app\App;

class LandingInvitationController extends AppController
{
    //ENTER PARAMETERS
    private $admin;

    //VIEW PARAMETERS
    private $pageType;
    private $pageUnderType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OTHER LANG VARIABLES
    private $langCodOfConduct;
    private $langwTermsandconditions;
    private $langPrivacy;

    public function __construct($admin = false)
    {
        $this->pageType =   'Landing';
        parent::__construct($this->pageType);
        $this->admin =      $admin;

        //DEFINE PAGE AUTHORITY
        if(!$admin)
        {
            $this->pageAuthority = 'Viewer';
        }

        //SET VIEW PARAMETERS
        $this->pageUnderType = 'Invitation';
        $this->view =       $this->pageType . '/' .$this->pageUnderType . '/' . $this->pageAuthority . '/';

        //SET TEMPLATE PARAMETERS
        $this->template =   $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OTHER LANG VARIABLES
        $this->langCodOfConduct =       App::getLangModel()->getCodeOfConductLangFile();
        $this->langwTermsandconditions =App::getLangModel()->getTermsAndConfitionsLangFile();
        $this->langPrivacy =            App::getLangModel()->getPrivacyLangFile();
    }

    public function showInvitationLanding()
    {
        $langFile =                 $this->langFile;
        $langCodOfConduct =         $this->langCodOfConduct;
        $langwTermsandconditions =  $this->langwTermsandconditions;
        $langPrivacy =              $this->langPrivacy;
        $facebookLoginUrl =         App::getFacebookAPIManager()->getLoginUrl();
        $pageName =                 $this->pageType;
        $we_langsArray =            $this->we_langsArray;

        $variables = compact('langFile', 'langCodOfConduct', 'langwTermsandconditions', 'langPrivacy', 'dataLangs', 'facebookLoginUrl', 'errorArray', 'we_langsArray', 'pageName');
        return $variables;
    }

    public function showAdminLanding()
    {
        $langFile =         $this->langFile;
        $facebookLoginUrl = App::getFacebookAPIManager()->getLoginUrl();
        $pageName =         $this->pageType;
        $we_langsArray =    $this->we_langsArray;
        $currentLang =      App::getLangModel()->getCurrentLang();

        $variables = compact('langFile', 'facebookLoginUrl', 'errorArray', 'pageName', 'we_langsArray', 'currentLang');
        return $variables;

    }
    public function index(){

        if($this->pageAuthority == 'Admin')
        {
            $variables = $this->showInvitationLanding();
            $this->render($this->view, $variables, $this->template);
        }
        if($this->pageAuthority == 'Viewer')
        {
            $variables = $this->showInvitationLanding();
            $this->render($this->view, $variables, $this->template);
        }
    }
}