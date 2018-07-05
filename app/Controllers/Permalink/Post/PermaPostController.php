<?php

namespace app\Controllers\Permalink\Post;

use app\Controllers\AppController;
use app\Table\UserModel\displayUser;
use core\Timeline\PostManager;
use core\Timeline\Timeline;
use core\Profile\AppProfile;
use core\Header\Header;

class PermaPostController extends AppController
{
    //ENTER PARAMETERS
    private $postid;

    //VIEW PARAMETERS
    private $pageType;
    private $underType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OBJECTS TO GET VARIABLES
    private $AppProfile;
    private $displayUser;
    private $Header;
    private $PostManager;

    public function __construct($elemid, $authority)
    {
        $this->pageType = 'Permalink';
        parent::__construct($this->pageType);
        $this->pageAuthority = $authority;
        $this->postid = $elemid;

        //SET VIEW PARAMETERS
        $this->underType = 'Post';
        $this->view = $this->pageType . '/' . $this->underType . '/' . $this->pageAuthority . '/';

        //SET TEMPLATES PARAMETERS
        if($this->pageAuthority == 'admin') $this->template = $this->adminTemplate;
        else $this->template = $this->defaultTemplate;


        //SET OBJECT TO GET VARIABLES
        $this->AppProfile =     new AppProfile();
        $this->PostManager =    new PostManager($this->postid);
        $this->displayUser =    new displayUser($this->myself);
        $this->Header =         new Header();
    }

    public function showMyPost()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =           $this->Header->getHeader();

        //get Timeline & users posts and users id in the same order, to make indexs matchs
        $post =             $this->PostManager->getMyPost();

        //Recommended contacts
        $recomdContacts =   $this->AppProfile->getMyRecommendedContactsRightBloc($pageName);

        $contentArray = compact('pageName','header','we_langsArray','langFooter','post', 'recomdContacts', 'currentUser');
        return $contentArray;
    }

    public function showUserPost()
    {
        //to complete
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
            $contentArray = $this->showMyPost();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}