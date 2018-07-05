<?php

namespace app\Table\ProfileViewers;

use core\Notifications\NotificationsManager;
use core\Session\Session;
use app\Table\AppDisplay;
use app\Table\ProfileViewers\Viewer\displayProfileViewer;

class displayProfileViewers extends AppDisplay //#todo cette class sert d'affichage pour le header et la right bar, corriger ca
{

    private $profileViewers;
    private $todisplay; // = ProfileViewers pour display un element de la page de viewers || UmViewers pou display un element de menu || = rightBloc pour afficher le rightSide bloc
    private $nbNotifs;

    protected $pageName;

    public function __construct($profileViewers = false, $todisplay = false)
    {
        $this->pageName = 'profileViews';
        parent::__construct(false, $this->pageName);
        $this->todisplay =          $todisplay;
        $this->profileViewers =     $profileViewers;
        $this->nbProfileViewers =   sizeof($this->profileViewers);

        //CALCUL NB NOTIFS
        $coreNotif =        new NotificationsManager();
        $this->nbNotifs =   $coreNotif->getNbNotifsForProfileViewers();
    }

    public function showEdit()
    {
        
    }

    public function showBody()
    {
        $content = '';
        foreach($this->profileViewers as $profileViewer)
        {
            $display = new displayProfileViewer($profileViewer, $this->todisplay); //#todo OPTIMISER: ici on charge un affichage pour chaques user du tableau meme si celui ci a déjà été chargé
            $content = $content . $display->show();
        }

        return $content;
    }

    public function showEmpty()
    {
        return '<div class="bloc-container empty-recommended-container">
                     <div class="empty-title-container col-md-12">
                         <h1>'. $this->langFile[$this->pageName]->text_empty_viewers .'</h1>
                     </div>   
                </div>';
    }

    public function showEmptyUm()
    {
        return '<div class="empty-title-container col-md-12">
                    <p>'. $this->langFileHeader->title_empty_notif .'</p>
               </div>';
    }

    public function showCounterRightSide()
    {
        return '<div class="quick-infos-container">                   
                    <p class="quickinfo-text"><span class="bold">'. $this->nbProfileViewers .'</span> '. $this->langFile[$this->pageName]->text_right_bloc_viewer .'</p>
                </div>';
    }

    public function showCounterRightSideEmpty()
    {
        return '<div class="quick-infos-container">                  
                    <p class="quickinfo-text"><span class="bold"> '. $this->langGenerals->word_noone .' </span> '. $this->langFile[$this->pageName]->text_right_bloc_viewer_empty .'</p>
                </div>';
    }
    
    public function showNotifBloc()
    {
        if(strlen($this->nbNotifs) >= 2)
        {
            $notifP = '<p class="lots-notifs">'. $this->nbNotifs .'</p>';
        }
        else{
            $notifP = '<p>'. $this->nbNotifs .'</p>';
        }

        if($this->nbNotifs == '0')
        {
            $notifP = '';
        }

        return '<div class="nbnotif-container">
                    <div class="nb-notifs">
                       '. $notifP .'
                    </div>
                </div>';
    }
    
    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if($this->profileViewers)
            {
                if($this->todisplay == 'ProfileViewers')
                {
                    return $this->showMyProfileViewers($this->showBody());
                }
                if($this->todisplay == 'UmViewers')
                {
                    return $this->showMyProfileViewerUm($this->showBody(), $this->showNotifBloc());
                }
                if($this->todisplay == 'rightBloc')
                {
                    return $this->showMyProfileViewerRightBloc($this->showCounterRightSide());
                }
            }
            else{
                if($this->todisplay == 'ProfileViewers')
                {
                    return $this->showMyProfileViewersEmpty($this->showEmpty());
                }
                if($this->todisplay == 'UmViewers')
                {
                    return $this->showMyProfileViewersUmEmpty($this->showEmptyUm());
                }
                if($this->todisplay == 'rightBloc')
                {
                    return $this->showMyProfileViewerRightBlocEmpty($this->showCounterRightSideEmpty());
                }
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if($this->profileViewers)
            {
                if($this->todisplay == 'UmViewers')
                {
                    return $this->showMyProfileViewerUm($this->showBody(), $this->showNotifBloc());
                }
            }
            else{
                if($this->todisplay == 'UmViewers')
                {
                    return $this->showMyProfileViewersUmEmpty($this->showEmptyUm());
                }
            }
        }
    }
}