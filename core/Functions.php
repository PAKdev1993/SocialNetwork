<?php

namespace core;

use app\App;

class Functions
{

    //HASH Parameters
    static $HASHFilesprefixe = 'Alex&MarcFiles-';
    static $HASHPostidprefixe = 'PAKpostid-';
    static $HASHUseridprefixe = 'Theopostid-';
    static $HASHComidprefixe = 'MarcCom-';
    static $HASHtextprefixe = 'Marc&AlexInSpace-';


    static function HASH($type, $tohash, $algorithm = 'sha1')
    {
        $data = '';
        if($type == 'file'){
            $data =  self::$HASHFilesprefixe . $tohash;
        }
        if($type == 'postid'){
            $data =  self::$HASHPostidprefixe . $tohash;
        }
        if($type == 'userid'){
            $data =  self::$HASHUseridprefixe . $tohash;
        }
        if($type == 'comid'){
            $data =  self::$HASHComidprefixe . $tohash;
        }
        if($type == 'text'){
            $data =  self::$HASHtextprefixe . $tohash;
        }
        if($type == 'img'){
            $data = $tohash;
        }
        return hash($algorithm, $data, false);
    }

    static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    static function str_random($length)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    static function isEqual($tab1, $tab2)
    {
        $tab1size = count($tab1);
        $tab2size = count($tab2);
        if($tab1size != $tab2size){
            return false;
        }
        else{
            for($tab1index = 0; $tab1index < $tab1size; ++$tab1index)
            {
                if($tab1[$tab1index] != $tab2[$tab1index])
                {
                    return false;
                }
            }
        }
        return true;
    }

    static function getArrayFromObjectProperty($objects, $propety)
    {
        $arrayProperty = [];
        foreach($objects as $object)
        {
            array_push($arrayProperty, $object->$propety);
        }
        return $arrayProperty;
    }

    static function getCompleteNameFromIduser($iduser)
    {
        $db = App::getDatabase();
        $user = $db->query("SELECT firstname, nickname, lastname FROM we__User WHERE pk_iduser = ?", [
            $iduser
        ])->fetch();
        if($user)
        {
            $completeName = $user->firstname. ' "' .$user->nickname. '" ' .$user->lastname;
            return $completeName;
        }
        else{
            return false;
        }
    }

    /**
     * @param $idusers array d'ids users
     * @return array
     */
    static function getUsersNicknameInArray($idusers)
    {
        $db = App::getDatabase();

        //create user id string to pass in 'IN'
        $idstring = implode(", ",$idusers);
        $useridstring = "(". $idstring .")";
        //$useridstring =   implode(',',$idusers);
        //important pour eviter les cas de (,ID) etc
        //$useridstring =   str_replace("(,","(",$useridstring);
        //var_dump($useridstring);

        $nicknameArray = '';
        if($idusers) //#todo comprendre pourquoi besoi nde ca (error syntax sql)
        {
            $users = $db->query("SELECT nickname FROM we__User WHERE pk_iduser IN ($idstring)", [])->fetchAll();
            $nicknameArray = self::getArrayFromObjectProperty($users, 'nickname');
        }
        return $nicknameArray;
    }
    
    /**
     * @param $iduser
     * @return array
     */
    static function getUserNickname($iduser)
    {
        $db = App::getDatabase();

        $nicknameUser = $db->query("SELECT nickname FROM we__User WHERE pk_iduser = ?", [$iduser])->fetch();
        if($nicknameUser)
        {
            return $nicknameUser->nickname;
        }
    }

    static function defineTypeConv($int)
    {
        switch($int)
        {
            case 1:
                $result = 'new conv';
                break;
            case 2:
                $result = 'user to user';
                break;
             default:
                $result = 'group conv';
                break;
        }
        return $result;
    }

    static function secureVarSQL($var, $tags = false)
    {
        if($tags)
        {
            return addslashes(strip_tags($var, $tags));
        }
        else{
            return addslashes(strip_tags($var));
        }
    }
}