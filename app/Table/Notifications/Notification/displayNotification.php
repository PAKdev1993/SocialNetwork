<?php
namespace app\Table\Notifications\Notification;

use app\Table\appDates;
use app\Table\Notifications\displayNotifications;
use app\Table\Posts\PostModel;
use core\Notifications\NotificationsManager;
use core\Session\Session;
use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use app\Table\Profile\Quickinfos\QuickInfosModel;
use app\Table\UserModel\UserModel;

class displayNotification extends displayNotifications
{
    //NOTIFICATION ATTRIBUTES
    private $id;
    private $id_userfrom;
    private $id_userto;
    private $codeNotif; /*  01 - un user devient une e-influence
                            02 - ne devient pas une e-influence
                            03 - a demandé a devenir une e-influence
                            04 - demande de e-influence rejetée
                            05 - est sanctionné profil suspendu
                            06 - est sanctionné a subis une supression de contenu
                            07 - recois un message
                            08 - someone like your post
                            09 - someone comment your post
                            10 - someone folowing you
                            11 - someone is added to your contacts
                            12 - someone want to add you
                            13 - nouvel event vous concernant vient de se creer
                            14 - nouvel event vous concernant viens d’etre anulé
                            15 - un utilisateur a visité votre page
                            16 - nouveau message
                            17 - a post was reported
                            18 – report abuse */
    private $elemid;
    private $consulted;
    private $notifLink;
    private $textNotif = '';
    private $picNotif = '';

    //LANG VARIABLES
    protected $langFile;

    //DATES
    private $date;
    private $dateTransformed;
    private $time;

    //USER
    private $userFrom;
    private $qiUserFrom;

    //USEFULL OBJECTS
    private $UserImages;

    //OTHER VARIABLES
    private $todisplay; // = NotificationsPage pour display un element de la page de notification || UmNotification pou display un element de menu


    public function __construct($notification = false, $todisplay = false)
    {
        parent::__construct();
        $this->id =             (empty($notification->pk_notifEntry))       ? '' : $notification->pk_notifEntry;
        $this->date =           (empty($notification->date))                ? '' : $notification->date;
        $this->id_userfrom =    (empty($notification->fk_iduserfrom))       ? '' : $notification->fk_iduserfrom;
        $this->id_userto =      (empty($notification->fk_iduserto))         ? '' : $notification->fk_iduserto;
        $this->codeNotif =      (empty($notification->codeNotif))           ? '' : (int)$notification->codeNotif;
        $this->elemid =         (empty($notification->elemid))              ? '' : $notification->elemid;
        $this->consulted =      (empty($notification->consulted))           ? '' : $notification->consulted;

        //format date:
        $appDates = new appDates($this->date);
        $this->dateTransformed = $appDates->getDate();

        //notif link
        $notifManager = new NotificationsManager();
        $this->notifLink = $notifManager->generateLinkNotif($this->codeNotif, $this->elemid, $this->id_userfrom);

        //todisplay
        $this->todisplay = $todisplay;

        //user
        $model =    new UserModel();
        $qimodel =  new QuickInfosModel();
        $this->userFrom =   $model->getUserFromId($this->id_userfrom);
        $this->qiUserFrom = $qimodel->getQuickInfosFromIdUser($this->id_userfrom);

        //images
        $this->UserImages = new displayUsersImages($this->userFrom);
        
        //case where the notif is about a post
        if($this->codeNotif == 8 || $this->codeNotif == 9)
        {
            $model = new PostModel();
            $post = $model->getPostFromid($this->elemid);
            //define text notif
            if($post){
                if($post->texte != '' && $post->texte != NULL)
                {
                    $this->textNotif = "« ".  strip_tags(substr($post->texte,0,25)) ." ... »";
                }
                //define pic notif if nescessary
                if($post->action == 'action_published_picture')
                {
                    if($post->images)
                    {
                        $this->picNotif = '<img src="inc/img/img.php?imgname='. $post->images .'&p='. $this->elemid .'&u='. $this->id_userto .'" alt="loading pic...">';
                    }
                }
            }
        }
    }

    public function showLeftPart()
    {
        //old: <span class="bold">'. $this->time .'</span>
        return '<div class="group-elem-left col-md-3">
                    <div class="group-elem-container">
                        <div class="time">
                            <span class="bold">' . $this->dateTransformed . '</span>                                      
                        </div>
                    </div>                    
                </div>';

    }

    public function showTextNotif()
    {
        if($this->picNotif == '')
        {
            return '<div class="info-notif-container col-md-12">
                        <p class="col-md-12">'. $this->textNotif .'</p>
                    </div>';
        }
        else{
            return '<div class="info-notif-container col-md-12">
                        <p class="col-md-8">'. $this->textNotif .'</p>
                        <div class="pic-notif-container col-md-4">
                            <span>'. $this->picNotif .'</span>
                        </div>                      
                    </div>';
        }
    }

