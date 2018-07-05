<?php

namespace app\Controllers\Profile;

use app\Controllers\AppController;
use core\Header\Header;
use core\Profile\ProfileGamer;
use app\Table\UserModel\UserModel;
use core\Session\Session;

#todo Erreure cohérence logique: les fonction prefixées ici with My sont celle liée a l'user owner, celle non prefixée a l'user viewer FAIRE LA MEME CHOSE DS LES DISPLAY
class ProfileGamerController extends AppController
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
    private $ProfileGamer;
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
            $this->ProfileGamer =   new ProfileGamer($this->UserToDisplay);
        }
        if($admin)
        {
            $this->pageAuthority = 'Admin';
        }
        if(!$slug && !$admin)
        {
            $this->pageAuthority = 'Owner';
            $this->ProfileGamer = new ProfileGamer();
        }

        //SET VIEW PARAMETERS
        $this->pageUnderType =  'ProfileGamer';
        $this->view =           $this->pageType . '/' . $this->pageAuthority . '/' . $this->pageUnderType . '/';

        //SET TEMPLATES PARAMETERS
        $this->template = $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OBJECTS TO GET VARIABLES
        $this->Header = new Header();
    }

    public function showMyProfile()
    {
        $pageName =         $this->pageUnderType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFile =         $this->langFile;
        $langErrorFiles =   $this->langErrorFiles;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();
        
        //cover
        $coverPic =         $this->ProfileGamer->getMyCoverPic();

        //profile
        $profilePic =       $this->ProfileGamer->getMyProfilePic();

        //quickinfos
        $quickinfos =       $this->ProfileGamer->getMyQuickInfos();

        //career
        $careerContent =    $this->ProfileGamer->getMyCareer();

        //events
        $eventContent =     $this->ProfileGamer->getMyEvents();

        //Games
        $gameContent =      $this->ProfileGamer->getMyGames();

        //MyTimeline
        $timelineContent =  $this->ProfileGamer->getMyTimeline();

        //Equipments
        $equipmentContent = $this->ProfileGamer->getMyEquipment();

        //ALbum preview
        $albumPreview =     $this->ProfileGamer->getMyGaleryPreview();

        //Summarry
        $summary =          $this->ProfileGamer->getMySummary();

        //Interests
        $interests =        $this->ProfileGamer->getMyInterests();

        //NotifyMynetwork
        $notifyMyNetwork =  $this->ProfileGamer->getNotifyMyNetworkBloc();

        //My Recommended contacts
        $rcmdContacts =     $this->ProfileGamer->getMyRecommendedContactsRightBloc($pageName);

        //Nb contacts
        $nbContacts =       $this->ProfileGamer->getMyNbContacts();

        //Nb followers
        $nbFollowers =       $this->ProfileGamer->getMyNbFolowers();

        //Bloc profile viwers
        $blocProfileViewers = $this->ProfileGamer->getBlocProfileViewers();

        //bloc live
        $blocLive =         $this->ProfileGamer->getMyBlocLive();

        $contentArray = compact('pageName','header','we_langsArray','langFile','langFooter','langErrorFiles', 'profilePic', 'coverPic', 'quickinfos', 'careerContent', 'gameContent', 'eventContent', 'equipmentContent', 'timelineContent','albumPreview', 'summary', 'interests', 'notifyMyNetwork', 'blocProfileViewers', 'blocLive', 'rcmdContacts', 'nbFollowers', 'nbContacts', 'currentUser');
        return $contentArray;
    }

    public function showProfile()
    {
        $pageName =         $this->pageUnderType;
        $currentUser =      $this->myself;
        $userToDisplay =    $this->UserToDisplay;
        $we_langsArray =    $this->we_langsArray;
        $langFile =         $this->langFile;
        $langErrorFiles =   $this->langErrorFiles;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();

        //cover
        $coverPic =         $this->ProfileGamer->getUserCoverPic();

        //profile
        $profilePic =       $this->ProfileGamer->getUserProfilePic();

        //quickinfos
        $quickinfos =       $this->ProfileGamer->getUserQuickInfos();

        //career
        $careerContent =    $this->ProfileGamer->getUserCareer();

        //events
        $eventContent =     $this->ProfileGamer->getUserEvents();

        //Games
        $gameContent =      $this->ProfileGamer->getUserGames();

        //MyTimeline
        $timelineContent =  $this->ProfileGamer->getUserTimeline();

        //Equipments
        $equipmentContent = $this->ProfileGamer->getUserEquipments();

        //ALbum preview
        $albumPreview =     $this->ProfileGamer->getUserGaleryPreview();

        //Summarry
        $summary =          $this->ProfileGamer->getUserSummary();

        //Interests
        $interests =        $this->ProfileGamer->getUserInterests();

        //My Recommended contacts
        $recmdContacts =    $this->ProfileGamer->getMyRecommendedContactsRightBloc($pageName);
        
        //Bloc followers
        $profileBlocFollowers = $this->ProfileGamer->getUserProfileFollowers();

        //Bloc followers
        $profileBlocContacts = $this->ProfileGamer->getUserProfileContacts();

        //bloc live
        $blocLive =         $this->ProfileGamer->getUserBlocLive();
        
        $contentArray = compact('pageName','header','we_langsArray','langFile','langErrorFiles','langFooter','profilePic', 'coverPic', 'quickinfos', 'careerContent', 'gameContent', 'eventContent', 'equipmentContent', 'timelineContent','albumPreview', 'summary', 'interests', 'blocLive', 'recmdContacts', 'userToDisplay', 'nbContacts', 'profileBlocFollowers', 'profileBlocContacts', 'currentUser');
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