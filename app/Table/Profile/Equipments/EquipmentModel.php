<?php

namespace app\Table\Profile\Equipments;

use app\App;
use core\Session\Session;
use core\Functions;

class EquipmentModel
{
    private $db;
    private $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();
    }

    public function getMyEquipments()
    {
        $equipments = $this->db->query("SELECT * FROM we__gamercareergear WHERE fk_iduser = ? ORDER BY typegear",[
            $this->session->read('auth')->pk_iduser
        ])->fetchAll();
        return $equipments;
    }

    public function getUserEquipmentsFromUserId($userid)
    {
        $equipments = $this->db->query("SELECT * FROM we__gamercareergear WHERE fk_iduser = ? ORDER BY typegear",[
            $userid
        ])->fetchAll();
        return $equipments;
    }

    public function getMyEquipmentFromId($equipmentid)
    {
        $equipment = $this->db->query("SELECT * FROM we__gamercareergear WHERE fk_iduser = ? AND pk_idgear =  ?",[
            $this->session->read('auth')->pk_iduser,
            $equipmentid
        ])->fetch();
        return $equipment;
    }

    public function saveMyEquipment($typegear , $brand, $model, $configlink)
    {
        $suppressToken = Functions::str_random(60);

        $typegear   = Functions::secureVarSQL($typegear);
        $brand      = Functions::secureVarSQL($brand);
        $model      = Functions::secureVarSQL($model);
        $configlink = Functions::secureVarSQL($configlink, '<a>');

        $this->db->query("INSERT INTO we__gamercareergear SET fk_iduser = ?, typegear = ?, brand = ?, model = ?, configlink = ?, suppressToken = ?", [
            Session::getInstance()->read('auth')->pk_iduser,
            $typegear,
            $brand,
            $model,
            $configlink,
            $suppressToken
        ]);
    }

    public function updateMyEquipment($pk_idgear, $typegear , $brand, $model, $configlink)
    {
        $typegear   = Functions::secureVarSQL($typegear);
        $brand      = Functions::secureVarSQL($brand);
        $model      = Functions::secureVarSQL($model);
        $configlink = Functions::secureVarSQL($configlink, '<a>');

        $this->db->query("UPDATE we__gamercareergear SET typegear = ?, brand = ?, model = ?, configlink = ? WHERE pk_idgear = ?", [
            $typegear,
            $brand,
            $model,
            $configlink,
            $pk_idgear
        ]);

    }

    public function deleteEquipment($equipmentid)
    {
        $this->db->query("DELETE FROM we__gamercareergear WHERE pk_idgear = ?", [$equipmentid]);
    }

    public function getEquipmentSuppressTokenFromId($equipmentid)
    {
        return $this->db->query("SELECT suppressToken FROM we__gamercareergear WHERE fk_iduser = ? AND pk_idgear = ?",[
            $this->session->read('auth')->pk_iduser,
            $equipmentid
        ])->fetch()->suppressToken;
    }
}