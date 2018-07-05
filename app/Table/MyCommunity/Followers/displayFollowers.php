<?php

namespace app\Table\MyCommunity\Followers;

use app\Table\AppDisplay;
use core\Session\Session;
use core\Sort\Sort;

use app\Table\MyCommunity\Followers\Follower\displayFollower;

class displayFollowers extends AppDisplay
{
    private $followers;

    protected $pageName;

    public function __construct($followers = false)
    {
        $this->pageName = 'community';
        parent::__construct(false, $this->pageName);
        $this->followers = $followers;
    }

    public function showEdit()
    {

    }

    public function showBody()
    {
        $sort = new Sort();
        $arrayAlphAssoc = $sort->createAlphSortFromUserElems($this->followers);

        $content = '';
        foreach($arrayAlphAssoc as $alph => $followerArray)
        {
            //on créé le top jusqu'a l'ouverture du body-alph-container
            $top = '<div class="alph-bloc col-md-12" data-alph-index="'. $alph .'">
                        <div class="header-alph-container">
                            <div class="alph-title">
                                <div class="alph">
                                    <h1>'. $alph .'</h1>
                                </div>
                            </div>
                        </div>
                        <div class="body-alph-container">';

            //on insere dans le body alph container le contenu <=> les followers displayed
            $followerContent = '';
            foreach($followerArray as $follower){
                $display = new displayFollower($follower);
                $followerContent = $followerContent . $display->show();
            }

            //on ferme le top et le alph-bloc
            $content = $content . $top . $followerContent .  '</div></div>';
        }
        return $content;
    }

    public function showEmptyFollower()
    {
        return '<div class="followers-body">
                    <h1>'. $this->langFile[$this->pageName]->text_empty_follower .'</h1>
                </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if(empty($this->followers))
            {
                return $this->showMyFolowersEmpty($this->showEmptyFollower());
            }
            else{
                return $this->showMyFolowers($this->showBody());

            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            //return $this->showQuickInfos();
        }
    }
}