<?php

namespace core\Session;

use app\Table\UserModel\UserModel;

class Session
{
    static $instance;

    static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function __construct()
    {
        session_start();
    }

    public function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    public function hasFlashes()
    {
        return isset($_SESSION['flash']);
    }

    public function getFlashes()
    {
        $flash =  $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;

    }

    public function write($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function writeLangFileArray($pageName, $langFile)
    {
        $_SESSION['langFiles'][$pageName] = $langFile;
    }

    /**
     * This function get langfiles by key name or all langfiles
     * @param bool $key nom du langfile a recuperer
     * @return array Session langfile array
     */
    public function readLangFile($key = false) //#todo ajouter le landing langFile a ce tableau
    {
        if($key){
            return isset($_SESSION['langFiles'][$key]) ? $_SESSION['langFiles'][$key] : null;
        }
        else{
            if(isset($_SESSION['langFiles'])) // cas de lalanding page ou le langFile est stocké ailleur
            {
                return $_SESSION['langFiles'];
            }
        }
    }

    public function read($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public function deleteFlash($key)
    {
        unset($_SESSION['flash'][$key]);
    }


    public function checkValueInSession($value, $sessionArrayName)
    {
        if(isset($_SESSION[$sessionArrayName]))
        {
            if(in_array($value, $_SESSION[$sessionArrayName], false))
            {
                return true;
            }
        }
        return false;
    }

    //#todo mettre les noms des actions ds un array, les ecrire a lamain n'ets pas une bosse solution
    public function setCurentAction($actioname, $idelem)
    {
        $this->write('current-action', ['actionname' => $actioname, 'idelem' => $idelem]);
    }

    public function setCurentState($state, $iduser = false)
    {
        //state; etat: owner / viewer, id: si page profile: id
        $this->write('current-state', ['state' => $state]);
        if($iduser)
        {
            $this->write('current-state', ['state' => $state, 'userid' => $iduser]);
        }
    }

    public function isOwner()
    {
        return self::$instance->read('current-state')['state'] == 'owner';
    }
}