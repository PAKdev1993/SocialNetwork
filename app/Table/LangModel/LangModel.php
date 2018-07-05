<?php

namespace app\Table\LangModel;

use core\Functions;
use core\Langs\Langs;
use app\App;

class LangModel extends Langs
{
    private $db;

    public function __construct($db = false)
    {
        parent::__construct(App::getCookie());
        $this->db = $db ? $db : App::getDatabase();
    }

    public function getLang()
    {
        $cookie = App::getCookie();
        return $cookie->read('langwe');
    }

    //on recupère l'array le langFile spécifié
    public function getPageLangFile($page)
    {
        if($page == "Permalink"){
            $page = 'Home';
        }
        $page = Functions::secureVarSQL($page);
        $langFile = $this->db->query('SELECT * FROM we__lang__'. $page . ' WHERE fk_langname = ?', [$this->currentLang])->fetch();
        if($langFile){
            return $langFile;
        }
        else
        {
            return $this->db->query('SELECT * FROM we__lang__'. $page . ' WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getLangMonths()
    {
        $monthsTraduce = $this->db->query('SELECT * FROM we__lang__months WHERE fk_langname = ?', [$this->currentLang])->fetch();
        if($monthsTraduce)
        {
            return $monthsTraduce;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__months WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getLangGear()
    {
        $gearTraduce = $this->db->query('SELECT * FROM we__lang__gear WHERE fk_langname = ?', [$this->currentLang])->fetch();
        if($gearTraduce)
        {
            return $gearTraduce;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__gear WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function addLanguageToLanguages($ISO_639_1)
    {
        $this->db->query('INSERT INTO `we__lang__lang` (`pk_idlang`, `langname`) VALUES (?, ?)', [NULL, $ISO_639_1]);
    }

    public function addLanguageToPage($pagename, $liblang)
    {
        $pagename = Functions::secureVarSQL($pagename);
        $this->db->query("INSERT INTO `we__lang__". $pagename ."` SET fk_langname = ?", [$liblang]);
    }

    public function addTrad($pagename, $liblang, $libtext, $val)
    {
        $pagename   = Functions::secureVarSQL($pagename);
        $libtext    = Functions::secureVarSQL($libtext);
        $this->db->query("UPDATE we__lang__". $pagename ." SET ". $libtext ." = ? WHERE fk_langname = ?", [$val, $liblang]);
    }

    public function getLangsFromDb()
    {
        return $this->db->selectAll('we__lang__lang')->fetchAll();
    }

    public function getPageLangsFromBd($page)
    {
        $page = Functions::secureVarSQL($page);
        return $this->db->query("SELECT fk_langname FROM we__lang__" . $page)->fetchAll();
    }

    public function getTraduceFromDb($dataLang, $lang, $page)
    {
        $dataLang   = Functions::secureVarSQL($dataLang);
        $page       = Functions::secureVarSQL($page);
        return $this->db->query("SELECT " . $dataLang . " FROM we__lang__" . $page . " WHERE fk_langname = ?", [$lang])->fetch();
    }

    public function updateTraduce($newTraduce, $dataLang, $lang, $page)
    {
        $dataLang   = Functions::secureVarSQL($dataLang);
        $page       = Functions::secureVarSQL($page);
        $this->db->query("UPDATE we__lang__" . $page . " SET ".$dataLang ." = ? WHERE fk_langname = ? ", [$newTraduce, $lang]);
    }

    public function getActionTraduce($actionName)
    {
        $actionName   = Functions::secureVarSQL($actionName);
        $actiontraduced = $this->db->query("SELECT ". $actionName ." FROM we__lang__actions WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($actiontraduced)
        {
            return $actiontraduced->$actionName;
        }
        else{
            return $this->db->query('SELECT '. $actionName .' FROM we__lang__actions WHERE fk_langname = ?', [$this->defaultlang])->fetch()->$actionName;
        }
    }

    public function getMailLangs()
    {
        $langFileMail = $this->db->query("SELECT * FROM we__lang__actions WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFileMail)
        {
            return $langFileMail;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__actions WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getMessageLangNotif($lang, $codeNotif)
    {
        //get codes notifs signification
        $likeNotif =        8;
        $commentNotif =     9;
        $contactNotif =     11;
        $askContactNotif =  12;
        $followerNotif =    10;

        //get traduce field name associate to notification message
        $fieldName = '';
        switch($codeNotif){
            case $likeNotif:
                $fieldName = "like_post";
                break;
            case $commentNotif:
                $fieldName = "post_comented";
                break;
            case $contactNotif:
                $fieldName = "new_contact";
                break;
            case $askContactNotif:
                $fieldName = "ask_contact";
                break;
            case $followerNotif:
                $fieldName = "new_follower";
                break;
        }

        //get traduce
        $tradNotif = $this->db->query("SELECT $fieldName FROM we__lang__emails WHERE fk_langname = ?", [$lang])->fetch()->$fieldName;
        if($tradNotif){
            return $tradNotif;
        }
        else{
            return $this->db->query("SELECT $fieldName FROM we__lang__emails WHERE fk_langname = ?", [$this->defaultlang])->fetch()->$fieldName;
        }
    }

    public function getMessageLangBtNotifMail($lang, $codeNotif)
    {
        //get codes notifs signification
        $likeNotif =        8;
        $commentNotif =     9;
        $contactNotif =     11;
        $askContactNotif =  12;
        $followerNotif =    10;

        //get traduce field name associate to notification message
        $fieldName = '';
        switch($codeNotif){
            case $likeNotif:
                $fieldName = "bt_view_post";
                break;
            case $commentNotif:
                $fieldName = "bt_view_post";
                break;
            case $contactNotif:
                $fieldName = "bt_view_profile";
                break;
            case $askContactNotif:
                $fieldName = "bt_view_profile, bt_accept";
                break;
            case $followerNotif:
                $fieldName = "bt_view_profile";
                break;
        }

        //get traduce
        $tradBtNotif = $this->db->query("SELECT $fieldName FROM we__lang__emails WHERE fk_langname = ?", [$lang])->fetchAll();
        if($tradBtNotif){
            return $tradBtNotif;
        }
        else{
            return $this->db->query("SELECT $fieldName FROM we__lang__emails WHERE fk_langname = ?", [$this->defaultlang])->fetchAll();
        }
    }

    public function getBtUnsubscribe($lang)
    {
        return $this->db->query("SELECT bt_unsubscribe FROM we__lang__emails WHERE fk_langname = ?", [$lang])->fetch()->bt_unsubscribe;
    }

    public function getCodeOfConductLangFile()
    {

        $langFile = $this->db->query("SELECT * FROM we__lang__codeofconduct WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile->cod_of_conduct;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__codeofconduct WHERE fk_langname = ?', [$this->defaultlang])->fetch()->cod_of_conduct;
        }
    }

    public function getTermsAndConfitionsLangFile()
    {

        $langFile = $this->db->query("SELECT * FROM we__lang__termsandconditions WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile->terms_and_conditions;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__termsandconditions WHERE fk_langname = ?', [$this->defaultlang])->fetch()->terms_and_conditions;
        }
    }

    public function getPrivacyLangFile()
    {

        $langFile = $this->db->query("SELECT * FROM we__lang__privacy WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile->privacy;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__privacy WHERE fk_langname = ?', [$this->defaultlang])->fetch()->privacy;
        }
    }

    public function getResetPageLangFile()
    {
        $langFile = $this->db->query("SELECT * FROM we__lang__reset WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__reset WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getHeaderLangFile()
    {
        $langFile = $this->db->query("SELECT * FROM we__lang__header WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__header WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getAskLangFile()
    {
        $langFile = $this->db->query("SELECT * FROM we__lang__ask WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__ask WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getLangContactForms()
    {
        $langFile = $this->db->query("SELECT * FROM we__lang__contactus WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__contactus WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getLangInfosBulles()
    {
        $langFile = $this->db->query("SELECT * FROM we__lang__infosbulles WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__infosbulles WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getLangGenerals($langname = false)
    {
        //get la langue general en fonction de la langue passée (paramètre langue utilisé ds les mails de notifs)
        if($langname)
        {
            $langFile = $this->db->query("SELECT * FROM we__lang__generals WHERE fk_langname = ?", [$langname])->fetch();
        }
        else{
            $langFile = $this->db->query("SELECT * FROM we__lang__generals WHERE fk_langname = ?", [$this->currentLang])->fetch();
        }

        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__generals WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getLangFilesError()
    {
        $langFile = $this->db->query("SELECT * FROM we__lang__errorfiles WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__errorfiles WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getLangFooter()
    {
        $langFile = $this->db->query("SELECT * FROM we__lang__footer WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT * FROM we__lang__footer WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }

    public function getNavProfileLangFile()
    {
        $langFile = $this->db->query("SELECT bt_nav_gamer_word_career, bt_nav_gamer_word_esport, bt_nav_gamer_word_event, bt_nav_gamer_word_games, word_Mes, bt_nav_gamer_word_equipment, word_My, bt_nav_gamer_word_timeline FROM we__lang__profile WHERE fk_langname = ?", [$this->currentLang])->fetch();
        if($langFile)
        {
            return $langFile;
        }
        else{
            return $this->db->query('SELECT bt_nav_gamer_word_career, bt_nav_gamer_word_esport, bt_nav_gamer_word_event, bt_nav_gamer_word_games, word_Mes, bt_nav_gamer_word_equipment, word_My, bt_nav_gamer_word_timeline FROM we__lang__profile WHERE fk_langname = ?', [$this->defaultlang])->fetch();
        }
    }



}