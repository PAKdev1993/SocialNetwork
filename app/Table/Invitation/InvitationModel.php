<?php
/**
 * Created by PhpStorm.
 * User: PAK
 * Date: 08/09/2016
 * Time: 20:55
 */

namespace app\Table\Invitation;


use app\App;
use core\Session\Session;

class InvitationModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function saveInvitation($email)
    {
        $this->db->query("INSERT INTO we__Invitations SET fk_iduser = ?, slug = ?, email = ?, date = ?",[
            $this->session->read('auth')->pk_iduser,
            $this->session->read('auth')->slug,
            $email,
            date("Y-m-d H:i:s")
        ]);
    }
}