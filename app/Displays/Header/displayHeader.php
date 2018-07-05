<?php
namespace app\Displays\Header;

use app\Displays\Header\Notifications\displayUmNotifications;
use app\Displays\Header\User\displayUmUser;
use app\Displays\Header\Viewer\displayUmViewer;
use app\Displays\Header\Messages\displayUmMessages;
use app\Table\AppDisplay;
use app\App;

class displayHeader extends AppDisplay
{
    private $UmUser;
    private $UmViewer;
    private $UmNotifications;
    private $UmMessages;
    private $we_langsArray;
    private $we_langProfile;

    public function __construct($profilesViewers = false, $notifications = false, $conversations = false)
    {
        parent::__construct();
        $this->UmUser =             new displayUmUser($this->currentUser);
        $this->UmViewer =           new displayUmViewer($profilesViewers, $this->currentUser);
        $this->UmNotifications =    new displayUmNotifications($notifications);
        $this->UmMessages =         new displayUmMessages($conversations);
        $this->we_langsArray =      App::getLangModel()->getLangsFromDb();
        $this->we_langProfile =     App::getLangModel()->getNavProfileLangFile();
    }

    public function showMenuTabs()
    {
        $contentLangs = '';
        foreach($this->we_langsArray as $lang)
        {
            $contentLangs .= '<li><a role="menuitem" href="#" class="lang-selector" id="lang-'. $lang->langname .'">'. $lang->langname .'</a></li>';
        }

        return '<div class="nav-menu-items collapse navbar-collapse" id="collapse-hd-menu">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a id="item-home" href="index.php?p=home">'. $this->langFileHeader->title_nav_home .'</a></li>
                        <li class="nav-item"><a id="item-profile" href="index.php?p=profile">'. $this->langFileHeader->title_nav_myprofile .'</a></li>
                        <li class="nav-item"><a id="item-community" href="index.php?p=mycommunity">'. $this->langFileHeader->title_nav_mycommunity .'</a></li>
                        <li class="nav-item"><a id="item-opportunitie" href="index.php?p=coming-soon-opportunities">'. $this->langFileHeader->title_nav_opportunities .'</a></li>
                        <li class="nav-item"><a id="item-loby" href="index.php?p=coming-soon-lobby">'. $this->langFileHeader->title_nav_lobby .'</a></li>                          
                        <li class="nav-item last"><a id="item-blog" href="http://blog.worldesport.com">BLOG</a></li>                          
                        <li class="nav-item mobile dropdown" id="item-langues">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'. $this->langFileHeader->title_nav_lang .'<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                '. $contentLangs .'
                            </ul>
                        </li>                                               
                    </ul>
                </div>';
    }

    public function showUserMenu()
    {
        return '<div class="user-menu-items">
                    <div class="nav-menu-items-container col-md-12">
                        <ul>
                        '. $this->UmUser->show()
                        . $this->UmViewer->show()
                        . $this->UmNotifications->show()
                        . $this->UmMessages->show() .'
                        </ul>
                    </div>
                </div>
                <div class="search-bar-header mobile" data-elem="search-bar-container">                   
                    <form action="index.php?p=search" method="post">
                        <div class="input-container">
                            <input type="text" autocomplete="off" name="keyword_searchbar_header_mobile" placeholder="'. $this->langFileHeader->placeholder_search_bar .'" class="input input-leftpart"/>
                        </div>                                       
                    </form>                                                                 
                    <!-- SEARCH RESULTS EMPLACEMENT -->
                </div>';
    }
    
    public function showMenu()
    {
        return '<div class="nav-container">                    
                    <nav class="menu navbar navbar-inverse navbar-static-top" id="hd-menu">                              
                        <div class="container">
                            <div class="container-navmenu">
                                <div class="navbar-header">
                                    <div class="mobile" id="nav-logo-we">
                                        <a href="index.php">
                                            <img src="public/img/logo/logo.png" alt="WorldEsport logo">                                            
                                             <span class="text-logo-container">
                                                <h1>World eSport</h1>
                                                <span class="text-tmp"><h2>(<span class="orange">Beta</span> version)</h2></span>
                                            </span>                                          
                                        </a>
                                    </div>   
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse-hd-menu" id="bt-menu">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>
                                '. $this->showMenuTabs() .'
                            </div>
                            <div class="user-menu-container">                                
                                '. $this->showUserMenu() .'                               
                            </div>                          
                        </div>
                    </nav>
                </div>
                '. $this->showProfileNavMobile() .'';


    }

