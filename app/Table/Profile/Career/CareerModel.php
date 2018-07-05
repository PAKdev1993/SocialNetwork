<?php

namespace app\Table\Profile\Career;

use app\App;
use core\Session\Session;
use core\Functions;

class CareerModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMyGamerCareer()
    {
        $teams = $this->db->query("SELECT * FROM we__gamercareerteam WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $teams;
    }

    public function getGamerCareerFromIduser($iduser)
    {
        $teams = $this->db->query("SELECT * FROM we__gamercareerteam WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $iduser
        ])->fetchAll();
        return $teams;
    }

    public function getMyTeamFromId($idteam)
    {
        $team = $this->db->query("SELECT * FROM we__gamercareerteam WHERE fk_iduser = ? AND pk_idteam =  ?",[
            $this->session->read('auth')->pk_iduser,
            $idteam
        ])->fetch();
        return $team;
    }

    public function saveMyTeam($name , $logo, $game, $role, $startdate, $enddate, $plateform, $description, $currentteam)
    {
        $suppressToken  = Functions::str_random(60);
        $name           = Functions::secureVarSQL($name);
        $game           = Functions::secureVarSQL($game);
        $role           = Functions::secureVarSQL($role);
        $plateform      = Functions::secureVarSQL($plateform);
        $currentteam    = Functions::secureVarSQL($currentteam);

        $this->db->query("INSERT INTO we__gamercareerteam SET fk_iduser = ?,  name = ?, logo = ?, game = ?, role = ?, startdate = ?, enddate = ?, platform = ?, description = ?, currentplayhere = ?, suppressToken = ?", [
            Session::getInstance()->read('auth')->pk_iduser,
            $name,
            $logo,
            $game,
            $role,
            $startdate,
            $enddate,
            $plateform,
            $description,
            $currentteam,
            $suppressToken
        ]);
    }

    public function updateMyTeam($pk_idteam, $name , $logo, $game, $role, $startdate, $enddate, $plateform, $description, $currentteam)
    {
        $name           = strip_tags($name);
        $game           = strip_tags($game);
        $role           = strip_tags($role);
        $plateform      = strip_tags($plateform);
        $currentteam    = strip_tags($currentteam);
        $description    = strip_tags($description);
        
        if($logo == 'didnttouch')
        {
            $this->db->query("UPDATE we__gamercareerteam SET name = ?, game = ?, role = ?, startdate = ?, enddate = ?, platform = ?, description = ?, currentplayhere = ? WHERE pk_idteam = ?", [
                $name,
                $game,
                $role,
                $startdate,
                $enddate,
                $plateform,
                $description,
                $currentteam,
                $pk_idteam
            ]);
        }
        else{
            $this->db->query("UPDATE we__gamercareerteam SET name = ?, game = ?, logo = ?, role = ?, startdate = ?, enddate = ?, platform = ?, description = ?, currentplayhere = ? WHERE pk_idteam = ?", [
                $name,
                $game,
                $logo,
                $role,
                $startdate,
                $enddate,
                $plateform,
                $description,
                $currentteam,
                $pk_idteam
            ]);
        }
    }

    public function deleteTeam($teamid)
    {
        $this->db->query("DELETE FROM we__gamercareerteam WHERE pk_idteam = ?", [$teamid]);
    }

    public function getMyEmployeeCareer()
    {
        $companies = $this->db->query("SELECT * FROM we__employeecareercompany WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $companies;
    }

    public function getUserEmployeeCareerFromIduser($iduser)
    {
        $companies = $this->db->query("SELECT * FROM we__employeecareercompany WHERE fk_iduser = ? ORDER BY enddate DESC",[
            $iduser
        ])->fetchAll();
        return $companies;
    }



    public function getMyCompanyFromId($idcompany)
    {
        $company = $this->db->query("SELECT * FROM we__employeecareercompany WHERE fk_iduser = ? AND pk_idcompanycareer =  ?",[
            $this->session->read('auth')->pk_iduser,
            $idcompany
        ])->fetch();
        return $company;
    }

    public function saveMyComapny($name , $logo, $city, $country, $jobtitle, $startdate, $enddate, $description, $curentworkhere)
    {
        $suppressToken = Functions::str_random(60);

        $name           = strip_tags($name);
        $city           = strip_tags($city);
        $country        = strip_tags($country);
        $jobtitle       = strip_tags($jobtitle);
        $description    = strip_tags($description);

        $this->db->query("INSERT INTO we__employeecareercompany SET fk_iduser = ?,  name = ?, logo = ?, city = ?, country = ?, jobtitle = ?, startdate = ?, enddate = ?, description = ?, currentlyWorkHere = ?, suppressToken = ?", [
            Session::getInstance()->read('auth')->pk_iduser,
            $name,
            $logo,
            $city,
            $country,
            $jobtitle,
            $startdate,
            $enddate,
            $description,
            $curentworkhere,
            $suppressToken
        ]);
    }

    public function updateMyCompany($pk_idcompanycareer, $name , $logo, $city, $country, $jobtitle, $startdate, $enddate, $description, $curentworkhere)
    {
        $name           = strip_tags($name);
        $city           = strip_tags($city);
        $country        = strip_tags($country);
        $jobtitle       = strip_tags($jobtitle);
        $description    = strip_tags($description);
        if($logo == 'didnttouch')
        {
            $this->db->query("UPDATE we__employeecareercompany SET name = ?, city = ?, country = ?, jobtitle = ?, startdate = ?, enddate = ?, description = ?, currentlyWorkHere = ? WHERE pk_idcompanycareer = ?", [
                $name,
                $city,
                $country,
                $jobtitle,
                $startdate,
                $enddate,
                $description,
                $curentworkhere,
                $pk_idcompanycareer
            ]);
        }
        else{
            $this->db->query("UPDATE we__employeecareercompany SET name = ?, logo = ?, city = ?, country = ?, jobtitle = ?, startdate = ?, enddate = ?, description = ?, currentlyWorkHere = ? WHERE pk_idcompanycareer = ?", [
                $name,
                $logo,
                $city,
                $country,
                $jobtitle,
                $startdate,
                $enddate,
                $description,
                $curentworkhere,
                $pk_idcompanycareer
            ]);
        }

    }

    public function deleteCompany($companyid)
    {
        $this->db->query("DELETE FROM we__employeecareercompany WHERE pk_idcompanycareer = ?", [$companyid]);
    }

    public function countMyTeam()
    {
        $nbteams = $this->db->query("SELECT COUNT(*) as nbTeams FROM we__gamercareerteam WHERE fk_iduser = ?",[
            $this->session->read('auth')->pk_iduser
        ])->fetch()->nbTeams;
        return $nbteams;
    }

    /**
     * @param $enddate //met la date de fin a l'ancienne current team et reset (met 0) les champ currentplayhere de toute les teams de l'user
     */
    public function resetCurrentTeam($enddate)
    {
        //get l'id de l'ancienne current team
        $oldCurrentTeamIdObj = $this->db->query("SELECT pk_idteam FROM we__gamercareerteam WHERE fk_iduser = ? AND currentplayhere = ?", [
            $this->session->read('auth')->pk_iduser,
            1
        ])->fetch();

        if($oldCurrentTeamIdObj)
        {
            //mettre la date de fin a l'ancienne currentTeam
            $this->db->query("UPDATE we__gamercareerteam SET enddate = ? WHERE fk_iduser = ? AND pk_idteam = ?", [
                $enddate,
                $this->session->read('auth')->pk_iduser,
                $oldCurrentTeamIdObj->pk_idteam
            ]);

            //reset les current play avant l'insertin de la current team par saveMyTeam
            $this->db->query("UPDATE we__gamercareerteam SET currentplayhere = ? WHERE fk_iduser = ?", [
                0,
                $this->session->read('auth')->pk_iduser
            ]);
        }
    }

    /**
     * @param $enddate //met la date de fin a l'ancienne current company et reset (met 0) les champ currentworkhere de toute les company de l'user
     */
    public function resetCurrentCompany($enddate)
    {
        //get l'id de l'ancienne current company
        $oldCurrentCompIdObj = $this->db->query("SELECT pk_idcompanycareer FROM we__employeecareercompany WHERE fk_iduser = ? AND currentlyWorkHere = ?", [
            $this->session->read('auth')->pk_iduser,
            1
        ])->fetch();

        if($oldCurrentCompIdObj)
        {
            //mettre la date de fin a l'ancienne current company
            $this->db->query("UPDATE we__employeecareercompany SET enddate = ? WHERE fk_iduser = ? AND pk_idcompanycareer = ?", [
                $enddate,
                $this->session->read('auth')->pk_iduser,
                $oldCurrentCompIdObj->pk_idcompanycareer
            ]);

            //reset les current work avant l'insertin de la current team par saveMyTeam
            $this->db->query("UPDATE we__employeecareercompany SET currentlyWorkHere = ? WHERE fk_iduser = ?", [
                0,
                $this->session->read('auth')->pk_iduser
            ]);
        }
    }

    //fonction utilisée par quickinfosModel
    public function getMyPreviousTeams($limit)
    {
        $teams = $this->db->query("SELECT name FROM we__gamercareerteam WHERE fk_iduser = ? AND currentplayhere = ? ORDER BY enddate DESC LIMIT 0,$limit",[
            $this->session->read('auth')->pk_iduser,
            0
        ])->fetchAll();
        return $teams;
    }

    //fonction utilisée par quickinfosModel get location string current job
    public function getMyLocation()
    {
        $location = $this->db->query("SELECT city, country FROM we__employeecareercompany WHERE fk_iduser = ? AND currentlyWorkHere = ?",[
            $this->session->read('auth')->pk_iduser,
            1
        ])->fetchAll();
        return $location;
    }

    public function getTeamSuppressTokenFromId($teamid)
    {
        return $this->db->query("SELECT suppressToken FROM we__gamercareerteam WHERE fk_iduser = ? AND pk_idteam = ?",[
            $this->session->read('auth')->pk_iduser,
            $teamid
        ])->fetch()->suppressToken;
    }

    public function getCompanySuppressTokenFromId($companyid)
    {
        return $this->db->query("SELECT suppressToken FROM we__employeecareercompany WHERE fk_iduser = ? AND pk_idcompanycareer = ?",[
            $this->session->read('auth')->pk_iduser,
            $companyid
        ])->fetch()->suppressToken;
    }

    public function isCurrentCompany($companyid)
    {
        return $this->db->query("SELECT currentlyWorkHere FROM we__employeecareercompany WHERE pk_idcompanycareer = ?",[
            $companyid
        ])->fetch()->currentlyWorkHere;
    }

    public function isCurrentTeam($teamid)
    {
        return $this->db->query("SELECT currentplayhere FROM we__gamercareerteam WHERE pk_idteam = ?",[
            $teamid
        ])->fetch()->currentplayhere;
    }
}