<?php

namespace app\Table;

use app\App;
use core\Session\Session;

class AppDisplay
{
    protected $db;

    //FORM ELEMS
    protected $formElems;

    //LANG
    //ces langfile sont set a la demande par les classes qui en ont besoin
    protected $langModel;
    protected $montharray  = ['empty','January','February','March','April','May','June','July','August','September','October','November','December'];
    protected $monthTraduce;
    protected $langFile;
    protected $langFileHeader;
    protected $gearTraduce;
    protected $langFileContactUs;
    protected $langInfosBulles;
    protected $langGenerals;
    protected $langErrorFiles;
    protected $langFileAsk;

    //CURRENT PAGE
    protected $pageName;

    //USERS TO DISPLAY
    //private $state = 1; //1 (default): l'user a deja rempli des données et on doit afficher le display normal
                        //0: First time, l'utilisateur n'a encore jamais rentré d'infos a display donc on affiche l'edit form ft
    protected $currentUser;
    protected $userToDisplay;

    //OTHER
    protected $unauthorizedChar = [' ', '\'','\\','/','#','-',','];
    
    //#todo rendre l'utilisation de cette classe plus claire
    public function __construct($user = false, $pageName = false)
    {
        $this->currentUser =    Session::getInstance()->read('auth');
        $this->userToDisplay =  $user;

        //FORM ELEMS
        $this->formElems = new AppForms();

        //LANG FILES
        $this->langModel =          App::getLangModel();
        $this->monthTraduce =       $this->langModel->getLangMonths(); //#todo trouver un moyen de ne pas avoir a refaire ces requettes
        $this->gearTraduce =        $this->langModel->getLangGear();
        $this->session =            Session::getInstance();

        //FILL SESSION LANGFILE ARRAY
        if($pageName)
        {
            $this->session->writeLangFileArray($pageName, $this->langModel->getPageLangFile($pageName));
        }
        $this->langFile =           $this->session->readLangFile();
        $this->langFileHeader =     $this->langFileHeader ?     $this->langFileHeader       : $this->langModel->getHeaderLangFile();
        $this->langFileContactUs =  $this->langFileContactUs ?  $this->langFileContactUs    : $this->langModel->getLangContactForms();
        $this->langInfosBulles =    $this->langInfosBulles ?    $this->langInfosBulles      : $this->langModel->getLangInfosBulles();
        $this->langGenerals =       $this->langGenerals ?       $this->langGenerals         : $this->langModel->getLangGenerals();
        $this->langErrorFiles =     $this->langErrorFiles ?     $this->langErrorFiles       : $this->langModel->getLangFilesError();
        $this->langFileAsk =        $this->langFileAsk ?        $this->langFileAsk          : $this->langModel->getAskLangFile();

        //PAGES
        $this->homePage =           'home';
        $this->profilePage =        'profile';
        $this->communityPage =      'community';
        $this->profileViewsPage =   'profileViews';
        $this->manageAccountPage =  'manageaccount';
        $this->notificationsPage =  'notifications';
        $this->messageCenterPage =  'messagecenter';

        $this->db =                 App::getDatabase(); // utilisé ds l'affichage des contact car on utiliser le user model
    }    

    /****************************************************************************\
     *                          MY PROFILE PART                                 *
    \****************************************************************************/
    //--NOTES
    //L'album lui s'affiche directement s la classe display

    //afficher le formulaire d'edition de my quick infos si l'user ne l'a pas rempli
    public function showMyQuickInfosft($content)
    {
        return '<div class="aside-left-bloc bloc general-bloc edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-quickinfos">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_quickinformation .'</h1>                               
                    </div>
                    '. $content .'                   
                </div>';
    }