    public function showProfileNavMobile(){
        $btCareer = '';
        $btEvent = '';
        $btGames = '';
        if ($_COOKIE['langwe'] == 'fr')
        {
            $btCareer = '<p>'.$this->we_langProfile->bt_nav_gamer_word_career .'</p>
                         <p>'.$this->we_langProfile->bt_nav_gamer_word_esport .'</p>';

            $btEvent =  '<p>'.$this->we_langProfile->bt_nav_gamer_word_event .'</p>
                         <p>'.$this->we_langProfile->bt_nav_gamer_word_esport .'</p>';

            $btGames =  '<p>'.$this->we_langProfile->bt_nav_gamer_word_games .'</p>
                         <p>'.$this->we_langProfile->bt_nav_gamer_word_esport .'</p>';
        }
        else{
            $btCareer = '<p>'.$this->we_langProfile->bt_nav_gamer_word_esport .'</p>
                         <p>'.$this->we_langProfile->bt_nav_gamer_word_career .'</p>';

            $btEvent =  '<p>'.$this->we_langProfile->bt_nav_gamer_word_esport .'</p>
                         <p>'.$this->we_langProfile->bt_nav_gamer_word_event .'</p>';

            $btGames =  '<p>'.$this->we_langProfile->bt_nav_gamer_word_esport .'</p>
                         <p>'.$this->we_langProfile->bt_nav_gamer_word_games .'</p>';
        }

        return '<nav class="nav-profile-container mobile bloc col-md-12" id="nav-profile-mobile">
                    <ul class="list-nav-profile col-md-12">
                        <li class="item-nav-profile active">
                            <a role="button" class="" href="#mycareer">
                                '. $btCareer .'
                            </a>
                        </li>
                        <li class="item-nav-profile">
                            <a role="button" class="" href="#myevent">
                                '. $btEvent .'
                            </a>
                        </li>
                        <li class="item-nav-profile">
                            <a role="button" class="" href="#mygames">
                                '. $btGames .'
                            </a>
                        </li>
                        <li class="item-nav-profile" id="header-nav-equipments">
                            <a role="button" class="" href="#myequipement">
                                <p>'. $this->we_langProfile->bt_nav_gamer_word_equipment .'</p>
                            </a>
                        </li>
                        <li class="item-nav-profile" id="header-nav-timeline">
                            <a role="button" class="" href="#mytimeline">
                                <p>'. $this->we_langProfile->bt_nav_gamer_word_timeline .'</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <nav class="nav-profile-container mobile bloc col-md-12" id="nav-profile-employee-mobile">
                    <ul class="list-nav-profile col-md-12">
                        <li class="item-nav-profile active">
                            <a role="button" class="" href="#mycareer">
                               '. $btCareer .'
                            </a>
                        </li>
                        <li class="item-nav-profile">
                            <a role="button" class="" href="#myevent">
                               '. $btEvent .'
                            </a>
                        </li>
                        <li class="item-nav-profile" id="header-nav-emp-timeline">
                            <a role="button" class="" href="#mytimeline">
                                <p>'. $this->we_langProfile->bt_nav_gamer_word_timeline .'</p>
                            </a>
                        </li>
                    </ul>
                </nav>';
    }

    public function showHeaderTop()
    {
        return '<div class="header-top">
                    <div id="logo-we">
                        <a href="index.php">
                            <img src="public/img/logo/logo.png" alt="WorldEsport logo">
                            <span class="text-logo-container">
                                <h1>World eSport</h1>
                                <span class="text-tmp"><h2>(<span class="orange">Beta</span> version)</h2></span>
                            </span>                           
                        </a>
                    </div>
                    <div class="search-bar-header" data-elem="search-bar-container">
                    
                        <form action="index.php?p=search" method="post">
                            <div class="input-container">
                                <input type="text" autocomplete="off" name="keyword_searchbar_header" placeholder="'. $this->langFileHeader->placeholder_search_bar .'" class="input input-leftpart"/>
                            </div>
                            <div class="submit-container">
                                <input type="submit" name="submit-search" class="input input-rightpart" value="">
                            </div>
                        </form>                                                                 
                        <!-- SEARCH RESULTS EMPLACEMENT -->
                    </div>
                </div>';
    }

    public function show()
    {
        return '<header class="header" id="hd-home">
                    '. $this->showHeaderTop() .'
                    '. $this->showMenu() .'
                </header>';
    }
}