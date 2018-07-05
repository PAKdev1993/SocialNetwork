<?php
use inc\Autoloader;

use core\Session\Session;

use app\Table\Profile\Equipments\EquipmentModel;
use app\Table\Profile\Equipments\displayEquipments;

use core\Profile\ProfileGamer;

if(isset($_POST['typegear']) && isset($_POST['brand']) && isset($_POST['model']) && isset($_POST['configlink']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $model = new EquipmentModel();
    $GamerProfile = new ProfileGamer();

    /****************************************************************************\
     *                          CREATION D'UN EQUIPMENT                         *
    \****************************************************************************/
    if($_POST['typeaction'] == 'create')
    {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SAVE NEW EQUIPMENT
         */
        $model->saveMyEquipment($_POST['typegear'], $_POST['brand'], $_POST['model'], $_POST['configlink']);
        $equipments = $model->getMyEquipments();
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * MAJ EQUIPMENTS DISPLAYED (SECURITY)
         */
        //update displayed array for security checks
        $idsArray = $GamerProfile->getArrayEquipmentsIdFromDBobj($equipments);
        $GamerProfile->updateEquipmentsDisplayed($idsArray);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * NOTIFY MY NETWORK IF NESCESSARY
         */
        $typeaction = $GamerProfile->getProfilePart();
        $action = "action_updated_gamerprofile";
        $elemType = NULL;
        $elemid = NULL;
        $GamerProfile->notifyMyNetwork($typeaction, $action, $elemType, $elemid);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * DISPLAY
         */
        $displayEquipments = new displayEquipments($equipments);
        echo($displayEquipments->show());
        exit();
    }
    /****************************************************************************\
     *                          UPDATE D'UN EQUIPMENT                           *
    \****************************************************************************/
    if($_POST['typeaction'] == 'update')
    {
        $elemid =  Session::getInstance()->read('current-action')['idelem'];
        $valid = true;
        
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        if($elemid != $_POST['equipmentid'])
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-state')['state'] != 'owner')
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-action')['actionname'] != 'editingEquipment')
        {
            $valid = false;
        }
        if($valid)
        {
            $idelem = Session::getInstance()->read('current-action')['idelem'];
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * UPDATE EQUIPMENT
             */
            $model->updateMyEquipment($idelem, $_POST['typegear'], $_POST['brand'], $_POST['model'], $_POST['configlink']);
            $careerEquipments = $model->getMyEquipments();
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * MAJ EQUIPMENTS DISPLAYD (SECURITY)
             */
            //update displayed array for security checks
            $idsArray = $GamerProfile->getArrayEquipmentsIdFromDBobj($careerEquipments);
            $GamerProfile->updateEquipmentsDisplayed($idsArray);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $displayEquipments = new displayEquipments($careerEquipments);
            echo($displayEquipments->show());
            exit();
        }
    }
    else{
        echo('err');
        exit();
    }
}
else{
    echo('err');
    exit();
}