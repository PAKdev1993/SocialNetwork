<?php

namespace app\Controllers\ProfileView;

use app\Controllers\AppController;
use core\Notifications\NotificationsManager;
use core\ProfileViews\ProfileViews;
use core\Profile\AppProfile;
use core\Header\Header;

class ProfileViewController extends AppController
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

    public function __construct($admin = false)
    {
        $this->pageType = 'ProfileViews';
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

        //OPERATIONS PREALABLES
        $notifManager = new NotificationsManager();
        $notifManager->resetNotifsForProfileViewers();
    }

    public function showMyProfileView()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =             $this->pageType;
        $currentUser =          $this->myself;
        $we_langsArray =        $this->we_langsArray;
        $langFooter =           $this->langFooter;

        //header bloc
        $header =               $this->Header->getHeader();

        //Cover
        $coverPic =             $this->AppProfile->getMyCoverPic();

        //ProfilePic
        $profilePic =           $this->AppProfile->getMyProfilePic();

        //Recommended contacts
        $recomdContactsRight =  $this->AppProfile->getMyRecommendedContactsRightBloc($pageName);
        
        //Profile viewers
        $profileViewers =       $this->ProfileView->getMyProfileViewers();

        $contentArray = compact('pageName','header','we_langsArray','langFooter','profilePic', 'coverPic','timelineContent', 'recomdContactsRight', 'profileViewers', 'currentUser');
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
            $contentArray = $this->showMyProfileView();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}