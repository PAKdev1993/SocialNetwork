<?php

namespace core\Langs;

class Langs
{
    private $cookielangName = 'langwe';

    protected $currentLang;
    protected $defaultlang = 'en';

    public function __construct($cookie)
    {
        $lang = $cookie->read($this->cookielangName);
        if(!$lang)
        {
            $this->changeLangFromUserBrowser($cookie, $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }
        else
        {
            $this->setLang($lang);
        }
    }

    public function changeLangFromUserBrowser($cookie, $serveur_http_language)
    {
        $langstring1 =  explode(",", $serveur_http_language);
        $langstring2 =  explode("-", $langstring1[0]);
        $lang = $langstring2[0];
        $cookie->write($this->cookielangName, $lang, 'month');
        $this->setLang($lang);
    }

    public function changeLangFromUserChoice($cookie, $lang)
    {
        $cookie->write($this->cookielangName, $lang, 'month');
        $this->setLang($lang);
    }

    public function setLang($lang)
    {
        $this->currentLang = $lang;
    }

    public function getCurrentLang()
    {
        return $this->currentLang;
    }
}