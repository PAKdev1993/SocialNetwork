<?php

namespace app\Controllers\Profile;

use app\App;
use app\Controllers\AppController;
use app\Table\UserModel\UserModel;
use core\Profile\ProfileGamer;
use core\Album\Album;
use core\Header\Header;

class AlbumController extends AppController
{
    //ENTER PARAMETERS
    private $slug;
    private $admin;

    //VIEW PARAMETERS
    private $pageType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OBJECTS TO GET VARIABLES
    private $ProfileGamer;
    private $Album;
    private $UserModel;
    private $Header;

    public function __construct($slug = false, $admin = false)
    {
        $this->pageType =  'Album';
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
            
            //SET OBJECT TO GET VARIABLES
            $this->ProfileGamer =   new ProfileGamer($this->UserToDisplay);
        }

        //SET VIEW PARAMETERS
        $this->view = $this->pageType . '/' . $this->pageAuthority . '/';

        //SET TEMPLATES PARAMETERS
        $this->template = $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OBJECT TO GET VARIABLES
        $this->Album =          new Album($this->UserToDisplay);
        $this->Header =         new Header();

    }

    public function showMyAlbum()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =     $this->pageType;
        $currentUser =  $this->myself;
        $langFile =     $this->langFile;
        $langFileProfile = App::getLangModel()->getPageLangFile('profile');
        $langErrorFiles = $this->langErrorFiles;

        //header bloc
        $header =           $this->Header->getHeader();

        //cover
        $coverPic =         $this->ProfileGamer->getMyCoverPic();

        //profile
        $profilePic =       $this->ProfileGamer->getMyProfilePic();

        //album
        $albumContent =     $this->Album->getMyAlbumLastMonth(date('m Y'), 6);

        //Nb contacts
        $nbContacts =       $this->ProfileGamer->getMyNbContacts();

        //Nb followers
        $nbFollowers =      $this->ProfileGamer->getMyNbFolowers();

        $contentArray = compact('pageName','langFile','langFileProfile','langErrorFiles','header','profilePic', 'coverPic', 'albumContent', 'nbContacts', 'nbFollowers', 'currentUser');
        return $contentArray;
    }

    public function showUserAlbum()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =     $this->pageType;
        $currentUser =  $this->myself;
        $userToDisplay = $this->UserToDisplay;
        $langFile =     $this->langFile;

        //header bloc
        $header =           $this->Header->getHeader();
        
        //cover
        $coverPic =         $this->ProfileGamer->getUserCoverPic();

        //profile
        $profilePic =       $this->ProfileGamer->getUserProfilePic();

        //album
        $albumContent =     $this->Album->getUserAlbumLastMonth(date('m Y'), 6);

        //Nb contacts
        $nbContacts =       $this->ProfileGamer->getUserNbContacts();

        //Nb followers
        $nbFollowers =      $this->ProfileGamer->getUserNbFolowers();

        //Bloc followers
        $profileBlocFollowers = $this->ProfileGamer->getUserProfileFollowers();

        $contentArray = compact('pageName','header','langFile','profilePic', 'coverPic', 'albumContent', 'currentUser', 'nbContacts', 'profileBlocFollowers', 'userToDisplay');
        return $contentArray;
    }

    public function showAdminAlbum()
    {

    }

    public function index()
    {
        if($this->pageAuthority == 'Viewer')
        {
            $contentArray = $this->showUserAlbum();
            $this->render($this->view, $contentArray, $this->template);
        }
        if($this->pageAuthority == 'Owner')
        {
            $contentArray = $this->showMyAlbum();
            $this->render($this->view, $contentArray, $this->template);
        }
        if($this->pageAuthority == 'Admin')
        {
            //$this->render($pageName, $variables, $temp);
        }
    }
}