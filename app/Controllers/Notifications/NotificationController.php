<?php
namespace app\Controllers\Notifications;

use app\Controllers\AppController;
use core\Notifications\NotificationsManager;
use core\ProfileViews\ProfileViews;
use core\Profile\AppProfile;
use core\Header\Header;

class NotificationController extends AppController
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
    private $Header;
    private $NotificationsManager;

    public function __construct($admin = false)
    {
        $this->pageType = 'Notifications';
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
        $this->Header =         new Header();
        $this->NotificationsManager = new NotificationsManager();

        //OPERATIONS PREALABLES
        $notifManager = new NotificationsManager();
        $notifManager->resetMyNotifs();
    }

    public function showMyNotifications()
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
        $notifications =        $this->NotificationsManager->getMyNotifications();

        $contentArray = compact('pageName','header', 'we_langsArray','langFooter','profilePic', 'coverPic','timelineContent', 'recomdContactsRight', 'notifications', 'currentUser');
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
            $contentArray = $this->showMyNotifications();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}