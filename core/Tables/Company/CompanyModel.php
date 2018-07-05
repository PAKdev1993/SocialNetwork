<?php

namespace core\Tables\Company;

use app\App;
use core\Session\Session;

class CompanyModel
{

    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function isMyCompany($companyid)
    {
        $owner =  $this->db->query("SELECT fk_iduser FROM we__employeecareercompany WHERE pk_idcompanycareer = ?",[$companyid])->fetch()->fk_iduser;
        return $owner == $this->session->read('auth')->pk_iduser;
    }
}