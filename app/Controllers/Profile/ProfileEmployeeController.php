<?php

namespace app\Controllers\Profile;

use app\Controllers\AppController;
use core\Profile\ProfileEmployee;
use app\Table\UserModel\UserModel;
use core\Header\Header;

class ProfileEmployeeController extends AppController
{
    //ENTER PARAMETERS
    private $slug;
    private $admin;

    //VIEW PARAMETERS
    private $pageType;
    private $pageUnderType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OBJECTS TO GET VARIABLES
    private $ProfileEmployee;
    private $UserModel;
    private $Header;

    public function __construct($slug = false, $admin = false)
    {
        $this->pageType = 'Profile';
        parent::__construct($this->pageType);
        $this->slug =           $slug;
        $this->admin =          $admin;

        //DEFINE PAGE AUTHORITY
        if($slug)
        {
            $this->pageAuthority = 'Viewer';

            //SET OBJECT TO GET VARIABLES
            $this->UserModel =      new UserModel();
            $this->UserToDisplay =  $this->UserModel->getUserFromSlug($slug);
            $this->ProfileEmployee =   new ProfileEmployee($this->UserToDisplay);
        }
        if($admin)
        {
            $this->pageAuthority = 'Admin';
        }
        if(!$slug && !$admin)
        {
            $this->pageAuthority = 'Owner';
            $this->ProfileEmployee =   new ProfileEmployee();
        }

        //SET VIEW PARAMETERS
        $this->pageUnderType =  'ProfileEmployee';
        $this->view =           $this->pageType . '/' . $this->pageAuthority . '/' . $this->pageUnderType . '/';

        //SET TEMPLATES PARAMETERS
        $this->template = $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OBJECTS TO GET VARIABLES
        $this->Header = new Header();
    }

    public function showMyProfile()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageUnderType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFile =         $this->langFile;
        $langErrorFiles =   $this->langErrorFiles;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();

        //cover
        $coverPic =         $this->ProfileEmployee->getMyCoverPic();

        //profile
        $profilePic =       $this->ProfileEmployee->getMyProfilePic();

        //quickinfos
        $quickinfos =       $this->ProfileEmployee->getMyQuickInfos();

        //career
        $careerContent =    $this->ProfileEmployee->getMyCareer();

        //events
        $eventContent =     $this->ProfileEmployee->getMyEvents();

        //MyTimeline
        $timelineContent =  $this->ProfileEmployee->getMyTimeline();

        //ALbum preview
        $albumPreview =     $this->ProfileEmployee->getMyGaleryPreview();

        //Interests
        $interests =        $this->ProfileEmployee->getMyInterests();

        //Summarry
        $summary =          $this->ProfileEmployee->getMySummary();

        //NotifyMynetwork
        $notifyMyNetwork =  $this->ProfileEmployee->getNotifyMyNetworkBloc();

        //My Recommended contacts
        $recmdContacts =    $this->ProfileEmployee->getMyRecommendedContactsRightBloc($pageName);

        //Nb contacts
        $nbContacts =       $this->ProfileEmployee->getMyNbContacts();

        //Nb followers
        $nbFollowers =       $this->ProfileEmployee->getMyNbFolowers();

        //Bloc profile viwers
        $blocProfileViewers =       $this->ProfileEmployee->getBlocProfileViewers();

        $contentArray = compact('pageName','header','we_langsArray','langFile','langErrorFiles','langFooter','profilePic', 'coverPic', 'quickinfos','albumPreview', 'interests', 'notifyMyNetwork', 'recmdContacts', 'summary', 'careerContent', 'eventContent', 'nbContacts', 'nbFollowers', 'timelineContent', 'blocProfileViewers', 'currentUser');
        return $contentArray;
    }

    public function showProfile()
    {
        $pageName =         $this->pageUnderType;
        $currentUser =      $this->myself;
        $userToDisplay =    $this->UserToDisplay;
        $we_langsArray =    $this->we_langsArray;
        $langFile =         $this->langFile;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =                   $this->Header->getHeader();

        //cover
        $coverPic =                 $this->ProfileEmployee->getUserCoverPic();

        //profile
        $profilePic =               $this->ProfileEmployee->getUserProfilePic();

        //quickinfos
        $quickinfos =               $this->ProfileEmployee->getUserQuickInfos();

        //Employee career
        $careerContent =            $this->ProfileEmployee->getUserEmployeeCareer();

        //Employee events
        $eventContent =             $this->ProfileEmployee->getUserEmployeeEvents();

        //MyTimeline
        $timelineContent =          $this->ProfileEmployee->getUserTimeline();

        //ALbum preview
        $albumPreview =             $this->ProfileEmployee->getUserGaleryPreview();

        //Employee summarry
        $summary =                  $this->ProfileEmployee->getUserSummary();

        //Interests
        $interests =                $this->ProfileEmployee->getUserInterests();

        //My Recommended contacts
        $recmdContacts =            $this->ProfileEmployee->getMyRecommendedContactsRightBloc($pageName);

        //Nb contacts
        $profileBlocContacts  =     $this->ProfileEmployee->getUserProfileContacts();

        //Bloc followers
        $profileBlocFollowers =     $this->ProfileEmployee->getUserProfileFollowers();

        $contentArray = compact('pageName','header','we_langsArray','langFile','langFooter','profilePic', 'coverPic', 'quickinfos','albumPreview', 'interests', 'notifyMyNetwork', 'recmdContacts', 'summary', 'careerContent', 'eventContent', 'timelineContent', 'currentUser', 'profileBlocContacts', 'nbFollowers', 'profileBlocFollowers', 'userToDisplay');
        return $contentArray;
    }

    public function showAdminProfile()
    {
        //
    }

    public function index()
    {
        if($this->pageAuthority == 'Viewer')
        {
            $contentArray = $this->showProfile();
            $this->render($this->view, $contentArray, $this->template);
        }
        if($this->pageAuthority == 'Owner')
        {
            $contentArray = $this->showMyProfile();
            $this->render($this->view, $contentArray, $this->template);
        }
        if($this->pageAuthority == 'Admin')
        {
            $contentArray = $this->showAdminProfile();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}