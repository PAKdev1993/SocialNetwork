<?php

namespace app\Controllers\Home;

use app\App;
use app\Controllers\AppController;

use app\Table\UserModel\displayUser;

use core\Timeline\Timeline;

use core\Profile\AppProfile;

use core\Header\Header;

class HomeController extends AppController
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
    private $displayUser;
    private $Timeline;
    private $Header;

    public function __construct($admin = false)
    {
        $this->pageType = 'Home';
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
        $this->Timeline =       new Timeline();
        $this->displayUser =    new displayUser($this->myself);
        $this->Header =         new Header();
    }

    public function showMyHome()
    {
        $pageName =         $this->pageType;
        $headerLangFile =   App::getLangModel()->getHeaderLangFile();
        $fileErrorLangFile= App::getLangModel()->getLangFilesError();
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFile =         $this->langFile;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();

        //profile
        $profilePic =       $this->displayUser->showMyProfilePic();

        //get Timeline & users posts and users id in the same order, to make indexs matchs
        $timelineContent =  $this->Timeline->getTimelineElem();

        //Recommended contacts
        $recomdContacts =   $this->AppProfile->getMyRecommendedContactsRightBloc($pageName);

        $contentArray = compact('pageName','we_langsArray','langFile','headerLangFile','langFooter', 'fileErrorLangFile','header','profilePic','timelineContent', 'recomdContacts', 'currentUser');
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
            $contentArray = $this->showMyHome();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}