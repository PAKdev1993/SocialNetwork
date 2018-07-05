<?php

namespace app\Controllers\Community;

use app\Controllers\AppController;
use core\Header\Header;
use core\Session\Session;

use core\Profile\AppProfile;

class CommunityController extends AppController
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

    public function __construct($admin = false)
    {
        $this->pageType = 'Community';
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
        $this->Header = new Header();
    }

    public function showMyCommunity()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFooter =       $this->langFooter;
        $langFile =         $this->langFile;

        //header bloc
        $header =           $this->Header->getHeader();

        //cover
        $coverPic =         $this->AppProfile->getMyCoverPic();

        //My Contacts
        $contacts =         $this->AppProfile->getMyContacts();

        //My pending contacts
        $pendingContacts =  $this->AppProfile->getMyPendingContacts();

        //My Followers
        $followers =        $this->AppProfile->getMyFollowers();

        //My Recommended contacts aside right
        $recomdContactsRight = $this->AppProfile->getMyRecommendedContactsRightBloc($pageName);

        //My Recommended contacts aside left
        $recommendedContacts = $this->AppProfile->getMyRecommendedContacts();

        $contentArray = compact('pageName','header','we_langsArray','langFile','profilePic','langFooter','coverPic', 'contacts', 'pendingContacts', 'followers', 'recommendedContacts', 'recomdContactsRight', 'currentUser');
        return $contentArray;
    }

    public function showAdminCommunity()
    {
        
    }

    public function index($errors = [], $admin = false){

        if($this->pageAuthority == 'Admin')
        {
            //$this->render($pageName, $variables, $temp);
        }
        if($this->pageAuthority == 'Owner')
        {
            $contentArray = $this->showMyCommunity();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}