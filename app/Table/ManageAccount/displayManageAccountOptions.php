<?php
namespace app\Table\ManageAccount;

use app\Table\AppDisplay;

class displayManageAccountOptions extends AppDisplay
{
    protected $pageName;

    private $emailAdress;
    private $manageAccountOptions; //tableau associatif contenant les elements sous forme: array['category'][arrayOptions]

    public function __construct($manageAccountOptions)
    {
        $this->pageName = 'manageaccount';
        parent::__construct(false, $this->pageName);
        $this->manageAccountOptions = $manageAccountOptions;
        $this->emailAdress = $this->currentUser->email;
    }

    public function showChangeEmailAdress()
    {
        return '<div class="manage-section">
                    <div class="title-section">
                        <h1>'. $this->langFile[$this->pageName]->title_change_email .'</h1>
                    </div>
                    <div class="body-section">
                        <div class="error-input-container error-email error-email-invalid col-md-7 novisible">
                            <p>'. $this->langFile[$this->pageName]->title_change_email .'</p>
                        </div>
                        <div class="error-input-container error-email error-email-inuse col-md-7 novisible">
                            <p>'. $this->langFile[$this->pageName]->error_email_inuse .'</p>
                        </div> 
                        <div class="input-container">                      
                            <input type="text" autocomplete="off" name="manage_email" placeholder="'. $this->langFile[$this->pageName]->placeholder_enter_email .'" class="input col-md-7" value="'. $this->emailAdress .'"/>
                        </div>                        
                    </div>
                </div>';
    }

    public function showChangePassword()
    {
        return '<div class="manage-section">
                    <div class="title-section">
                        <h1>'. $this->langFile[$this->pageName]->title_change_password .'</h1>
                    </div>
                    <div class="body-section">
                        <div class="error-input-container error-password error-password-match col-md-7 novisible">
                            <p>'. $this->langFile[$this->pageName]->error_password_match .'</p>
                        </div>
                        <div class="error-input-container error-password error-password-nbchar col-md-7 novisible">
                            <p>'. $this->langFile[$this->pageName]->error_password_size .'</p>
                        </div>
                        <div class="input-container">
                            <input type="password" autocomplete="off" name="manage_password" placeholder="'. $this->langFile[$this->pageName]->placeholder_enter_password .'" class="input col-md-7" value=""/>
                        </div>
                        <div class="input-container">
                            <input type="password" autocomplete="off" name="manage_password_confirm" placeholder="'. $this->langFile[$this->pageName]->placeholder_reenter_password .'" class="input col-md-7" value=""/>
                        </div>
                    </div>
                </div>';
    }

    public function showNotifWhen()
    {
        $title = '<div class="title-section">
                     <h1>'. $this->langFile[$this->pageName]->checkbox_wish_to_receive_email .'</h1>
                  </div>';

        $body = '';
        foreach ($this->manageAccountOptions['notifWhen'] as $option) //#todo simplifier ca ET corriger de [0] qui est toujour le meme (on a jamais [1])
        {
            //INPUT NOTIF CONTACT ASK
            if($option->notif_when_user_wtb_part_of_network)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_wtb_part_of_network" value="1" id="notif-network-checkbox" checked>
                                    <label for="notif-network-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_wants_to_be_part_of_network .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_wtb_part_of_network" value="0" id="notif-network-checkbox">
                                    <label for="notif-network-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_wants_to_be_part_of_network .'</label>
                                </div>';
            }

            //INPUT NOTIF CONTACT REQUEST ACCEPTED
            if($option->notif_when_user_accept_contact_request)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_accept_contact_request" value="1" id="notif-contact-request-checkbox" checked>
                                    <label for="notif-contact-request-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_accepted_request .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_accept_contact_request" value="0" id="notif-contact-request-checkbox">
                                    <label for="notif-contact-request-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_accepted_request .'</label>
                                </div>';
            }

