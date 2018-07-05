<?php

namespace core\Cookie;

use app\App;
use core\Session\Session;

class Cookie
{
    private $session;
    private $db;

    public function __construct($session = false)
    {
        //#todo enlever le paramettre DB au constructeur de Cookie
        $this->session = Session::getInstance();
        $this->db = App::getDatabase();
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
    
    public function connectFromCookie()
    {
        //$this->session->delete('auth');
        //test si le cookie existe et si l'user n'existe plus en session
        if (isset($_COOKIE['remember-me']) && !$this->user())
        {
            $remember_token = $_COOKIE['remember-me'];
            $parts = explode('==', $remember_token);
            $user_id = $parts[0];
            $token = $parts[1];
            $user = $this->db->query('SELECT * FROM we__user WHERE pk_iduser = ?', [$user_id])->fetch();

            //test si l'user existe
            if ($user)
            {
                //test si le token est le bon
                $expected = $user->remember_token;
                if ($token == $expected)
                {
                    $this->session->write('auth', $user);
                }
                else
                {
                    setcookie('remember-me', null, -1);
                }
            }
            else
            {
                setcookie('remember-me', null, -1);
            }
        }
    }

    public function write($key, $val, $time = 'week')
    {
        if($time == 'week')
        {
            setcookie($key, $val, time() + 60*60*24*7, '/');
        }
        if($time == 'month')
        {
            setcookie($key, $val, time() + 60*60*24*7*30, '/');
        }
    }

    public function read($key)
    {
        if(isset($_COOKIE[$key]))
        {
            return $_COOKIE[$key];
        }
        else
        {
            return false;
        }
    }

    public function deleteCookie($key)
    {
        unset($_COOKIE[$key]);
    }

    public function readCookie($key){
        return $_COOKIE[$key];
    }
}