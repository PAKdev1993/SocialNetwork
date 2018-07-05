<?php

namespace app\Table\Profile\Quickinfos;

use app\App;
use core\Functions;
use core\Session\Session;
use app\Table\Profile\Career\CareerModel;

class QuickInfosModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMyQuickInfos()
    {
        $quickinfos = $this->db->query("SELECT * FROM we__quickinfos WHERE fk_iduser = ?",[
            $this->session->read('auth')->pk_iduser
        ])->fetch();
        return $quickinfos;
    }

    public function getQuickInfosFromIdUser($iduser)
    {
        $quickinfos = $this->db->query("SELECT * FROM we__quickinfos WHERE fk_iduser = ?",[
            $iduser
        ])->fetch();
        return $quickinfos;
    }

    public function createMyBasicsQuickInfos($birthdate , $nationnality, $language)
    {
        $birthdate          = Functions::secureVarSQL($birthdate);
        $nationnality       = Functions::secureVarSQL($nationnality);
        $language           = Functions::secureVarSQL($language);

        $this->db->query("INSERT INTO we__quickinfos SET fk_iduser = ?,  birthdate = ?, nationnality = ?, language = ?", [
            Session::getInstance()->read('auth')->pk_iduser,
            $birthdate,
            $nationnality,
            $language
        ]);
    }

    public function updateBasicsQuickInfos($birthdate , $nationnality, $language)
    {
        $birthdate          = Functions::secureVarSQL($birthdate);
        $nationnality       = Functions::secureVarSQL($nationnality);
        $language           = Functions::secureVarSQL($language);

        $this->db->query("UPDATE we__quickinfos SET birthdate = ?, nationnality = ?, language = ? WHERE fk_iduser = ?", [
            $birthdate,
            $nationnality,
            $language,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function updateAdvancedQuickInfos($role, $game, $curentteam, $previousteam, $platform, $action)
    {
        $role           = Functions::secureVarSQL($role);
        $game           = Functions::secureVarSQL($game);
        $curentteam     = Functions::secureVarSQL($curentteam);
        $previousteam   = Functions::secureVarSQL($previousteam);
        $action         = Functions::secureVarSQL($action);

        switch($action){
            case '01':
                //ajout d'une team current, mise a jour de l'ensemble des infos avancées
                $this->db->query("UPDATE we__quickinfos SET role = ?, game = ?, current_team = ?, previous_team = ?, platform = ? WHERE fk_iduser = ?", [
                    $role,
                    $game,
                    $curentteam,
                    $previousteam,
                    $platform,
                    Session::getInstance()->read('auth')->pk_iduser
                ]);
                break;
            case '10':
                //plus de current team
                $this->db->query("UPDATE we__quickinfos SET role = ?, game = ?, current_team = ?, previous_team = ?, platform = ? WHERE fk_iduser = ?", [
                    $role,
                    $game,
                    $curentteam,
                    $previousteam,
                    $platform,
                    Session::getInstance()->read('auth')->pk_iduser
                ]);
                break;
            case 'default':
                //simple ajout ou modif de team, update des quickinfos
                $this->db->query("UPDATE we__quickinfos SET previous_team = ? WHERE fk_iduser = ?", [
                    $previousteam,
                    Session::getInstance()->read('auth')->pk_iduser
                ]);
                break;
        }
    }

    public function updateOnDeleteCurrentTeam()
    {
        $previousTeams = $this->getMyPreviousTeamString(2);
        $this->db->query("UPDATE we__quickinfos SET role = ?, game = ?, current_team = ?, previous_team = ? WHERE fk_iduser = ?", [
            NULL,
            NULL,
            NULL,
            $previousTeams,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function updateOnDeleteTeam()
    {
        $previousTeams = $this->getMyPreviousTeamString(2);
        $this->db->query("UPDATE we__quickinfos SET previous_team = ? WHERE fk_iduser = ?", [
            $previousTeams,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function updateOnDeleteCurrentCompany()
    {
        $this->db->query("UPDATE we__quickinfos SET jobtitle = ?, location = ?, current_company = ? WHERE fk_iduser = ?", [
            NULL,
            NULL,
            NULL,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function updateAdvancedQuickInfosEmployee($company, $jobtitle, $location)
    {
        $this->db->query("UPDATE we__quickinfos SET current_company = ?, jobtitle = ?, location = ? WHERE fk_iduser = ?", [
            $company,
            $jobtitle,
            $location,
            Session::getInstance()->read('auth')->pk_iduser
        ]);
    }

    public function getMyPreviousTeamString($limit)
    {
        $model = new CareerModel();
        $previousTeamsNameObj = $model->getMyPreviousTeams($limit);
        $teamString = '';
        if(!empty($previousTeamsNameObj))
        {
            $nbPreviousTeams = sizeof($previousTeamsNameObj);
            if($nbPreviousTeams == 1)
            {
                $teamString = $previousTeamsNameObj[0]->name;
            }
            else{
                for($i = 0; $i < $nbPreviousTeams; $i++)
                {
                    if($i == $limit - 1)
                    {
                        $teamString = $teamString . $previousTeamsNameObj[$i]->name . ' ...';
                    }
                    else{
                        $teamString = $teamString . $previousTeamsNameObj[$i]->name . ', ';
                    }
                }
            }
            return $teamString;
        }
        else{
            return false;
        }

    }

    public function getPreviousTeamStringFromIdUser($iduser)
    {
        $previous_team = $this->db->query("SELECT previous_team FROM we__quickinfos WHERE fk_iduser = ?",[
            $iduser
        ])->fetch()->previous_team;
        return $previous_team;
    }

    public function getCompleteNameFromUserid($iduser)
    {
        $complete_name = $this->db->query("SELECT complete_name FROM we__quickinfos WHERE fk_iduser = ?",[
            $iduser
        ])->fetch()->complete_name;
        return $complete_name;
    }
}