    public function showRightPart()
    {
        $leftPart = '';
        $rightPart = '';
        switch ($this->codeNotif) {
            //LIKE NOTIFICATION
            case 8:
                $leftPart = '<div class="contact-infos-container col-md-9">
                                <div class="contact-infos">
                                    <h1 class="complete-name">' . $this->qiUserFrom->complete_name . '</h1>
                                    <div class="user-actions col-md-12">
                                        <p class="action bold">'. $this->langFile[$this->pageName]->title_field_liked .'</p>
                                    </div>
                                    '. $this->showTextNotif() .'                                 
                                </div>
                            </div>';

                $rightPart = '<div class="contact-pic-container col-md-3">
                                <div class="contact-pic pic">
                                    <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                </div>
                            </div>';
                break;

            //COMMENT NOTIFICATION
            case 9:
                $leftPart = '<div class="contact-infos-container col-md-9">
                                <div class="contact-infos">
                                    <h1 class="complete-name">' . $this->qiUserFrom->complete_name . '</h1>
                                    <div class="user-actions col-md-12">
                                        <p class="action bold">'. $this->langFile[$this->pageName]->title_field_commented .'</p>
                                    </div>
                                    '. $this->showTextNotif() .'
                                </div>
                            </div>';

                $rightPart = '<div class="contact-pic-container col-md-3">
                                <div class="contact-pic pic">
                                    <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                </div>
                            </div>';
                break;
            //SOMEONE IS FOLLOWING YOU
            case 10:
                $leftPart = '<div class="contact-infos-container col-md-9">
                                <div class="contact-infos">
                                    <h1 class="complete-name">' . $this->qiUserFrom->complete_name . '</h1>
                                    <div class="user-actions col-md-12">
                                        <p class="action bold">'. $this->langFile[$this->pageName]->title_field_isfollowingyou .'</p>
                                    </div>                                  
                                </div>
                            </div>';

                $rightPart = '<div class="contact-pic-container col-md-3">
                                <div class="contact-pic pic">
                                    <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                </div>
                            </div>';
                break;
            //SOMEONE IS ADDED TO YOUR CONTACTS
            case 11:
                $leftPart = '<div class="contact-infos-container col-md-9">
                                <div class="contact-infos">
                                    <h1 class="complete-name">' . $this->qiUserFrom->complete_name . '</h1>
                                    <div class="user-actions col-md-12">
                                        <p class="action bold">'. $this->langFile[$this->pageName]->notification_acceptedrequest .'</p>
                                    </div>                                  
                                </div>
                            </div>';

                $rightPart = '<div class="contact-pic-container col-md-3">
                                <div class="contact-pic pic">
                                    <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                </div>
                            </div>';
                break;
            //ASK FOR ADD NOTIFICATION
            case 12:
                $leftPart = '<div class="contact-infos-container col-md-9">
                                <div class="contact-infos">
                                    <h1 class="complete-name">' . $this->qiUserFrom->complete_name . '</h1>
                                    <div class="user-actions col-md-12">
                                        <p class="action bold">'. $this->langFile[$this->pageName]->title_field_sentyourequest .'</p>
                                    </div>
                                </div>
                            </div>';

                $rightPart = '<div class="contact-pic-container col-md-3">
                                <div class="contact-pic pic">
                                    <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                </div>
                            </div>';
                break;
        }

        return '<div class="group-elem-right col-md-9" data-notif-id="'. $this->id .'" data-notif-link="'. $this->notifLink .'">
                    <div class="group-elem-container">
                        '. $rightPart . $leftPart .'
                    </div>
                </div>';
    }

    //SHOW NOTIFICATION IN NOTIFICATION PAGE
    public function showNotification()
    {
        return '<div class="notif-container col-md-12">
                    '. $this->showLeftPart() .'
                    '. $this->showRightPart() .'
                </div>';
    }

