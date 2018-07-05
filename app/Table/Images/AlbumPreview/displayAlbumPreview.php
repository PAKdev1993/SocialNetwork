<?php


namespace app\Table\Images\AlbumPreview;

use app\Table\AppDisplay;
use app\Table\Images\Images\ImagesForAlbumPreview\displayImagesForAlbumPreview;
use core\Session\Session;

//#todo mettre l'affichage de cette classe ds AppDisplay
class displayAlbumPreview extends AppDisplay
{
    protected $pageName;

    private $imgsObj;

    public function __construct($imgsObj = false, $userToDisplay = false)
    {
        $this->pageName = 'album';
        parent::__construct($userToDisplay, $this->pageName);

        $this->imgsObj = !$imgsObj ? false : $imgsObj;
    }

    public function showMyHeader()
    {
        return '<div class="title-aside-bloc col-md-12">
                    <h1><a href="index.php?p=profile&u='. $this->currentUser->slug.'&s=album">Album</a></h1>
                </div>';
    }

    public function showUserHeader()
    {
        return '<div class="title-aside-bloc col-md-12">
                    <h1><a href="index.php?p=profile&u='. $this->userToDisplay->slug .'&s=album">Album</a></h1>
                </div>';
    }

    public function showMyBody()
    {
        $content = '';
        foreach($this->imgsObj as $imgObj)
        {
            $display = new displayImagesForAlbumPreview($imgObj, $this->currentUser);
            $content = $content . '<div class="pic-elem-container">
                                        <div class="pic-elem">
                                            <a href="index.php?p=profile&u='. $this->currentUser->slug .'&s=album">
                                                '. $display->show() .'
                                            </a>
                                        </div>
                                    </div>';
        }

        return '<div class="bloc-container" id="album-preview-container">
                    '. $content .'
                </div>
                ';
    }

    public function showUserBody()
    {
        $content = '';
        foreach($this->imgsObj as $imgObj)
        {
            $display = new displayImagesForAlbumPreview($imgObj, $this->userToDisplay);
            $content = $content . '<div class="pic-elem-container">
                                        <div class="pic-elem">
                                             <a href="index.php?p=profile&u='. $this->userToDisplay->slug .'&s=album">
                                                '. $display->show() .'
                                            </a>
                                        </div>
                                    </div>';
        }

        return '<div class="bloc-container" id="album-preview-container">
                    '. $content .'
                </div>
                ';
    }

    public function showMyBodyEmpty()
    {
        return '<div class="bloc-container empty-album-container" id="album-preview-container">
                     <div class="empty-title-container col-md-12">
                         <h1>'. $this->langFile[$this->pageName]->title_empty_no_picture_posted .'</h1>
                     </div>   
                </div>
                ';
    }

    public function showUserBodyEmpty()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->userToDisplay->nickname .'</span> '. $this->langFile[$this->pageName]->text_empty_has_no_picture .'</p>
                    </div>                    
                </div>';
    }

    public function showMyAlbumPreview()
    {
        return '<div class="aside-left-bloc bloc">
                    '. $this->showMyHeader() . $this->showMyBody() .'
                </div>';
    }

    public function showUserAlbumPreview()
    {
        return '<div class="aside-left-bloc bloc">
                    '. $this->showUserHeader() . $this->showUserBody() .'
                </div>';
    }

    public function showMyAlbumPreviewEmpty()
    {
        return '<div class="aside-left-bloc bloc">
                    '. $this->showMyHeader() . $this->showMyBodyEmpty() .'
                </div>';
    }

    public function showUserAlbumPreviewEmpty()
    {
        return '<div class="aside-left-bloc bloc">
                    '. $this->showUserHeader() . $this->showUserBodyEmpty() .'
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if($this->imgsObj)
            {
                return $this->showMyAlbumPreview(); //#todo changer ca: degeulasse
            }
            else{
                return $this->showMyAlbumPreviewEmpty();
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if($this->imgsObj)
            {
                return $this->showUserAlbumPreview(); //#todo changer ca: degeulasse
            }
            else{
                return $this->showUserAlbumPreviewEmpty();
            }
        }
    }
}