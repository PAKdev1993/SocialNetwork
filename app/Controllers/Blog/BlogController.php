<?php

namespace app\Controllers\Blog;

use app\Controllers\AppController;
use core\ManageAccount\ManageAccount;
use core\ProfileViews\ProfileViews;
use core\Profile\AppProfile;
use core\Header\Header;

class BlogController extends AppController
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
    private $Header;

    public function __construct($admin = false)
    {
        $this->pageType = 'Blog';
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
        $this->Header = new Header();
    }

    public function showBlog()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();
        
        $contentArray = compact('pageName','header','we_langsArray','langFooter','currentUser');
        return $contentArray;
    }

    public function showPubicBlog()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();

        $contentArray = compact('pageName','header','we_langsArray','langFooter','currentUser');
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
            $contentArray = $this->showBlog();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}