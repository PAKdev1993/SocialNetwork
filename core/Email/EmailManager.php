<?php
namespace core\Email;

use app\App;
use app\Table\LangModel\LangModel;
use app\Table\UserModel\UserModel;
use core\Session\Session;
use app\Displays\Emails\displayEmails;

class EmailManager
{
    private $langFile;
    private $currentUser;

    private $reportEmail;
    private $contactEmail;

    private $SENDGRI_API_KEY;

    public function __construct()
    {
        $this->langFile =       App::getLangModel()->getMailLangs();
        $this->currentUser =    Session::getInstance()->read('auth');

        $this->reportEmail =    'help@worldesport.com';
        $this->contactEmail =   'contact@worldesport.com';
        $this->legalEmail =     'legal@worldesport.com';

        $this->SENDGRI_API_KEY = "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";
    }

    public function sendInvitationEmail($email)
    {
        //prepare objects
        $display =      new displayEmails('InvitationEmail');

        //prepare mail parameters
        $mailto =   $email;
        $object =   'World eSport - '. $this->currentUser->firstname .' "'. $this->currentUser->nickname .'" '. $this->currentUser->lastname .' has invited you to join World eSport';
        $body =     $display->show();
        $type = 	'invitation';
        $replyTo =  false;

        //mail
        $this->mailWithSendGrid($mailto, $object, $body, $type, $replyTo);
    }

    public function sendConfirmationMail($userid, $tokenConfirm)
    {
        //prepare objects
        $display =      new displayEmails('ConfirmEmail', false, $tokenConfirm, $userid);
        $UserModel =    new UserModel();

        //prepare mail parameters
        $mailto =   $UserModel->getEmailFromIduser($userid);
        $object =   'World eSport - Please confirm your email';
        $body =     $display->show();
        $type = 	'confirmAccount';
        $replyTo =  false;

        //mail
        $this->mailWithSendGrid($mailto, $object, $body, $type, $replyTo);
    }

    public function sendConfirmationOnParainnageMail($userid, $tokenConfirm, $tokenParrainage)
    {
        //prepare objects
        $display =      new displayEmails('ConfirmOnParrainageEmail', $tokenParrainage, $tokenConfirm, $userid, $tokenParrainage);
        $UserModel =    new UserModel();

        //prepare mail parameters
        $mailto =   $UserModel->getEmailFromIduser($userid);
        $object =   'World eSport - Please confirm your email';
        $body =     $display->show();
        $type = 	'confirmAccount';
        $replyTo =  false;

        //mail
        $this->mailWithSendGrid($mailto, $object, $body, $type, $replyTo);
    }

    public function sendReportEemail($object, $message)
    {
        //prepare objects
        $display =      new displayEmails('ReportAbuseEmail', false, false, false, $object, $message);

        //prepare mail parameters
        $mailto =   $this->reportEmail;
        $object =   'Abuse reported by :' . $this->currentUser->firstname . '"'.$this->currentUser->nickname.'"' . $this->currentUser->lastname;
        $body =     $display->show();
        $type = 	'reportAbuse';
        $replyTo =  $this->currentUser->email;

        //mail
        $this->mailWithSendGrid($mailto, $object, $body, $type, $replyTo);
    }

    public function sendContactEemail($object, $message)
    {
        //prepare objects
        $display =      new displayEmails('ContactEmail', false, false, false, $object, $message);

        //prepare mail parameters
        $mailto =   $this->contactEmail;
        $object =   'Contact :' . $this->currentUser->firstname . ' "'.$this->currentUser->nickname.'" ' . $this->currentUser->lastname;
        $body =     $display->show();
        $type = 	'contactSupport';
        $replyTo =  $this->currentUser->email;

        //mail
        $this->mailWithSendGrid($mailto, $object, $body, $type, $replyTo);
    }

