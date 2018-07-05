<?php

namespace core\PageNotFound;

use app\App;

class NotFoundManager
{
    private $codeErr;/*  1 - le post n'existe plus
                         2 - l'user n'existe plus
                         3 - la page n'existe plus ou a expiré
                         4 - la page a expiré pour le token de rest password
                        */
    
    public function __construct($codeErr = false)
    {
        $this->pageName = 'pagenotfound';
        $this->codeErr = $codeErr;
        $this->langFile = App::getLangModel()->getPageLangFile($this->pageName);
    }
    
    public function getErrorMessage()
    {
        $message = '';
        switch($this->codeErr){
            case 1:
                $message = $this->langFile->text_deleted_page . '<a id="item-home" href="index.php?p=home">'. $this->langFile->text_deleted_page .'</a>';
                break;
            case 2:
                $message = $this->langFile->text_deleted_page . '<a id="item-home" href="index.php?p=home">'. $this->langFile->text_deleted_page .'</a>';
                break;
            case 4:
                $message = $this->langFile->text_deleted_page . '<a id="item-home" href="index.php">'. $this->langFile->title_landing_page .'</a> to reset your password';
                break;
            default:
                $message = $this->langFile->text_page_doesnt_exist;
                break;
        }
        return $message;
    }
}