<?php

namespace app\Table\Profile\Equipments;

use app\Table\AppDisplay;
use core\Session\Session;
use app\Table\Profile\Equipments\Equipment\displayEquipment;

class displayEquipments extends AppDisplay
{
    protected $pageName;

    private $gears;

    public function __construct($gears = false, $userToDisplay = false)
    {
        $this->pageName = 'Profile';
        parent::__construct($userToDisplay, $this->pageName);
        $this->gears = $gears;
    }

    public function showEdit()
    {
        return '<div class="bloc-edit-container">
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_addequipment .'</h1>
                    </div>
                    
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmenttype .'
                        </div>
                        <div class="input select comment-input">
                            <select name="addequipment-typegear" id="addequipment-typegear">
                                <option value="">-- select one --</option>
                                <option value="mouse">'. $this->gearTraduce->mouse .'</option>
                                <option value="keyboard">'. $this->gearTraduce->keyboard .'</option>
                                <option value="screen">'. $this->gearTraduce->screen .'</option>
                                <option value="headset">'. $this->gearTraduce->headset .'</option>
                                <option value="cfg">'. $this->gearTraduce->cfg .'</option>                                  
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_select_equipment .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>     
                         
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentbrand .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Logitech, Steel Series ..." contenteditable="true" id="add-equipment-equipmentbrand"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_brand .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>                       
                    
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentreference .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Kone +, RAT-7 ..." contenteditable="true" id="add-equipment-reference"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_name .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                    
                    <div class="field-container hided-field col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_config_link .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: https//mediafire.com/hGts ..." contenteditable="true" id="add-equipment-config"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_config .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                  
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newgear-ft">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
            
                </div>';
    }

    public function showEditFt()
    {
        return '<div class="bloc-edit-container-permanent">
                    <div class="title-edit-container">
                        <h1>'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_addequipment .'</h1>
                    </div>
                    
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmenttype .'
                        </div>
                        <div class="input select comment-input">
                            <select name="addequipment-typegear" id="addequipment-typegear">
                                <option value="">-- select one --</option>
                                <option value="mouse">'. $this->gearTraduce->mouse .'</option>
                                <option value="keyboard">'. $this->gearTraduce->keyboard .'</option>
                                <option value="screen">'. $this->gearTraduce->screen .'</option>
                                <option value="headset">'. $this->gearTraduce->headset .'</option>
                                <option value="cfg">'. $this->gearTraduce->cfg .'</option>                                  
                            </select>
                        </div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_select_equipment .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>     
                         
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentbrand .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Logitech, Steel Series ..." contenteditable="true" id="add-equipment-equipmentbrand"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_brand .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>                       
                    
                    <div class="field-container col-md-8">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentreference .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: Kone +, RAT-7 ..." contenteditable="true" id="add-equipment-reference"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_name .'</span></span>
                            <span class="pseudo"></span>
                        </div> 
                    </div>
                    
                    <div class="field-container hided-field col-md-12">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_config_link .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: https//mediafire.com/hGts ..." contenteditable="true" id="add-equipment-config"></div>
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_config .'
                        </div> 
                    </div>
                  
                    <div class="update-button-container">
                        <div class="loader-container loader-bt" data-elem="loader-bt">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div> 
                        <button class="share-button bt share-button-big" id="update-newgear-ft">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
            
                </div>';
    }

    public function showUserEmpty()
    {
        return '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->userToDisplay->nickname .'</span> '. $this->langFile[$this->pageName]->title_myprofile_emptycontent_equipment .'</p>
                    </div>                    
                </div>';
    }

    public function showMyEquipmentBody()
    {
        $content = '';
        foreach($this->gears as $gear)
        {
            $display = new displayEquipment($gear);
            $content = $content . $display->showMyEquipment();
        }
        return $content;
    }

    public function showUserEquipmentBody()
    {
        $content = '';
        foreach($this->gears as $gear)
        {
            $display = new displayEquipment($gear);
            $content = $content . $display->showUserEquipment();
        }
        return $content;
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if (empty($this->gears))
            {
                return $this->showMyEquipmentsft($this->showEditFt());
            }
            else {
                return $this->showMyEquipments($this->showMyEquipmentBody());
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if (empty($this->gears))
            {
                return $this->showUserEquipmentsEmpty($this->showUserEmpty());
            }
            else {
                return $this->showUserEquipments($this->showUserEquipmentBody());
            }
        }
    }
}