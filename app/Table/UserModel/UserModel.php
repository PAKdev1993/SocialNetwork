<?php
namespace app\Table\UserModel;

use app\App;
use core\Session\Session;

use core\Functions;

class UserModel
{
    private $db;

    //#todo effectuer les modification pour ne plus jamais passer le db en paramètre
    public function __construct($db = false)
    {
        if($db)
        {
            $this->db = $db;
        }
        else{
            $this->db = App::getDatabase();
        }
        $this->session = Session::getInstance();

    }

    public function getUserFromEmail($email)
    {
        return $this->db->query('SELECT email FROM we__User WHERE email = ?', [$email])->fetch();
    }

    public function getUserFromId($userid){
        return $this->db->query('SELECT * FROM we__User WHERE pk_iduser = ?', [$userid])->fetch();
    }

    public function getUserFromUserToken($userid, $token)
    {
        $user = $this->db->query('SELECT * FROM we__User WHERE pk_iduser = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_date > DATE_SUB(NOW(), INTERVAL 30 MINUTE)', [$userid, $token])->fetch();
        return $user;
    }

    public function getUserFromSlug($slug)
    {
        $user = $this->db->query('SELECT * FROM we__User WHERE slug = ?', [$slug])->fetch();
        return $user;
    }

    //#todo a mettre dans CommunityModel
    public function getMyContactsIdsInArray()
    {
        $usersIdsObj = $this->db->query('SELECT id_contact FROM we__Contacts WHERE fk_iduser = ?', [
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        $idArray = Functions::getArrayFromObjectProperty($usersIdsObj, 'id_contact');
        return $idArray;
    }

    //#todo a mettre dans CommunityModel
    public function getMyContactsPendingInArray()
    {
        $usersIdsObj = $this->db->query('SELECT fk_iduserto FROM we__Contactsask WHERE fk_iduserfrom = ?', [
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();

        $idArray = Functions::getArrayFromObjectProperty($usersIdsObj, 'fk_iduserto');
        return $idArray;
    }

    public function getLastPostActivity($iduser)
    {
        $lastPostActivity = $this->db->query('SELECT last_post_activity FROM we__user WHERE pk_iduser = ?', [$iduser])->fetch();
        return $lastPostActivity;
    }

    public function getUserIdFromSponsorToken($sponsorToken)
    {
        $iduser = $this->db->query('SELECT pk_iduser FROM we__user WHERE sponsor_token = ?', [$sponsorToken])->fetch()->pk_iduser;
        return $iduser;
    }

    public function getAvatar($userid)
    {
        return $this->db->query('SELECT background_profile FROM we__user WHERE pk_iduser = ?', [$userid])->fetch()->background_profile;
    }

    public function getMyGamerSummary()
    {
        return $this->db->query('SELECT gamersummary FROM we__user WHERE pk_iduser = ?', [Session::getInstance()->read('auth')->pk_iduser])->fetch()->gamersummary;
    }

    public function getMyEmployeeSummary()
    {
        return $this->db->query('SELECT employeesummary FROM we__user WHERE pk_iduser = ?', [Session::getInstance()->read('auth')->pk_iduser])->fetch()->employeesummary;
    }

    public function saveMyGamerSummary($content)
    {
        $content = Functions::secureVarSQL($content, '<a>');

        return $this->db->query('UPDATE we__user SET gamersummary = ? WHERE pk_iduser = ?', [
            $content,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function saveMyEmployeeSummary($content)
    {
        $content = Functions::secureVarSQL($content, '<a>');

        return $this->db->query('UPDATE we__user SET employeesummary = ? WHERE pk_iduser = ?', [
            $content,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function getMyInterests()
    {
        return $this->db->query('SELECT interests FROM we__user WHERE pk_iduser = ?', [Session::getInstance()->read('auth')->pk_iduser])->fetch()->interests;
    }

    public function saveMyInterests($content)
    {
        $content = Functions::secureVarSQL($content);

        return $this->db->query('UPDATE we__user SET interests = ? WHERE pk_iduser = ?', [
            $content,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function saveCoverPic($picName)
    {
        return $this->db->query('UPDATE we__user SET background_cover = ? WHERE pk_iduser = ?', [
            $picName,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function saveProfilePic($picName)
    {
        return $this->db->query('UPDATE we__user SET background_profile = ? WHERE pk_iduser = ?', [
            $picName,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function getProfilePicFromSlug($slug)
    {
        $bgProfile = $this->db->query('SELECT background_profile FROM we__user WHERE slug = ?', [$slug])->fetch()->background_profile;
        return $bgProfile;
    }

    public function getProfilePicFromId($iduser)
    {
        $bgProfile = $this->db->query('SELECT background_profile FROM we__user WHERE pk_iduser = ?', [$iduser])->fetch();
        if($bgProfile)
        {
            return $bgProfile->background_profile;
        }
        return $bgProfile;
    }

    public function updateNotifyMyNetwork($state)
    {
        return $this->db->query('UPDATE we__user SET notifyMyNetwork = ? WHERE pk_iduser = ?', [
            $state,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function getCurrentTeamFromIduser($iduser)
    {
        $team = $this->db->query('SELECT * FROM we__gamercareerteam WHERE fk_iduser = ? AND currentplayhere = ?', [
            $iduser,
            1
        ])->fetch();
        return $team;
    }

    public function getCurrentCompanyFromIduser($iduser)
    {
        $company = $this->db->query('SELECT * FROM we__quickinfos WHERE fk_iduser = ? AND currentlyWorkHere = ?', [
            $iduser,
            1
        ])->fetch();
        return $company;
    }

    public function getSlugFromId($iduser)
    {
        $slug = $this->db->query('SELECT slug FROM we__user WHERE pk_iduser = ?', [$iduser])->fetch();
        if($slug)
        {
            return $slug->slug;
        }
        return $slug;
    }

    public function getIdFromSlug($sluguser)
    {
        $iduser = $this->db->query('SELECT pk_iduser FROM we__user WHERE slug = ?', [$sluguser])->fetch();
        if($iduser)
        {
            return $iduser->pk_iduser;
        }
        return $iduser;
    }

    public function userExistFromSlug($sluguser)
    {
        $iduser = $this->db->query('SELECT pk_iduser FROM we__user WHERE slug = ?', [$sluguser])->fetch();
        if($iduser)
        {
            return true;
        }
        return false;
    }

    public function updateMyEmail($email)
    {
        $email = strip_tags($email);

        return $this->db->query('UPDATE we__user SET email = ? WHERE pk_iduser = ?', [
            $email,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function updateMyPassword($pwdCrypted)
    {
        return $this->db->query('UPDATE we__user SET pass = ? WHERE pk_iduser = ?', [
            $pwdCrypted,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function getEmailFromIduser($iduser)
    {
        $email = $this->db->query('SELECT email FROM we__user WHERE pk_iduser = ?', [$iduser])->fetch()->email;
        if($email)
        {
            return $email;
        }
        return false;
    }

    public function getSponsorTokenFromId($iduser)
    {
        $sponsorToken = $this->db->query('SELECT sponsor_token FROM we__user WHERE pk_iduser = ?', [$iduser])->fetch()->sponsor_token;
        if($sponsorToken)
        {
            return $sponsorToken;
        }
        return false;
    }

    public function updateUserLanguage($lang)
    {
        //si la langue de l'user est deja definie et identique a $lang on ne fait rien, sinon on update
        if($this->session->read('auth')->langWebsite != $lang)
        {
            $this->session->read('auth')->langWebsite = $lang;
            $this->db->query('UPDATE we__user SET langWebsite = ? WHERE pk_iduser = ?', [
                $lang,
                $this->session->read('auth')->pk_iduser
            ]);
        }
    }

    public function isOnline($bool)
    {
        if($bool == true)
        {
            $this->db->query('UPDATE we__user SET isOnline = ? WHERE pk_iduser = ?', [
                1,
                $this->session->read('auth')->pk_iduser
            ]);
            var_dump($bool);
            die('true');
        }
        else{
            $this->db->query('UPDATE we__user SET isOnline = ? WHERE pk_iduser = ?', [
                0,
                $this->session->read('auth')->pk_iduser
            ]);
            var_dump($bool);
            die('false');
        }
    }
}
