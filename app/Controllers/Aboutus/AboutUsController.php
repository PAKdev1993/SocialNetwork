<?php
namespace app\Controllers\Aboutus;

use app\Controllers\AppController;
use core\Header\Header;

class AboutUsController extends AppController
{
    //ENTER PARAMETERS
    private $admin;

    //VIEW PARAMETERS
    private $pageType;
    private $pageUnderType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OBJECTS TO GET VARIABLES
    private $Header;

    public function __construct($admin = false)
    {
        $this->pageType = 'Aboutus';
        parent::__construct($this->pageType);
        $this->admin = $admin;

        //DEFINE PAGE AUTHORITY
        if($admin)
        {
            $this->pageAuthority = 'Admin';
        }
        else{
            $this->pageAuthority = 'Viewer';
        }

        //SET VIEW PARAMETERS
        $this->view = $this->pageType . '/' . $this->pageAuthority . '/';

        //SET TEMPLATES PARAMETERS
        $this->template = $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OBJECT TO GET VARIABLES
        $this->Header =         new Header();
    }

    public function showAboutUs()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFile =         $this->langFile;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =       $this->Header->getHeader();

        $contentArray = compact('pageName','header','we_langsArray','langFile','langFooter','currentUser');
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
            $contentArray = $this->showAboutUs();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}