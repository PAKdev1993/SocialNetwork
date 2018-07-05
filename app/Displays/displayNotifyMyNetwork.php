<?php
/**
 * Created by PhpStorm.
 * User: PAK
 * Date: 07/08/2016
 * Time: 23:19
 */

namespace app\Displays;

use app\Table\AppDisplay;

class displayNotifyMyNetwork extends AppDisplay
{
    private $notifyMyNetworkState;
    protected $pageName;
    
    public function __construct()
    {
        $this->pageName = 'profile';
        parent::__construct(false, $this->pageName);
        $this->notifyMyNetworkState = $this->currentUser->notifyMyNetwork;
    }
    
    public function showContent()
    {
        switch ($this->notifyMyNetworkState){
            case true:
                $class = 'class=active-notify';
                break;
            default:
                $class = '';
        }
        return '<div class="notify-left-part col-md-8">
                    <h3>'. $this->langFile[$this->pageName]->title_notifyMyNetwork .'</h3>
                </div>
                <div class="notify-right-part col-md-4">
                    <div id="toggle-notif-container" '. $class .'>
                        <div class="toggle-background">
       
                        </div>
                        <div class="toggle-notif-msg msg-y">
                            <p>'. $this->langFile[$this->pageName]->title_no .'</p>
                        </div>
                        <div class="toggle-notif-msg msg-n">
                            <p>'. $this->langFile[$this->pageName]->title_yes .'</p>
                        </div>
                        <div class="msg-cursor">
                        </div>
                    </div>
                </div>';
    }

    public function show()
    {
        return $this->showNotifyMyNetwork($this->showContent());
    }
}