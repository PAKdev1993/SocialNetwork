<?php
namespace app\Controllers\PageNotFound;

use app\Controllers\AppController;
use core\Notifications\NotificationsManager;
use core\PageNotFound\NotFoundManager;
use core\ProfileViews\ProfileViews;
use core\Profile\AppProfile;
use core\Header\Header;

class PageNotFoundController extends AppController
{
    //ENTER PARAMETERS
    private $admin;
    private $codeErr;

    //VIEW PARAMETERS
    private $pageType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OBJECTS TO GET VARIABLES
    private $AppProfile;
    private $Header;
    private $NotFoundManager;

    public function __construct($codeErr, $admin = false, $isLogged = true)
    {
        $this->pageType = 'PageNotFound';
        parent::__construct($this->pageType);
        $this->admin = $admin;
        $this->codeErr = $codeErr;

        //DEFINE PAGE AUTHORITY
        if($admin)
        {
            $this->pageAuthority = 'Admin';
        }
        if($isLogged)
        {
            $this->pageAuthority = 'Viewer';
        }
        if(!$isLogged)
        {
            $this->pageAuthority = 'Public';
        }

        //SET VIEW PARAMETERS
        $this->view = $this->pageType . '/' . $this->pageAuthority . '/';

        //SET TEMPLATES PARAMETERS
        $this->template = $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OBJECT TO GET VARIABLES
        $this->AppProfile =     new AppProfile();
        $this->Header =         new Header();
        $this->NotFoundManager = new NotFoundManager($this->codeErr);
    }

    public function show404()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =     $this->pageType;
        $currentUser =  $this->myself;
        $langFile =     $this->langFile;

        //header bloc
        $header =       $this->Header->getHeader();

        //404 message
        $message =      $this->NotFoundManager->getErrorMessage();

        $contentArray = compact('pageName','langFile','header','message', 'currentUser');
        return $contentArray;
    }

    public function showPublic404()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =     $this->pageType;
        $langFile =     $this->langFile;

        //404 message
        $message =      $this->NotFoundManager->getErrorMessage();

        $contentArray = compact('pageName','langFile','message');
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
        if($this->pageAuthority == 'Viewer')
        {
            $contentArray = $this->show404();
            $this->render($this->view, $contentArray, $this->template);
        }
        if($this->pageAuthority == 'Public')
        {
            $contentArray = $this->showPublic404();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}