    public function sendResetPasswordMail($userid, $resettoken, $email)
    {
        //prepare user
        $UserModel = new UserModel();
        $user = $UserModel->getUserFromId($userid);

        //prepare mail parameters
        $mailto =   $email;
        $object =   'Password reset for : ' . $user->firstname . ' "'.$user->nickname.'" ' . $user->lastname;
        $type = 	'resetPassword';
        $replyTo =  false;

        //prepare objects
        $display =   new displayEmails('ResetEmail', false, $resettoken, $userid, false, false);
        $body =      $display->show();

        //mail
        $this->mailWithSendGrid($mailto, $object, $body, $type, $replyTo);

        //return
        //#todo faire en sorte que ds ce genre d'meail le message de validation ne s'affiche que si l'email parvient (par default on admet que l'email parvient a chaque fois)
        return "success";
    }

    public function sendNotifEmailToUser($from, $to, $codeNotif, $elemid)
    {
        //prepare mail parameters
        $model = new UserModel();
        $user = $model->getUserFromId($to);

        $langModel = new LangModel();
        $traducedMessage = $langModel->getMessageLangNotif($user->langWebsite, $codeNotif);

        $mailto =   $user->email;
        $object =   'World eSport – '. $this->currentUser->firstname .' "'.$this->currentUser->nickname.'" '. $this->currentUser->lastname .' '. $traducedMessage;

        //get codes notifs signification
        $likeNotif =        8;
        $commentNotif =     9;
        $contactNotif =     11;
        $askContactNotif =  12;
        $followerNotif =    10;

        $type = '';
        switch($codeNotif){
            case $likeNotif:
                $type = 'likeNotif';
                break;
            case $commentNotif:
                $type = 'commentNotif';
                break;
            case $contactNotif:
                $type = 'contactNotif';
                break;
            case $askContactNotif:
                $type = 'askNotif';
                break;
            case $followerNotif:
                $type = 'followNotif';
                break;
        }

        //display mail body
        $typeMail = 'NotifEmail';
        $display =   new displayEmails($typeMail, false, false, false, false, false, $codeNotif, $from, $to, $elemid);
        $body =      $display->show();

        //mail
        $this->mailWithSendGrid($mailto, $object, $body, $type);
    }
    
    public function mailWithSendGrid($email, $object, $message, $type, $replyTo = false)
    {
        require('sendgrid-php/sendgrid-php.php');

        $from = '';
        $categorie = '';
        switch($type){
            case "confirmAccount":
                $categorie = 'Confirmation email';
                $from = "noreply@worldesport.com";
                break;
            case "resetPassword":
                $categorie = 'Forgotten password';
                $from = "Reset@worldesport.com";
                break;
            case "contactSupport":
                $categorie = "Contact support";
                $from = 'contact@worldesport.com';
                break;
            case "reportAbuse":
                $categorie = "Abuse report";
                $from = "abuse@worldesport.com";
                break;
            case "invitation":
                $categorie = "Invitation email";
                $from = 'Invitation@worldesport.com';
                break;
            case "likeNotif":
                $categorie = 'New like';
                $from = "notifications@worldesport.com";
                break;
            case "commentNotif":
                $categorie = 'New comment';
                $from = "notifications@worldesport.com";
                break;
            case "contactNotif":
                $categorie = "New contact";
                $from = "notifications@worldesport.com";
                break;
            case "askNotif":
                $categorie = "New contact request";
                $from = "notifications@worldesport.com";
                break;
            case "followNotif":
                $categorie = "New follower";
                $from = "notifications@worldesport.com";
        }

        $email = "pakrstic.pro@gmail.com";
        $fromSendGrid = new \SendGrid\Email(null, $from);
        $subject = $object;
        $to = new \SendGrid\Email(null, $email);
        $content = new \SendGrid\Content("text/html", $message);
        $mail = new \SendGrid\Mail($fromSendGrid, $subject, $to, $content);
        //ajouter category pour les stats
        $mail->addCategory($categorie);
        //add replay to if nescessary
        if($replyTo)
        {
            $reply_to = new \SendGrid\ReplyTo($replyTo);
            $mail->setReplyTo($reply_to);
        }

        $apiKey = $this->SENDGRI_API_KEY;
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        //status code pour email envoyé = 201
        /*var_dump($response->statusCode());
        var_dump($response->headers());
        var_dump($response->body());*/
    }
}