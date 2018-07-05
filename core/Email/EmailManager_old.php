<?php
namespace core\Email;

use app\App;
use app\Table\LangModel\LangModel;
use app\Table\UserModel\UserModel;
use core\Session\Session;
use app\Displays\Emails\displayEmails;

class EmailManager_old
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
        $action = 	'invitation';
        $replyTo =  false;

        //mail
        $this->mailWithPHPMailer($mailto, $object, $body, $action, $replyTo);
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
        $action = 	'confirmAccount';
        $replyTo =  false;

        //mail
        $this->mailWithPHPMailer($mailto, $object, $body, $action, $replyTo);
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
        $action = 	'confirmAccount';
        $replyTo =  false;

        //mail
        $this->mailWithPHPMailer($mailto, $object, $body, $action, $replyTo);
    }

    public function sendReportEemail($object, $message)
    {
        //prepare objects
        $display =      new displayEmails('ReportAbuseEmail', false, false, false, $object, $message);

        //prepare mail parameters
        $mailto =   $this->reportEmail;
        $object =   'Abuse reported by :' . $this->currentUser->firstname . '"'.$this->currentUser->nickname.'"' . $this->currentUser->lastname;
        $body =     $display->show();
        $action = 	'reportAbuse';
        $replyTo =  $this->currentUser->email;

        //mail
        $this->mailWithPHPMailer($mailto, $object, $body, $action, $replyTo);
    }

    public function sendContactEemail($object, $message)
    {
        //prepare objects
        $display =      new displayEmails('ContactEmail', false, false, false, $object, $message);

        //prepare mail parameters
        $mailto =   $this->contactEmail;
        $object =   'Contact :' . $this->currentUser->firstname . ' "'.$this->currentUser->nickname.'" ' . $this->currentUser->lastname;
        $body =     $display->show();
        $action = 	'contactSupport';
        $replyTo =  $this->currentUser->email;

        //mail
        $this->mailWithPHPMailer($mailto, $object, $body, $action, $replyTo);
    }

    public function sendResetPasswordMail($userid, $resettoken, $email)
    {
        //prepare user
        $UserModel = new UserModel();
        $user = $UserModel->getUserFromId($userid);

        //prepare mail parameters
        $mailto =   $email;
        $object =   'Password reset for : ' . $user->firstname . ' "'.$user->nickname.'" ' . $user->lastname;
        $action = 	'resetPassword';
        $replyTo =  false;

        //prepare objects
        $display =   new displayEmails('ResetEmail', false, $resettoken, $userid, false, false);
        $body =      $display->show();

        //mail
        return $this->mailWithPHPMailer($mailto, $object, $body, $action, $replyTo);
    }

    public function sendNotifEmailToUser($from, $to, $codeNotif, $elemid)
    {
        //prepare mail parameters
        $model = new UserModel();
        $user = $model->getUserFromId($to);

        $langModel = new LangModel();
        $traducedMessage = $langModel->getMessageLangNotif($user->langWebsite, $codeNotif);

        $mailto =   $user->email;
        $object =   'World eSport – '. $user->firstname .' "'.$user->nickname.'" '. $user->lastname .' '. $traducedMessage;

        //display mail body
        $typeMail = 'NotifEmail';
        $display =   new displayEmails($typeMail, false, false, false, false, false, $codeNotif, $from, $to, $elemid);
        $body =      $display->show();

        //mail
        $this->mailWithSendGrid($mailto, $object, $body);
    }

    public function mailWithPHPMailer($email, $object, $message, $type, $replyTo = false)
    {
        require('PHPmailer/PHPMailerAutoload.php');
        $mail = new  \PHPMailer(); 		// create a new object

        switch($type){
            case "confirmAccount":
                $mail->Username = "noreply@worldesport.com";
                $mail->Password = "W0rld3sp0rt";
                $mail->SetFrom("noreply@worldesport.com");
                break;
            case "resetPassword":
                $mail->Username = "noreply@worldesport.com";
                $mail->Password = "W0rld3sp0rt";
                $mail->SetFrom("Reset@worldesport.com");
                break;
            case "contactSupport":
                $mail->Username = "noreply@worldesport.com";
                $mail->Password = "W0rld3sp0rt";
                $mail->SetFrom('contact@worldesport.com');
                break;
            case "reportAbuse":
                $mail->Username = "noreply@worldesport.com";
                $mail->Password = "W0rld3sp0rt";
                $mail->SetFrom('abuse@worldesport.com');
                break;
            case "invitation":
                $mail->Username = "noreply@worldesport.com";
                $mail->Password = "W0rld3sp0rt";
                $mail->SetFrom('Invitation@worldesport.com');
                break;
        }

        $mail->IsSMTP(); 				// enable SMTP
        $mail->SMTPDebug = 		0; 		// debugging: 0: nothing,  1 = errors and messages, 2 = data + messages
        $mail->SMTPAuth = 		true; 	// authentication enabled
        $mail->SMTPSecure = 	'ssl'; 	// secure transfer enabled REQUIRED for Gmail
        $mail->Host = 			"smtp.gmail.com";
        $mail->Port = 			465; 	// or 465
        $mail->CharSet =        'UTF-8';
        $mail->IsHTML(true);
        $mail->Subject = 		$object;
        $mail->Body = 			$message;
        $mail->AddAddress($email);

        //if reply to est paramété ajouter l'option
        if($replyTo)
        {
            $mail->AddReplyTo($replyTo);
        }

        if(!$mail->Send()) {
            return "errSend";
        }
        else{
            return "success";
        }
    }

    public function mailWithServeur($email, $object, $message)
    {
        $email = "pakrstic.pro@gmail.com";
        // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: notifications@worldesport.com' . "\r\n" .
                    'Content-type: text/html; charset=iso-8859-1';

        if(mail($email, $object, $message, $headers))
        {
            return "success";
        }
        else{
            return "errSend";
        }
    }

    public function mailWithSendGrid($email, $object, $message)
    {
        require('sendgrid-php/sendgrid-php.php');

        $email = "pakrstic.pro@gmail.com";
        $from = new \SendGrid\Email(null, "notifications@worldesport.com");
        $subject = $object;
        $to = new \SendGrid\Email(null, $email);
        $content = new \SendGrid\Content("text/html", $message);
        $mail = new \SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = $this->SENDGRI_API_KEY;
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        var_dump($response->statusCode());
        var_dump($response->headers());
        var_dump($response->body());
    }
}