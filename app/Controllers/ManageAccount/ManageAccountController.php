<?php

namespace app\Controllers\ManageAccount;

use app\Controllers\AppController;
use core\ManageAccount\ManageAccount;
use core\ProfileViews\ProfileViews;
use core\Profile\AppProfile;
use core\Header\Header;

class ManageAccountController extends AppController
{
    //ENTER PARAMETERS
    private $admin;

    //VIEW PARAMETERS
    private $pageType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OBJECTS TO GET VARIABLES
    private $AppProfile;
    private $ProfileView;
    private $Header;
    private $ManageAccount;

    public function __construct($admin = false)
    {
        $this->pageType = 'ManageAccount';
        parent::__construct($this->pageType);
        $this->admin = $admin;

        //DEFINE PAGE AUTHORITY
        if($admin)
        {
            $this->pageAuthority = 'Admin';
        }
       else{
            $this->pageAuthority = 'Owner';
        }

        //SET VIEW PARAMETERS
        $this->view = $this->pageType . '/' . $this->pageAuthority . '/';

        //SET TEMPLATES PARAMETERS
        $this->template = $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OBJECT TO GET VARIABLES
        $this->AppProfile =     new AppProfile();
        $this->ProfileView =    new ProfileViews($this->myself);
        $this->Header =         new Header();
        $this->ManageAccount =  new ManageAccount();
    }

    public function showMyAccountManager()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();

        //Cover
        $coverPic =         $this->AppProfile->getMyCoverPic();

        //Manage Account Panel
        $manageAccountPanel = $this->ManageAccount->getManageAccountOptions();
        
        $contentArray = compact('pageName','header','we_langsArray','langFooter','coverPic','manageAccountPanel','currentUser');
        return $contentArray;
    }

    public function showAdminHome()
    {
        
    }

    public function index()
    {
        if($this->pageAuthority == 'Admin')
        {
            //$this->render($pageName, $variables, $temp);
        }
        if($this->pageAuthority == 'Owner')
        {
            $contentArray = $this->showMyAccountManager();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}