    //SHOW NOTIFICATION IN UDER MEDU : Um
    public function showUmNotification()
    {
        $content = '';
        switch ($this->codeNotif) {
            //LIKE NOTIF
            case 8:
                if($_COOKIE['langwe'] == 'de') //#todo CHANGER CA: trouver une parade plus elegente
                {
                    $content = '<div class="under-menu-item col-md-12" data-notif-id="' . $this->id . '" data-notif-link="' . $this->notifLink . '">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                            </div>
                                        </div>
                                        <div class="users-ids user-actions-container col-md-9">
                                            <h4>' . $this->userFrom->nickname . '</h4>
                                            <div class="user-actions col-md-12">
                                                <p class="location">'. $this->langFileHeader->word_yourpost .' </p><p class="action bold">'. $this->langFileHeader->word_liked .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                }
                else{
                    $content = '<div class="under-menu-item col-md-12" data-notif-id="' . $this->id . '" data-notif-link="' . $this->notifLink . '">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">'. $this->UserImages->showProfileUserPic_little() .'</a>
                                            </div>
                                        </div>
                                        <div class="users-ids user-actions-container col-md-9">
                                            <h4>' . $this->userFrom->nickname . '</h4>
                                            <div class="user-actions col-md-12">
                                                <p class="action bold">'. $this->langFileHeader->word_liked .' </p><p class="location">'. $this->langFileHeader->word_yourpost .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                }
                break;
            //COMMENT NOTIF
            case 9:
                if($_COOKIE['langwe'] == 'de') //#todo CHANGER CA: trouver une parade plus elegente
                {
                    $content = '<div class="under-menu-item col-md-12" data-notif-id="'. $this->id .'" data-notif-link="'. $this->notifLink .'">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                            </div>
                                        </div>
                                        <div class="users-ids user-actions-container col-md-9">
                                            <h4>'. $this->userFrom->nickname .'</h4>
                                            <div class="user-actions col-md-12">
                                                <p class="location">' . $this->langFileHeader->word_yourpost .' </p><p class="action bold">'. $this->langFileHeader->word_commented . '</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                }
                if($_COOKIE['langwe'] == 'en'){
                    $content = '<div class="under-menu-item col-md-12" data-notif-id="'. $this->id .'" data-notif-link="'. $this->notifLink .'">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                            </div>
                                        </div>
                                        <div class="users-ids user-actions-container col-md-9">
                                            <h4>'. $this->userFrom->nickname .'</h4>
                                            <div class="user-actions col-md-12">
                                                <p class="action bold">' . $this->langFileHeader->word_commented .' </p><p class="location">'. $this->langGenerals->word_on .' '. $this->langFileHeader->word_yourpost . '</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                }
                else{
                    $content = '<div class="under-menu-item col-md-12" data-notif-id="'. $this->id .'" data-notif-link="'. $this->notifLink .'">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                            </div>
                                        </div>
                                        <div class="users-ids user-actions-container col-md-9">
                                            <h4>'. $this->userFrom->nickname .'</h4>
                                            <div class="user-actions col-md-12">
                                                <p class="action bold">' . $this->langFileHeader->word_commented .' </p><p class="location">'. $this->langFileHeader->word_yourpost . '</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                }
                break;
            //SOMEONE IS FOLLOWING YOU
            case 10:
                $content = '<div class="under-menu-item col-md-12" data-notif-id="'. $this->id .'" data-notif-link="'. $this->notifLink .'">
                                <div class="user-infos-container col-md-12">
                                    <div class="um-pic-container col-md-3">
                                        <div class="pic um-pic">
                                            <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                        </div>
                                    </div>
                                    <div class="users-ids user-actions-container col-md-9">
                                        <h4>'. $this->userFrom->nickname .'</h4>
                                        <div class="user-actions col-md-12">
                                            <p class="action bold">' . $this->langFileHeader->word_isfollowing . '</p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                break;
            //SOMEONE IS ADDED TO YOUR CONTACTS
            case 11:
                $content = '<div class="under-menu-item col-md-12" data-notif-id="'. $this->id .'" data-notif-link="'. $this->notifLink .'">
                                <div class="user-infos-container col-md-12">
                                    <div class="um-pic-container col-md-3">
                                        <div class="pic um-pic">
                                            <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                        </div>
                                    </div>
                                    <div class="users-ids user-actions-container col-md-9">
                                        <h4>'. $this->userFrom->nickname .'</h4>
                                        <div class="user-actions col-md-12">
                                            <p class="action bold">'. $this->langFileHeader->title_action_friend_request_accepted .'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                break;
            //ASK FOR ADD NOTIF
            case 12:
                $content = '<div class="under-menu-item col-md-12" data-notif-id="'. $this->id .'" data-notif-link="'. $this->notifLink .'">
                                <div class="user-infos-container col-md-12">
                                    <div class="um-pic-container col-md-3">
                                        <div class="pic um-pic">
                                            <a href="index.php?p=profile&u=' . $this->userFrom->slug . '">' . $this->UserImages->showProfileUserPic_little() . '</a>
                                        </div>
                                    </div>
                                    <div class="users-ids user-actions-container col-md-9">
                                        <h4>'. $this->userFrom->nickname .'</h4>
                                        <div class="user-actions col-md-12">
                                            <p class="action bold">'. $this->langFileHeader->title_action_contact_request_sent .'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                break;
        }
        return $content;
    }

    public function show()
    {
        if($this->id) //#todo to remove, juste au cas ou
        {
            if(Session::getInstance()->read('current-state')['state'] == 'owner')
            {
                if($this->todisplay == 'NotificationsPage')
                {
                    return $this->showNotification();
                }
                if($this->todisplay == 'UmNotifications')
                {
                    return $this->showUmNotification();
                }

            }
            if(Session::getInstance()->read('current-state')['state'] == 'viewer')
            {
                if($this->todisplay == 'NotificationsPage')
                {
                    return $this->showNotification();
                }
                if($this->todisplay == 'UmNotifications')
                {
                    return $this->showUmNotification();
                }
            }
        }

    }
}