<?php

namespace app\Controllers\MessageCenter;

use app\Controllers\AppController;
use core\ManageAccount\ManageAccount;
use core\MessageCenter\MessageCenterManager;
use core\ProfileViews\ProfileViews;
use core\Profile\AppProfile;
use core\Header\Header;

class MessageCenterController extends AppController
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
    private $MessageCenter;

    public function __construct($admin = false)
    {
        $this->pageType = 'MessageCenter';
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
        $this->MessageCenter =  new MessageCenterManager();
    }

    public function showMessageCenter()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();

       //Apercu Conversations
        $convsApercus = $this->MessageCenter->getApercuConversations();
        
        //Last conversation
        $lastConv = //$this->MessageCenter->getLastConversation();

        $contentArray = compact('pageName','header','we_langsArray','langFooter','convsApercus','lastConv','currentUser');
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
            $contentArray = $this->showMessageCenter();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}