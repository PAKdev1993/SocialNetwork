<?php

namespace app\Table\btmodifs;

use app\Table\AppDisplay;

class btmodifsDisplay extends AppDisplay
{
    private $status;

    public function __construct($status = false)
    {
        parent::__construct();
        $this->status = $status;
    }

    public function show()
    {
        if($this->status == 'author')
        {
            return "<div class='bt-modifs infobulle'>
                        <button class='bt-delete'>
                            <span class='message'><span>". $this->langInfosBulles->action_delete_post ."</span></span>
                            <span class='pseudo'></span>
                        </button>
                    </div>
                    <div class='bt-modifs infobulle'>
                        <button class='bt-edit'>
                            <span class='message'><span>". $this->langInfosBulles->action_edit_post ."</span></span>
                            <span class='pseudo'></span>
                        </button>
                    </div>";
        }
        if($this->status == 'viewer')
        {
            return "<div class='bt-modifs infobulle'>
                        <button class='bt-hide'>
                            <span class='message'><span>". $this->langInfosBulles->action_hide_post ."</span></span>
                            <span class='pseudo'></span>
                        </button>
                    </div>
                    <div class='bt-modifs infobulle'>
                        <button class='bt-hide-all infobulle'>
                            <span class='message'><span>". $this->langInfosBulles->action_hide_userPosts ."</span></span>
                            <span class='pseudo'></span>
                        </button>
                    </div>";
        }
        if($this->status == 'edit-profile-elem')
        {
            return '<div class="edit-options-container">            
                        <div class="bt-modifs infobulle">
                            <button class="bt-delete">
                                <span class="message hided"><span>'. $this->langInfosBulles->action_delete_post .'</span></span>
                                <span class="pseudo"></span>
                            </button>
                        </div>
                        <div class="bt-modifs infobulle">
                            <button class="bt-edit">
                                <span class="message hided"><span>'. $this->langInfosBulles->action_edit_post .'</span></span>
                                <span class="pseudo"></span>
                            </button>
                        </div>
                    </div>';
        }
        if($this->status == 'edit-post-notify-my-network')
        {
            return '<div class="bt-modifs infobulle">
                        <button class="bt-delete">
                            <span class="message hided"><span>'. $this->langInfosBulles->action_delete_post .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>';
        }
        if($this->status == 'edit-conv-chatbox')
        {
            return '<div class="bt-modifs infobulle" data-elem="delete-conv">
                        <button class="bt-delete" data-action="delete-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_deleteconv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="delete-usr-conv">
                        <button class="bt-delete-user" data-action="del-users-from-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_deleteUserFromConv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="leave-conv">
                        <button class="bt-leave" data-action="leave-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_leaveConv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="rename-conv">
                        <button class="bt-edit" data-action="rename-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_changeConvName .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>';
        }
        if($this->status == 'edit-discussion-usertouser')
        {
            return '<div class="bt-modifs infobulle" data-elem="delete-conv">
                        <button class="bt-delete" data-action="delete-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_deleteconv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>                   
                    <div class="bt-modifs infobulle" data-elem="add-user-to-convers">
                        <button class="bt-add-user-to-convers" data-action="add-user-to-convers">
                            <span class="message"><span>Add users</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>';
        }
        if($this->status == 'edit-emptyConv')
        {
            return '<div class="bt-modifs infobulle" data-elem="delete-conv">
                        <button class="bt-delete" data-action="delete-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_deleteconv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="add-user-to-convers">
                        <button class="bt-add-user-to-convers" data-action="add-user-to-convers">
                            <span class="message"><span>Add users</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>';
        }
        if($this->status == 'edit-discussion-groupConv')
        {
            return '<div class="bt-modifs infobulle" data-elem="delete-conv">
                        <button class="bt-delete" data-action="delete-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_deleteconv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="delete-usr-conv">
                        <button class="bt-delete-user" data-action="del-users-from-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_deleteUserFromConv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="leave-conv">
                        <button class="bt-leave" data-action="leave-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_leaveConv .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="rename-conv">
                        <button class="bt-edit" data-action="rename-conv">
                            <span class="message"><span>'. $this->langInfosBulles->bt_changeConvName .'</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>
                    <div class="bt-modifs infobulle" data-elem="add-user-to-convers">
                        <button class="bt-add-user-to-convers" data-action="add-user-to-convers">
                            <span class="message"><span>Add users</span></span>
                            <span class="pseudo"></span>
                        </button>
                    </div>';
        }
    }
}