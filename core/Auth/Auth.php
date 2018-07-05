<?php

namespace core\Auth;

use app\Table\UserModel\UserModel;
use core\Cookie\Cookie;
use core\Email\EmailManager;
use core\Functions;
use app\App;
use core\Session\Session;

class Auth
{
    static $instance;

    private $session;
    private $db;

    private $options = [
        'logerror' => "vous n'avez pas le droit d'acceder a cette page"
    ];

    public function __construct($db = false, $session = false, $options = false)
    {
        $this->db =         $db ? $db : App::getDatabase();
        $this->options =    array_merge($this->options, (array)$options);
        $this->session =    Session::getInstance();
        $this->cookie =     App::getCookie();
    }

    public function connect($user)
    {
        $this->session->write('auth', $user);
    }

    public function isLogged()
    {
        if(!$this->session->read('auth') && !isset($_COOKIE['remember-me']) && !($this->isAdmin()))
        {
            $this->session->setFlash('danger', $this->options['logerror']);
            return false;
        }
        if($this->isAdmin())
        {
            return false;
        }
        return true;
    }

    public function isNotLogged()
    {
        if(!$this->session->read('auth'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isAdmin()
    {
        $user = $this->session->read('auth');
        if($user) {
            if ($user->email == "WorldEsport@worldesport.com" || $user->email == "test24@test24.com") {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function user()
    {
        if(!$this->session->read('auth'))
        {
            return false;
        }
        else
        {
            return $this->session->read('auth');
        }
    }

    public function logout()
    {
        setcookie('remember-me', NULL, -1, '/');
        $this->session->delete('auth');
        $this->session->delete('facebook_access_token');
        $this->session->deleteFlash('facebookerror');
        $this->session->deleteFlash('danger');
        $this->session->deleteFlash('special');
    }
    
    public function generateSponsorToken()
    {
        $ids = $this->db->query('SELECT pk_iduser FROM we__user', [])->fetchAll();
        foreach ($ids as $id)
        {
            $sponsorToken = Functions::str_random(60);
            $this->db->query('UPDATE we__user SET sponsor_token = ? WHERE pk_iduser = ?', [$sponsorToken, $id->pk_iduser]);
        }
    }

    public function register($firstname, $nickname, $lastname, $password, $email, $invited, $invitedByToken = false)
    {
        //envoyé en post par AJAX: fname, lname, nickname, email, pwd
        $slug =         $this->generateSlug($firstname, $nickname, $lastname);
        $password =     $this->hashPassword($password);
        $token =        Functions::str_random(60);
        $sponsorToken = Functions::str_random(60);
        $this->db->query("INSERT INTO we__user SET firstname = ?, nickname = ?, lastname = ?, pass = ?, email = ?, slug = ?, confirm_token = ?, invited = ?, sponsor_token = ?, langWebsite = ?", [
            $firstname,
            $nickname,
            $lastname,
            $password,
            $email,
            $slug,
            $token,
            (int)$invited,
            $sponsorToken,
            $this->cookie->read('langwe')
        ]);
        $user_id = $this->db->lastInsertId();

        //SEND CONFIRM MAIL
        $EmailManager = new EmailManager();
        if($invitedByToken)
        {
            $EmailManager->sendConfirmationOnParainnageMail($user_id, $token, $invitedByToken);
        }
        else{
            $EmailManager->sendConfirmationMail($user_id, $token);
        }
    }

    /**
     * @param $email
     * @param $password
     * @param bool $remember
     * @return bool|string|object db user
     */
    public function login($email, $password, $remember = false)
    {
        //test de l'existence de l'user
        $user = $this->db->query('SELECT * FROM we__user WHERE email = ?', [$email])->fetch();
        if($user)
        {
            //test de la correspondance des password
            if(password_verify($password, $user->pass))
            {
                //test de l'etat confirmé de l'user
                $user_confirmed = $this->db->query('SELECT * FROM we__user WHERE email = ? AND confirm_date IS NOT NULL', [$email])->fetch();
                if($user_confirmed)
                {
                    //init variables
                    $this->session->write('auth', $user);
                    $this->session->write('completeName', $user->firstname. ' "' .$user->nickname. '" ' .$user->lastname);
                    $this->session->setFlash('success', "You are now loged in");
                    $this->session->write('nbChatBoxesfresh', 0);
                    //$userModel = new UserModel();
                    //$userModel->isOnline($bool = true);
                    if($remember)
                    {
                        $this->remember($user->pk_iduser);
                        $result = "logged";
                    }
                    else
                    {
                        $result = "logged";
                    }
                }
                else
                {
                    $result = "error confirm";
                }
            }
            else
            {
                $result = "error login";
            }
        }
        else
        {
            $result = "error login";
        }

        return $result;
    }

    public function remember($user_id)
    {
        $remember_token = Functions::str_random(210) . sha1($user_id . 'MarcAlexPak');
        $this->db->query('UPDATE we__user SET remember_token = ? WHERE pk_iduser = ?', [$remember_token, $user_id]);
        $cookie = new Cookie();
        $cookieName = 'remember-me';
        $cookieValue = $user_id . '==' . $remember_token;
        $cookieTime = 'week';
        $cookie->write($cookieName, $cookieValue, $cookieTime);
    }

    /**
     * @param $user_id
     * @param $token , token de confirmation de compte
     * @param $session , session dans laquelle ecrire la valeur de 'auth' si la confirmation de compte est accepté
     * @param $invitedByToken token de parrainnage
     * @return bool
     */
    public function confirm($user_id, $token, $invitedByToken = false)
    {
        //test de l'existence de l'user ET de l'egalité du token
        $user = $this->db->query('SELECT * FROM we__user WHERE pk_iduser = ?', [$user_id])->fetch();
        if($user && $user->confirm_token == $token)
        {
            //Confirmation de l'account et connection
            $this->db->query('UPDATE we__user SET confirm_token = NULL, confirm_date = NOW() WHERE pk_iduser= ?', [$user_id]);
            $this->session->write('auth', $user);

            //Definition du parrain
            if($invitedByToken)
            {
                $model =        new UserModel();
                $inviteBy =     $model->getUserIdFromSponsorToken($invitedByToken);
                $this->db->query("INSERT INTO we__invitedBy SET invitedBy = ?, isInvited = ?", [
                    $inviteBy,
                    $user_id
                ]);
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function resetPassword($email)
    {
        //envoyé en post par AJAX: email

        //test si l'user existe
        $user = $this->db->query('SELECT * FROM we__user WHERE email = ?', [$email])->fetch();
        if($user)
        {
            //test si l'user a confirmé son compte
            $user = $this->db->query('SELECT * FROM we__user WHERE email = ? AND confirm_date IS NOT NULL', [$email])->fetch();
            if($user)
            {
                $reset_token = Functions::str_random(60);
                $this->db->query('UPDATE we__user SET reset_token = ?, reset_date = NOW() WHERE pk_iduser = ?', [$reset_token, $user->pk_iduser]);


                //SEND RESET MAIL
                $EmailManager = new EmailManager();
                $msg = $EmailManager->sendResetPasswordMail($user->pk_iduser, $reset_token, $email);
                return $msg;
            }
            else
            {
                $msg = 'confirm issue';
                return $msg;
            }
        }
        else
        {
            $msg = 'user issue';
            return $msg;
        }
    }
    
    public function resetPasswordFromPageReset($pwd, $userid)
    {
        $password =     $this->hashPassword($_POST['pwd-reset']);
        $user_id =      $userid;
        return $this->db->query('UPDATE we__user SET pass = ?, reset_token = NULL, reset_date = NULL WHERE pk_iduser = ?', [$password, $user_id]);
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function generateSlug($firstname, $nickname, $lastname)
    {
        $slugtmp = $firstname. '-' . $nickname . '-' . $lastname;
        $slugtmp = str_replace(' ','-',$slugtmp); //remplacement des spaces par un tiret
        $nbDuplication = $this->db->query("SELECT COUNT(*) as nbDuplications FROM we__user WHERE slug LIKE CONCAT('%','".$slugtmp."', '%')", [$slugtmp])->fetch()->nbDuplications;
        if($nbDuplication)
        {
            $slug =  $slugtmp . $nbDuplication;
        }
        else{
            $slug =  $slugtmp;
        }
        return $slug;
    }
}