    //afficher myquickinfos lorsqu'elle ont été remplis au moions une fois
    public function showMyQuickInfos($content)
    {
        return '<div class="aside-left-bloc bloc general-bloc">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_quickinformation .'</h1>  
                        <div class="edit-ico-container" id="edit-quickinfos">
                            <div class="edit-gear edit-bloc ico-gear"></div>
                            <div class="close-edit close-edit-bloc"></div>
                        </div>
                    </div>
                    '. $content .'                  
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showMyCareerft($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-teams">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showMyCareer($content)
    {
        //important de mettre le loader après le $content pour les problèmes doverflow
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>
                    <div class="edit-ico-container" id="edit-mycareer">
                        <div class="edit-gear edit-bloc ico-gear"></div>
                        <div class="close-edit close-edit-bloc"></div>
                    </div>
                    <div class="profile-elems-container" id="profile-career">                        
                        '. $content .'                       
                    </div>
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-teams">
                        <span class="loader loader-double">
                        </span>
                    </div>
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showMyEmployeeCareer($content)
    {
        //important de mettre le loader après le $content pour les problèmes doverflow
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>
                    <div class="edit-ico-container" id="edit-mycompanies">
                        <div class="edit-gear edit-bloc ico-gear"></div>
                        <div class="close-edit close-edit-bloc"></div>
                    </div>
                    <div class="profile-elems-container" id="profile-career">                        
                        '. $content .'                       
                        <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-company-body">
                            <span class="loader loader-double">
                            </span>
                        </div>
                    </div>
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showMyEmployeeCareerft($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc  edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-company">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher myevents lorsqu'il n'a jmais été remplie avec au moins un event
    public function showMyEventsft($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-events">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showMyEvents($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">
                    <div class="loader-container loader-profile-elem" id="loader-events">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>
                    <div class="edit-ico-container" id="edit-myevents">
                        <div class="edit-gear edit-bloc ico-gear"></div>
                        <div class="close-edit close-edit-bloc"></div>
                    </div>
                    <div class="profile-elems-container" id="profile-events">
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher myeveent employee lorsqu'elle n'a jmais été remplie avec au moins un employee event
    public function showMyEmployeeEventsft($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-employee-events">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher myevents employee lorsqu'elle a été remplie avec au moins un event
    public function showMyEmployeeEvents($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>
                    <div class="edit-ico-container" id="edit-myemployeeevents">
                        <div class="edit-gear edit-bloc ico-gear"></div>
                        <div class="close-edit close-edit-bloc"></div>
                    </div>
                    <div class="profile-elems-container" id="profile-employee-events">
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-employee-events-body">
                        <span class="loader loader-double">
                        </span>
                    </div>
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher mygames lorsqu'elle n'a jamais été remplie avec au moins un jeu
    public function showMyGamesft($content)
    {
    return '<div class="aside-left-bloc bloc profile-bloc edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-games">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_games .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showMyGames($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">
                    <div class="loader-container loader-profile-elem" id="loader-games">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_games .'</h1>                      
                    </div>
                    <div class="edit-ico-container" id="edit-mygames">
                        <div class="edit-gear edit-bloc ico-gear"></div>
                        <div class="close-edit close-edit-bloc"></div>
                    </div>
                    <div class="profile-elems-container" id="profile-games">
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher myequipments lorsqu'il n'a jamais été remplie avec au moins un equipment
    public function showMyEquipmentsft($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-equipments">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_myequipment .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showMyEquipments($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">
                    <div class="loader-container loader-profile-elem" id="loader-equipments">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_myequipment .'</h1>                      
                    </div>
                    <div class="edit-ico-container" id="edit-myequipments">
                        <div class="edit-gear edit-bloc ico-gear"></div>
                        <div class="close-edit close-edit-bloc"></div>
                    </div>
                    <div class="profile-elems-container" id="profile-equipements">
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher my summary lorsqu'il n'a encore jamais été rempli
    public function showMySummaryft($content)
    {
        return '<div class="aside-right-bloc bloc edit-mode-permanent"> 
                    <div class="loader-container loader-profile-elem" id="loader-summary" data-elem="loader-summary">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_gamersummary .'</h1>                               
                    </div>                                      
                    '. $content .'
                </div>';
    }

    //afficher min summary rempli
    public function showMySummary($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_gamersummary .'</h1>
                        <div id="edit-summary" data-action="edit-summary" class="edit-ico-container">
                            <div class="edit-gear edit-bloc ico-gear"></div>
                            <div class="close-edit close-edit-bloc"></div>
                        </div>                       
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mon summary employee lorsqu'il n'a encore jamais été rempli
    public function showMyEmployeeSummaryft($content)
    {
        return '<div class="aside-right-bloc bloc edit-mode-permanent"> 
                    <div class="loader-container loader-profile-elem" id="loader-summary">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_employeesummary .'</h1>                               
                    </div>                                      
                    '. $content .'
                </div>';
    }

    //afficher mon summary rempli
    public function showMyEmployeeSummary($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_employeesummary .'</h1>
                        <div id="edit-employee-summary" data-action="edit-summary" class="edit-ico-container">
                            <div class="edit-gear edit-bloc ico-gear"></div>
                            <div class="close-edit close-edit-bloc"></div>
                        </div>                       
                    </div>
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-summary" data-elem="loader-summary">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher my intesrest lorsqu'ils n'ont jamais été remplis par au moins un interest
    public function showMyInterestsft($content)
    {
        return '<div class="aside-right-bloc bloc edit-mode-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-interest">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_interests .'</h1>                      
                    </div>                    
                    '. $content .'
                </div>';
    }

    //afficher my interests lorsqu'ils on été remplis
    public function showMyInterests($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_interests .'</h1>
                        <div class="edit-ico-container" data-action="edit-interests">
                            <div class="edit-gear edit-bloc ico-gear"></div>
                            <div class="close-edit close-edit-bloc"></div>
                        </div>
                        <div class="loader-container loader-profile-elem" id="loader-interest" data-elem="loader-interests">
                            <span class="loader loader-double">
                            </span>
                        </div>
                    </div>
                   '. $content .'
                </div>';
    }

    //afficher le bloc notifyMynetwork
    public function showNotifyMyNetwork($content)
    {
        return '<div class="aside-left-bloc bloc">
                    <div class="bloc-container" id="notify-container">
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher la liste de contact vide
    public function showMyContactsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_nav_contact .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12 empty-container" id="my-contacts-container">
                    '. $content .'
                    </div>
               </div>';
    }

    //afficher la liste de contact
    public function showMyContacts($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_nav_contact .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-contacts-container">
                        '. $content .'
                    </div>
                    <div class="alphabetic-bar">
                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="A">A</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="B">B</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="C">C</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="D">D</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="E">E</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="F">F</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="G">G</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="H">H</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="I">I</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="J">J</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="K">K</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="L">L</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="M">M</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="N">N</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="O">O</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="P">P</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="Q">Q</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="R">R</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="S">S</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="T">T</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="U">U</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="V">V</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="W">W</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="X">X</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="Y">Y</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="Z">Z</p>
                            </div>
                        </div>
                        
                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="#">#</p>
                            </div>
                        </div>
                    </div>
               </div>';
    }

    //afficher la liste d'invitation vide
    public function showMyPendingContactsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_block_pending_contact .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12 empty-container" id="my-pending-contacts-container">                       
                        '. $content .'
                    </div>                  
               </div>';
    }

    //afficher la liste d'invitations
    public function showMyPendingContacts($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_block_pending_contact .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-pending-contacts-container">
                        '. $content .'
                    </div>                   
               </div>';
    }

    //afficher la liste des followers empty
    public function showMyFolowersEmpty($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_nav_follower .'</h1>
                    </div>
                    <div class="community-elems-container empty-container col-md-12" id="my-followers-container">
                        '. $content .'
                    </div>                   
               </div>';
    }

    //afficher la liste des followers
    public function showMyFolowers($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_nav_follower .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-followers-container">
                        '. $content .'
                    </div>
                    <div class="alphabetic-bar">
                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="A">A</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="B">B</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="C">C</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="D">D</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="E">E</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="F">F</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="G">G</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="H">H</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="I">I</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="J">J</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="K">K</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="L">L</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="M">M</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="N">N</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="O">O</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="P">P</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="Q">Q</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="R">R</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="S">S</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="T">T</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="U">U</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="V">V</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="W">W</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="X">X</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="Y">Y</p>
                            </div>
                        </div>

                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="Z">Z</p>
                            </div>
                        </div>
                        
                        <div class="letter-container">
                            <div class="letter-content">
                                <p class="letter" data-goto="#">#</p>
                            </div>
                        </div>
                    </div>
               </div>';
    }

    //afficher la liste des recommended conatcts vide
    public function showMyRecommendedContactsRightBloc($content)
    {
        return '<div class="aside-right-bloc bloc recommended-contact-container">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_nav_recommended_contact .'</h1>
                    </div>
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-recommended-contacts">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    '. $content .'
               </div>';
    }

    //afficher la liste des recommened contacts
    public function showMyRecommendedContacts($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc recommended-contact-container">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_nav_recommended_contact .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-recommended-contacts-container">
                        '. $content .'
                    </div>
               </div>';
    }

    public function showMyRecommendedContactsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc recommended-contact-container empty">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->communityPage]->title_nav_recommended_contact .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-recommended-contacts-container">
                        '. $content .'
                    </div>
               </div>';
    }

    public function showMyProfileViewersEmpty($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc recommended-contact-container empty">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profileViewsPage]->title_profileviews .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-profile-viewers-container">
                        '. $content .'
                    </div>
               </div>';
    }

    public function showMyProfileViewers($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc recommended-contact-container">
                    <div class="title-aside-bloc col-md-12">                   
                        <h1>'. $this->langFile[$this->profileViewsPage]->title_profileviews .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-profile-viewers-container">
                        '. $content .'
                    </div>
               </div>';
    }
    
    //NOTES LA PREVIEW ALBUM EST DIRECTEMENT DISPLAY VIA AlBumPreviewDisplay
    public function showMyProfileViewersUmEmpty($content)
    {
        return '<li class="user-menu-item" id="user-item-viewers" tabindex="2">
                    <a role="button">
                        <div class="pseudo"></div>
                    </a>
                    <div class="under-menu uder-menu-user">
                        <div class="under-menu-item-container empty col-md-12">
                            <div class="descript col-md-12">
                                <p>'. $this->langFileHeader->title_um_viewer .'</p>
                            </div>
                            '. $content .'
                            <div class="um-show-more col-md-12">
                                <a role="button" href="index.php?p=profileviews">
                                    <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>';
    }

    public function showMyProfileViewerUm($content, $notifBloc)
    {
        return '<li class="user-menu-item" id="user-item-viewers" tabindex="2">
                    '. $notifBloc .'
                    <a role="button">
                        <div class="pseudo"></div>
                    </a>
                    <div class="under-menu uder-menu-user">
                        <div class="under-menu-item-container col-md-12">
                            <div class="descript col-md-12">
                                <p>'. $this->langFileHeader->title_um_viewer .'</p>
                            </div>
                            '. $content .'
                            <div class="um-show-more col-md-12">
                                <a role="button" href="index.php?p=profileviews">
                                    <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>';
    }

    public function showMyProfileViewerRightBloc($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_profileview .'</h1>
                    </div>
                    <div class="bloc-container" id="whoviewed-container">
                        '. $content .'
                    </div>
                </div>';
    }

    public function showMyProfileViewerRightBlocEmpty($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_profileview .'</h1>
                    </div>
                    <div class="bloc-container" id="whoviewed-container">
                        '. $content .'
                    </div>
                </div>';
    }
    
    public function showSearchPageResults($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc search-results-container">
                    <div id="loader-searchPage-results" class="loader-container loader-elem-bloc loader-profile-elem">
                        <div class="loader-double-container">
                            <span class="loader loader-double">
                            </span>
                        </div>
                    </div>
                    <div class="community-elems-container col-md-12" id="search-results-container">
                        '. $content .'
                    </div>
               </div>';
    }

    public function showSearchPageResultsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc search-results-container empty">
                    <div id="loader-searchPage-results" class="loader-container loader-elem-bloc loader-profile-elem">
                        <div class="loader-double-container">
                            <span class="loader loader-double">
                            </span>
                        </div>
                    </div>
                    <div class="community-elems-container col-md-12" id="search-results-container">
                        '. $content .'
                    </div>
                   
               </div>';
    }

    public function showSearchBarResults($content)
    {
        return '<div class="search-bar-header-results">
                    <div id="loader-search-bar-results" class="loader-container loader-elem-bloc loader-profile-elem">
                        <div class="loader-double-container">
                            <span class="loader loader-double"></span>
                        </div>
                    </div>
                    <div class="searchbar-results-container col-md-12">
                        '. $content .'
                    </div>
                    <div class="um-show-more col-md-12">
                        <a role="button" data-action="show-more-results" href="index.php?p=search">
                            <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                        </a>
                    </div>
                </div>';
    }

    public function showSearchBarResultsEmpty($content)
    {
        return '<div class="search-bar-header-results">
                    <div id="loader-search-bar-results" class="loader-container loader-elem-bloc loader-profile-elem">
                        <div class="loader-double-container">
                            <span class="loader loader-double"></span>
                        </div>
                    </div>
                    <div class="searchbar-results-container col-md-12">
                       '. $content .'
                    </div>
                    <div class="um-show-more col-md-12">
                        <a role="button" data-action="show-more-results" href="index.php?p=search">
                            <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                        </a>
                    </div>
                </div>';
    }

    public function showMyAccountManager($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->manageAccountPage]->title_manage_your_account .'</h1>
                    </div>
                    <div class="body-aside-bloc col-md-12">
                        <div class="manage-elems-container col-md-12">
                            '. $content .'
                        </div>
                        <div class="update-button-container col-md-12">
                            <button class="share-button bt share-button-big" data-action="save-account-parameters" id="bt-save-account">'. $this->langFile[$this->manageAccountPage]->bt_update .'</button>
                        </div>
                    </div>
                    <div class="loader-container loader-profile-elem loader-profile-elem-body" id="loader-manage-account">
                        <span class="loader loader-double">
                        </span>
                    </div>
                </div>';
    }

    public function showMyNotifications($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->notificationsPage]->title_field_mynotifications .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-notifications-container">
                        '. $content .'
                    </div>
               </div>';
    }

    public function showMyNotificationsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc community-bloc recommended-contact-container empty">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->notificationsPage]->title_field_mynotifications .'</h1>
                    </div>
                    <div class="community-elems-container col-md-12" id="my-notifications-container">
                        '. $content .'
                    </div>
               </div>';
    }

    public function showMyNotificationsUmEmpty($content)
    {
        return '<li class="user-menu-item" id="user-item-notifications" tabindex="3">
                    <a role="button">
                        <div class="pseudo"></div>
                    </a>                       
                    <div class="under-menu uder-menu-user">
                        <div class="under-menu-item-container empty col-md-12">
                            '. $content .'
                            <div class="um-show-more col-md-12">
                                <a role="button" href="index.php?p=notifications">
                                    <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>';
    }

    public function showMyNotificationsUm($content, $notifBloc)
    {
        return '<li class="user-menu-item" id="user-item-notifications" tabindex="3">
                    '. $notifBloc .'
                    <a role="button">
                        <div class="pseudo"></div>
                    </a>                       
                    <div class="under-menu uder-menu-user">
                        <div class="under-menu-item-container col-md-12">
                            <div class="item-container">
                                '. $content .'
                            </div>                           
                            <div class="um-show-more col-md-12">
                                <a role="button" href="index.php?p=notifications">
                                    <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>';
    }

    public function showMyMessagesUmEmpty($content)
    {
        return '<li class="user-menu-item" id="user-item-message" tabindex="2">
                    <div class="nbnotif-container">
                        <div class="nb-notifs">
                           <p data-elem="unreadedNotifs"></p>
                        </div>
                    </div>
                    <a role="button" href="http://localhost/WEindev/index.php?p=messages">
                        <div class="pseudo"></div>
                    </a>
                    <div class="under-menu uder-menu-user">
                        <div class="under-menu-item-container empty col-md-12">
                            <div class="bt-new-message-container">
                                <a role="button" data-action="new-msg">'. $this->langFileHeader->bt_new_tchat .'</a>
                            </div>
                            <div class="item-container">
                                '. $content .'
                            </div>
                            <div class="um-show-more col-md-12">
                                <a role="button" href="index.php?p=messages">
                                    <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>';
    }

    public function showMyMessagesUm($content, $notifBloc)
    {
        return '<li class="user-menu-item" id="user-item-message" tabindex="3">
                    '. $notifBloc .'
                    <a role="button" href="http://localhost/WEindev/index.php?p=messages">
                        <div class="pseudo"></div>
                    </a>                       
                    <div class="under-menu uder-menu-user">
                        <div class="under-menu-item-container col-md-12">
                            <div class="bt-new-message-container">
                                <a role="button" data-action="new-msg">'. $this->langFileHeader->bt_new_tchat .'</a>
                            </div>
                            <div class="item-container">
                                '. $content .'
                            </div>                           
                            <div class="um-show-more col-md-12">
                                <a role="button" href="index.php?p=messages">
                                    <p>'. $this->langFileHeader->bt_showMore_dotted .'</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>';
    }
    
    //afficher mon bloc live jamais rempli sur mon profil
    public function showMyLiveEmpty($content)
    {
        return '<div class="aside-right-bloc bloc bloc-edit-container-permanent">                               
                    <div class="loader-container loader-profile-elem" id="loader-live" data-elem="loader-live">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_bloc_live .'</h1>
                    </div>
                    '. $content .'
                </div>';    
    }

    //afficher le bloc live jamais rempli sur le profil d'un user
    public function showUserLiveEmpty($content)
    {
        return '<div class="aside-right-bloc bloc bloc-edit-container-permanent">          
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_bloc_live .'</h1>
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mon bloc live rempli
    public function showMyLive($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_bloc_live .'</h1>
                        <div class="edit-ico-container" data-action="edit-live">
                            <div class="edit-gear edit-bloc ico-gear"></div>
                            <div class="close-edit close-edit-bloc"></div>
                        </div>
                        <div class="loader-container loader-profile-elem" id="loader-live" data-elem="loader-live">
                            <span class="loader loader-double">
                            </span>
                        </div>
                    </div>
                   '. $content .'
                </div>';
    }

    //afficher le bloc live rempli d'un user
    public function showUserLive($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_bloc_live .'</h1>              
                    </div>
                   '. $content .'
                </div>';
    }

    //afficher le bloc d'apercu des convs ds le message center
    public function showApercuDiscutions($content)
    {
        return '<div class="discussions-container col-md-12">
                    '. $content .'
                </div>';
    }

    /****************************************************************************\
     *                          SOMEONE ELSE PROFILE PART                       *
    \****************************************************************************/
    public function showUserQuickInfosEmpty($content)
    {
        return '<div class="aside-left-bloc bloc general-bloc">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_quickinformation .'</h1>                               
                    </div>
                    '. $content .'                   
                </div>';
    }

    public function showUserQuickInfos($content)
    {
        return '<div class="aside-left-bloc bloc general-bloc">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_quickinformation .'</h1>                       
                    </div>
                    '. $content .'                  
                </div>';
    }

    public function showUserCareerEmpty($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showUserCareer($content)
    {
        //important de mettre le loader après le $content pour les problèmes doverflow
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>                    
                    <div class="profile-elems-container" id="profile-career">                        
                        '. $content .'                       
                    </div>
                </div>';
    }

    //afficher user events lorsqu'il est vide
    public function showUserEventsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher user career lorsqu'elle a été remplie avec au moins une team
    public function showUserEvents($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>                    
                    <div class="profile-elems-container" id="profile-events">
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher user games lorsqu'il n'y a aucun jeux d'entré
    public function showUserGamesEmpty($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_games .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher les jeu de l'user lorsqu'il y en a au moins un
    public function showUserGames($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_games .'</h1>                      
                    </div>                    
                    <div class="profile-elems-container" id="profile-games">
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher user equipments lorsqu'il n'a jamais été remplie avec au moins un equipment
    public function showUserEquipmentsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_myequipment .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher user equipments lorsqu'elle a été remplie avec au moins une team
    public function showUserEquipments($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_myequipment .'</h1>                      
                    </div>                    
                    <div class="profile-elems-container" id="profile-equipements">
                        '. $content .'
                    </div>
                </div>';
    }

    //afficher user summary lorsqu'il n'a encore jamais été rempli
    public function showUserSummaryEmpty($content)
    {
        return '<div class="aside-right-bloc bloc edit-mode-permanent">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_gamersummary .'</h1>                               
                    </div>                                      
                    '. $content .'
                </div>';
    }

    //afficher user summary rempli
    public function showUserSummary($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_gamersummary .'</h1>                                              
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher mon summary employee lorsqu'il n'a encore jamais été rempli
    public function showUserEmployeeSummaryEmpty($content)
    {
        return '<div class="aside-right-bloc bloc">                     
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_employeesummary .'</h1>                               
                    </div>                                      
                    '. $content .'
                </div>';
    }

    //afficher mon summary rempli
    public function showUserEmployeeSummary($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_employeesummary .'</h1>                                           
                    </div>                    
                    '. $content .'
                </div>';
    }

    //afficher user intesrest lorsqu'ils n'ont jamais été remplis par au moins un interest
    public function showUserInterestsEmpty($content)
    {
        return '<div class="aside-right-bloc bloc edit-mode-permanent">                  
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_interests .'</h1>                      
                    </div>                    
                    '. $content .'
                </div>';
    }

    //afficher user interests lorsqu'ils on été remplis
    public function showUserInterests($content)
    {
        return '<div class="aside-right-bloc bloc">
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->title_myprofile_interests .'</h1>                        
                    </div>
                   '. $content .'
                </div>';
    }

    //afficher user career lorsqu'elle a été remplie avec au moins une team
    public function showUserEmployeeCareer($content)
    {
        //important de mettre le loader après le $content pour les problèmes doverflow
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>                 
                    <div class="profile-elems-container" id="profile-career">                        
                        '. $content .'                
                    </div>
                </div>';
    }

    //afficher mycareer lorsqu'elle a été remplie avec au moins une team
    public function showUserEmployeeCareerEmpty($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                   
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_career .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher myeveent employee lorsqu'elle n'a jmais été remplie avec au moins un employee event
    public function showUserEmployeeEventsEmpty($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc edit-mode-permanent">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>
                    '. $content .'
                </div>';
    }

    //afficher myevents employee lorsqu'elle a été remplie avec au moins un event
    public function showUserEmployeeEvents($content)
    {
        return '<div class="aside-left-bloc bloc profile-bloc">                    
                    <div class="title-aside-bloc col-md-12">
                        <h1>'. $this->langFile[$this->profilePage]->nav_gamer_events .'</h1>                      
                    </div>                    
                    <div class="profile-elems-container" id="profile-employee-events">                   
                        '. $content .'
                    </div>
                </div>';
    }
    /****************************************************************************\
     *                             EMAILS PART                                  *
    \****************************************************************************/
    public function showEmailHtmlContent($content)
    {
        return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3c.org/TR/xhtml1/DTD/xhtml-strict.dtd">
                <html xmlns:v="urn:schemas-microsoft-com:vml">
                    <head>
                        <meta http-equiv="content-type" content="text/html; charset=utf-8">
                        <meta name="viewport" content="width=device-width; initial-scale=1.0, maximum-scale=1.0;">
                        <link rel="stylesheet" type="text/css" href="public/styles/mails/style.css">
                    </head>
                    ' . $content . '
                </html>';
    }

    public function showEmailHtmlContentUpdated($content)
    {
        return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitionnal //EN" "http://www.w3c.org/TR/xhtml1/DTD/xhtml-transitionnal.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/plain; charset=UTF-8" />
                        <meta name="viewport" content="width=device-width; initial-scale=1.0, maximum-scale=1.0;">
                        <link rel="stylesheet" type="text/css" href="public/styles/mails/style.css">
                    </head>
                    ' . $content . '
                </html>';
    }
}