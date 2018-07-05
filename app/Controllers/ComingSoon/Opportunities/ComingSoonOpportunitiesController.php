<?php
namespace app\Controllers\ComingSoon\Opportunities;

use app\Controllers\AppController;
use core\ComingSoon\ComingSoonManager;
use core\Header\Header;
use core\Profile\AppProfile;

class ComingSoonOpportunitiesController extends AppController
{
    //ENTER PARAMETERS
    private $admin;
    private $codeErr;

    //VIEW PARAMETERS
    private $pageType;
    private $pageUnderType;
    private $pageAuthority;
    private $view;

    //TEMPLATE PARAMETERS
    private $template;

    //OBJECTS TO GET VARIABLES
    private $Header;
    private $AppProfile;

    public function __construct($admin = false)
    {
        $this->pageUnderType = 'Opportunities';
        parent::__construct($this->pageUnderType);
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
        $this->pageType = 'ComingSoon';
        $this->view = $this->pageType . '/' . $this->pageUnderType . '/' . $this->pageAuthority . '/';

        //SET TEMPLATES PARAMETERS
        $this->template = $admin ? $this->adminTemplate : $this->defaultTemplate;

        //SET OBJECT TO GET VARIABLES
        $this->Header =         new Header();
        $this->AppProfile =     new AppProfile();
    }

    public function showComingSoonOpportunities()
    {
        //$langFile = App::getLangModel()->getPageLangFile($this->pageName);
        $pageName =         $this->pageType;
        $currentUser =      $this->myself;
        $we_langsArray =    $this->we_langsArray;
        $langFile =         $this->langFile;
        $langFooter =       $this->langFooter;

        //header bloc
        $header =       $this->Header->getHeader();

        //My Recommended contacts aside right
        $recomdContactsRight = $this->AppProfile->getMyRecommendedContactsRightBloc($pageName);

        $contentArray = compact('pageName','header','we_langsArray','langFile','langFooter', 'recomdContactsRight', 'currentUser');
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
            $contentArray = $this->showComingSoonOpportunities();
            $this->render($this->view, $contentArray, $this->template);
        }
    }
}