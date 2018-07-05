<?php
namespace app\Displays\LinkPreview;

use app\Table\AppDisplay;

class displayLinkPreviews extends AppDisplay
{
    private $type;
    private $link;
    private $site_name;
    private $title;
    private $desc;
    private $img;
    private $post;
    private $empty;

    public function __construct($type = false, $link = false, $siteName = false, $siteTitle = false, $siteDesc = false, $siteImg = false, $post = false, $empty = false)
    {
        parent:: __construct();
        $this->type =       $type;
        $this->link =       $link;
        $this->site_name =  $siteName;
        $this->title =      $siteTitle;
        $this->desc =       $siteDesc;
        $this->img =        $siteImg;
        $this->post =       $post;
        $this->empty =      $empty;
    }

    public function showTitle()
    {
        return '<div class="title-preview-container">
                    <div class="title-preview">
                        <p class="sitename">'. $this->site_name .'</p>
                        <p class="site_title">'. $this->title .'</p>
                    </div>
                </div>';
    }

    public function showImg()
    {
        if($this->img != '')
        {
            return '<div class="image-preview">
                        <img src="'. $this->img .'" alt="'. $this->site_name .' image">
                    </div>';
        }
    }

    public function showYoutubeEmbed()
    {
        return '<div class="image-preview">
                    <iframe width="100%" height="100%" src="'. $this->img .'" frameborder="0" allowfullscreen></iframe>
                </div>';
    }

    public function showTwitchEmbed()
    {
        return '<div class="image-preview">
                    <iframe src="https://player.twitch.tv/?video=v'. $this->img .'" frameborder="0" scrolling="no" height="100%" width="100%" allowfullscreen></iframe>
                </div>';
    }

    public function showDesc()
    {
        if($this->desc != 'default')
        {
            return '<div class="preview-desc">
                        <p class="site_desc">'. $this->desc .'</p>
                    </div>';
        }
    }

    public function showSharePreview()
    {
        switch ($this->type){
            case "youtube":
                return '<div class="preview-content">
                            <div class="delete-preview">
                                <div class="before-close-preview" data-action="delete-preview">                               
                                </div>
                                <div class="cancel" data-action="cancel-delete-preview">
                                     '. $this->langGenerals->word_cancel .'
                                </div>
                            </div>
                            <a href="'. $this->link .'" target="_blank">                                
                                '. $this->showYoutubeEmbed() .'
                                '. $this->showTitle() .'
                                '. $this->showDesc() .'                       
                            </a>
                        </div>';
                break;
            case "twitch":
                return '<div class="preview-content">
                            <div class="delete-preview">
                                <div class="before-close-preview" data-action="delete-preview">                               
                                </div>
                                <div class="cancel" data-action="cancel-delete-preview">
                                     '. $this->langGenerals->word_cancel .'
                                </div>
                            </div>
                            <a href="'. $this->link .'" target="_blank">                                
                                '. $this->showTwitchEmbed() .'
                                '. $this->showTitle() .'
                                '. $this->showDesc() .'                       
                            </a>
                        </div>';
                break;
            default:
                return '<div class="preview-content">
                            <div class="delete-preview">
                                <div class="before-close-preview" data-action="delete-preview">                               
                                </div>
                                <div class="cancel" data-action="cancel-delete-preview">
                                     '. $this->langGenerals->word_cancel .'
                                </div>
                            </div>
                            <a href="'. $this->link .'" target="_blank">                                
                                '. $this->showImg() .'
                                '. $this->showTitle() .'    
                                '. $this->showDesc() .'                                           
                            </a>
                        </div>';
        }
    }

    public function showPreview()
    {
        switch ($this->type){
            case "youtube":
                return '<div class="preview-content" data-state="updated">
                            <div class="delete-preview">
                                <div class="before-close-preview" data-action="delete-post-preview">                               
                                </div>
                                <div class="cancel" data-action="cancel-delete-post-preview">
                                     '. $this->langGenerals->word_cancel .'
                                </div>
                            </div>
                            <a href="'. $this->link .'" target="_blank" class="link-preview">                                
                                '. $this->showYoutubeEmbed() .'
                                '. $this->showTitle() .'
                                '. $this->showDesc() .'                       
                            </a>
                        </div>';
                break;
            case "twitch":
                return '<div class="preview-content" data-state="updated">
                            <div class="delete-preview">
                                <div class="before-close-preview" data-action="delete-post-preview">                               
                                </div>
                                <div class="cancel" data-action="cancel-delete-post-preview">
                                     '. $this->langGenerals->word_cancel .'
                                </div>
                            </div>
                            <a href="'. $this->link .'" target="_blank" class="link-preview">                               
                                '. $this->showTwitchEmbed() .'
                                '. $this->showTitle() .'
                                '. $this->showDesc() .'                       
                            </a>
                        </div>';
                break;
            default:
                return '<div class="preview-content" data-state="updated">
                            <div class="delete-preview">
                                <div class="before-close-preview" data-action="delete-post-preview">                               
                                </div>
                                <div class="cancel" data-action="cancel-delete-post-preview">
                                     '. $this->langGenerals->word_cancel .'
                                </div>
                            </div>
                            <a href="'. $this->link .'" target="_blank" class="link-preview">                               
                                '. $this->showImg() .'
                                '. $this->showTitle() .'
                                '. $this->showDesc() .'                       
                            </a>
                        </div>';
        }
    }

    public function showUnavailablePreview()
    {
        return '<div class="preview-content empty">                           
                    <p class="preview-empty-desc">'. $this->langGenerals->mess_unavailable_link_preview .'</p>
                </div>';
    }

    public function show()
    {
        if($this->post){
            if(!$this->empty){
                return $this->showPreview();
            }
            else{
                return false;
            }
        }
        if($this->empty){
            return $this->showUnavailablePreview();
        }
        else{
            return $this->showSharePreview();
        }
    }
}