            //INPUT NOTIF USER LIKE YOUR POST
            if($option->notif_when_user_like_post)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_like_post" value="1" id="notif-like-checkbox" checked>
                                    <label for="notif-like-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_likes_your_post .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_like_post" value="0" id="notif-like-checkbox">
                                    <label for="notif-like-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_likes_your_post .'</label>
                                </div>';
            }

            //INPUT NOTIF USER COMMENTED YOUR POST
            if($option->notif_when_user_comment_post)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_comment_post" value="1" id="notif-comment-checkbox" checked>
                                    <label for="notif-comment-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_comments_your_post .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="notif_when_user_comment_post" value="0" id="notif-comment-checkbox">
                                    <label for="notif-comment-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_comments_your_post .'</label>
                                </div>';
            }
        }

        $body = '<div class="body-section">
                    '. $body .'
                 </div>';

        $content = '<div class="manage-section">
                        '. $title
            . $body .'
                    </div>';
        return $content;
    }

    public function showEmailWhen()
    {
        $title = '<div class="title-section">
                     <h1>'. $this->langFile[$this->pageName]->title_wish_receive_email .'</h1>
                  </div>';

        $body = '';
        foreach ($this->manageAccountOptions['emailWhen'] as $option) //#todo simplifier ca, le rendre plus dynamique
        {
            //INPUT EMAIL CONTACT ASK
            if($option->email_when_user_wtb_part_of_network)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_wtb_part_of_network" value="1" id="email-network-checkbox" checked>
                                    <label for="email-network-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_wants_to_be_part_of_network .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_wtb_part_of_network" value="0" id="email-network-checkbox">
                                    <label for="email-network-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_wants_to_be_part_of_network .'</label>
                                </div>';
            }

            //INPUT EMAIL CONTACT REQUEST ACCEPTED
            if($option->email_when_user_accept_contact_request)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_accept_contact_request" value="1" id="email-contact-request-checkbox" checked>
                                    <label for="email-contact-request-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_accepted_request .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_accept_contact_request" value="0" id="email-contact-request-checkbox">
                                    <label for="email-contact-request-checkbox">'. $this->langFile[$this->pageName]->checkbox_when_user_accepted_request .'</label>
                                </div>';
            }

            //INPUT EMAIL USER LIKE YOUR POST
            if($option->email_when_user_like_post)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_like_post" value="1" id="email-like-checkbox" checked>
                                    <label for="email-like-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_likes_your_post .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_like_post" value="0" id="email-like-checkbox">
                                    <label for="email-like-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_likes_your_post .'</label>
                                </div>';
            }

            //INPUT EMAIL USER COMMENTED YOUR POST
            if($option->email_when_user_comment_post)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_comment_post" value="1" id="email-comment-checkbox" checked>
                                    <label for="email-comment-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_comments_your_post .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_comment_post" value="0" id="email-comment-checkbox">
                                    <label for="email-comment-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_comments_your_post .'</label>
                                </div>';
            }

            //INPUT EMAIL USER FOLLOW YOU
            if($option->email_when_user_follow_you)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_follow_you" value="1" id="email-follow-checkbox" checked>
                                    <label for="email-follow-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_follow_you .'</label>
                                 </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_when_user_follow_you" value="0" id="email-follow-checkbox">
                                    <label for="email-follow-checkbox">'. $this->langFile[$this->pageName]->checkbox_user_follow_you .'</label>
                                 </div>';
            }
        }

        $body = '<div class="body-section">
                    '. $body .'
                 </div>';

        $content = '<div class="manage-section">
                        '. $title
                         . $body .'
                    </div>';
        return $content;
    }

    public function showWeeklyEmailWhen()
    {
        $title = '<div class="title-section">
                     <h1>'. $this->langFile[$this->pageName]->checkbox_wish_to_receive_weekly_email .'</h1>
                  </div>';

        $body = '';
        foreach ($this->manageAccountOptions['weeklyEmailWhen'] as $option) //#todo simplifier ca, le rendre plus dynamique
        {
            //INPUT WEEKLY EMAIL LAST VISITORS
            if($option->email_weekly_summary_of_visitors)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_weekly_summary_of_visitors" value="1" id="weekly-summary-visitors" checked>
                                    <label for="weekly-summary-visitors">'. $this->langFile[$this->pageName]->checkbox_summary_who_visited_profile .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_weekly_summary_of_visitors" value="0" id="weekly-summary-visitors">
                                    <label for="weekly-summary-visitors">'. $this->langFile[$this->pageName]->checkbox_summary_who_visited_profile .'</label>
                                </div>';
            }

            //INPUT WEEKLY EMAIL
            if($option->email_weekly_list_of_recommended_contacts)
            {
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_weekly_list_of_recommended_contacts" value="1" id="weekly-rcmd-contacts-list" checked>
                                    <label for="weekly-rcmd-contacts-list">'. $this->langFile[$this->pageName]->checkbox_list_of_recommended_contacts .'</label>
                                </div>';
            }
            else{
                $body = $body . '<div class="input-container">
                                    <input type="checkbox" name="email_weekly_list_of_recommended_contacts" value="0" id="weekly-rcmd-contacts-list">
                                    <label for="weekly-rcmd-contacts-list">'. $this->langFile[$this->pageName]->checkbox_list_of_recommended_contacts .'</label>
                                </div>';
            }
        }

        $body = '<div class="body-section">
                    '. $body .'
                 </div>';

        $content = '<div class="manage-section">
                        '. $title
                        . $body .'
                    </div>';
        return $content;
    }

    /* OLD public function showBody()
    {
        return $this->showChangeEmailAdress() . $this->showChangePassword() . $this->showNotifWhen() . $this->showEmailWhen() . $this->showWeeklyEmailWhen();
    }*/
    
    public function showBody()
    {
        //old return $this->showChangeEmailAdress() . $this->showChangePassword() . $this->showEmailWhen() . $this->showWeeklyEmailWhen();
        return $this->showChangeEmailAdress() . $this->showChangePassword () . $this->showEmailWhen();
    }

    public function show()
    {
        return $this->showMyAccountManager($this->showBody());
    }
}