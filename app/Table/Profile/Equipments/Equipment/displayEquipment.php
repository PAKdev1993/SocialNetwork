<?php

namespace app\Table\Profile\Equipments\Equipment;

use app\Table\AppDisplayElem;
use app\Table\Profile\Equipments\displayEquipments;
use core\Session\Session;

class displayEquipment extends displayEquipments
{
    //GEAR ATTRIBUTES
    private $id;
    private $typegear;
    private $brand;
    private $model;
    private $configlink;

    private $gearTraduced;

    public function __construct($equipment)
    {
        parent::__construct();
        $this->id =             (empty($equipment->pk_idgear))      ? '' : $equipment->pk_idgear;
        $this->typegear =       (empty($equipment->typegear))       ? '' : $equipment->typegear;
        $this->brand =          (empty($equipment->brand))          ? '' : $equipment->brand;
        $this->model =          (empty($equipment->model))          ? '' : $equipment->model;
        $this->configlink =     (empty($equipment->configlink))     ? '' : $equipment->configlink;

        //TRANSLATE GEAR
        $gear = $this->typegear;
        $this->gearTraduced = $this->gearTraduce->$gear;
    }

    public function showEdit()
    {
        switch($this->typegear) {
            case 'cfg':
                $content = '<div class="bloc-edit-container">
                                <div class="edit-ico-container">
                                    <div class="close-edit close-edit-profile-bloc-elem"></div>
                                </div>
                                <div class="title-edit-container">
                                    <h1>'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_editequipment .' ' . $this->typegear . '</h1>
                                </div>
                                
                                <div class="field-container col-md-6">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmenttype .'
                                    </div>
                                    <div class="input select comment-input">
                                        <select name="edit-equipment-type" id="edit-equipment-type">
                                            <option value="' . strtolower($this->typegear) . '">' . $this->typegear . '</option>
                                            <option value="mouse">' . $this->gearTraduce->mouse . '</option>
                                            <option value="keyboard">' . $this->gearTraduce->keyboard . '</option>
                                            <option value="screen">' . $this->gearTraduce->screen . '</option>
                                            <option value="headset">' . $this->gearTraduce->headset . '</option>
                                            <option value="cfg">' . $this->gearTraduce->cfg . '</option>                                  
                                        </select>
                                    </div>
                                </div>      
                                     
                                <div class="field-container col-md-6 hided-field">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentbrand .'
                                    </div>
                                    <div class="input min-large comment-input editable-content" placeholder="ex: Logitech, Steel Series ..." contenteditable="true" id="edit-equipment-brand">' . $this->brand . '</div>
                                    <div class="bulle-error">
                                        <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_brand .'</span></span>
                                        <span class="pseudo"></span>
                                    </div> 
                                </div>                                             
                                
                                <div class="field-container col-md-8 hided-field">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentreference .'
                                    </div>
                                    <div class="input min-large comment-input editable-content" placeholder="ex: Kone +, RAT-7 ..." contenteditable="true" id="edit-equipment-reference">' . $this->model . '</div>
                                    <div class="bulle-error">
                                        <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_name .'</span></span>
                                        <span class="pseudo"></span>
                                    </div> 
                                </div>
                                
                                <div class="field-container col-md-12">
                                    <div class="label-field">
                                       '. $this->langFile[$this->pageName]->title_field_config_link .'
                                    </div>
                                    <div class="input min-large comment-input editable-content" placeholder="ex: https//mediafire.com/hGts ..." contenteditable="true" id="edit-equipment-config">' . $this->configlink . '</div>
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
                                    <button class="share-button bt share-button-big" id="update-equipment">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                                </div>
                        
                            </div>';
                break;
            default:
                $content = '<div class="bloc-edit-container">
                                <div class="edit-ico-container">
                                    <div class="close-edit close-edit-profile-bloc-elem"></div>
                                </div>
                                <div class="title-edit-container">
                                    <h1>'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_editequipment .' ' . $this->typegear . '</h1>
                                </div>
                                
                                <div class="field-container col-md-6">
                                    <div class="label-field">
                                       '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmenttype .'
                                    </div>
                                    <div class="input select comment-input">
                                        <select name="edit-equipment-type" id="edit-equipment-type">
                                            <option value="' . strtolower($this->typegear) . '">' . $this->typegear . '</option>
                                            <option value="mouse">' . $this->gearTraduce->mouse . '</option>
                                            <option value="keyboard">' . $this->gearTraduce->keyboard . '</option>
                                            <option value="screen">' . $this->gearTraduce->screen . '</option>
                                            <option value="headset">' . $this->gearTraduce->headset . '</option>
                                            <option value="cfg">' . $this->gearTraduce->cfg . '</option>                                  
                                        </select>
                                    </div>
                                </div>      
                                     
                                <div class="field-container col-md-6">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentbrand .'
                                    </div>
                                    <div class="input min-large comment-input editable-content" placeholder="ex: Logitech, Steel Series ..." contenteditable="true" id="edit-equipment-brand">' . $this->brand . '</div>
                                    <div class="bulle-error">
                                        <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_brand .'</span></span>
                                        <span class="pseudo"></span>
                                    </div> 
                                </div>                                             
                                
                                <div class="field-container col-md-8">
                                    <div class="label-field">
                                        '. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_add_equipmentreference .'
                                    </div>
                                    <div class="input min-large comment-input editable-content" placeholder="ex: Kone +, RAT-7 ..." contenteditable="true" id="edit-equipment-reference">' . $this->model . '</div>
                                    <div class="bulle-error">
                                        <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_gamer_equipment_name .'</span></span>
                                        <span class="pseudo"></span>
                                    </div> 
                                </div>
                                
                                <div class="field-container hided-field col-md-12">
                                    <div class="label-field">
                                       '. $this->langFile[$this->pageName]->title_field_config_link .'
                                    </div>
                                    <div class="input min-large comment-input editable-content" placeholder="ex: https//mediafire.com/hGts ..." contenteditable="true" id="edit-equipment-config">' . $this->configlink . '</div>
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
                                    <button class="share-button bt share-button-big" id="update-equipment">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                                </div>
                        
                            </div>';
                break;
        }
        return $content;
    }

    public function showMyEquipment() //#todo OPTIMISABLE, refactoriser
    {
        //test du type de materiel
        switch($this->typegear){
            case 'mouse':
                $img = '<img src="public/img/icon/mouse_white.png" alt="WorldEsport mouse logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span> '. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span> '. $this->model .'</p>
                        </div>';
                break;
            case 'keyboard':
                $img = '<img src="public/img/icon/keyboard_white.png" alt="WorldEsport keyboard logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span> '. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span> '. $this->model .'</p>
                        </div>';
                break;
            case 'screen':
                $img = '<img src="public/img/icon/screen_white.png" alt="WorldEsport screen logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span> '. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span> '. $this->model .'</p>
                        </div>';
                break;
            case 'headset':
                $img = '<img src="public/img/icon/headset_white.png" alt="WorldEsport headset logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span> '. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span> '. $this->model .'</p>
                        </div>';
                break;
            case 'cfg':
                $img = '<img src="public/img/icon/cfg_white.png" alt="WorldEsport config logo">';
                $body = '<div class="info-line info-line-cfg">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentlink .'</span> <a href="'. $this->configlink .'" target="_blank">'. $this->configlink .'</a></p>
                        </div>';
                break;
            default:
                $img = '';
                $body = '';
                break;
        }

        $content = '<div class="profile-elem profil-equipement-container col-md-12" data-elem="'. $this->id .'"> 
                        <div class="profile-aside-container">
                            <div class="loader-container loader-elem-bloc loader-profile-elem">
                                <div class="loader-double-container">
                                    <span class="loader loader-double">
                                    </span>
                                </div>
                            </div>
                            <div class="edit-container">
                                <div class="edit-ico-container">
                                    <div class="edit-gear edit-profile-bloc-elem ico-gear"></div>                                
                                </div>
                                <div class="edit-options">
                               
                                </div>
                            </div>  
                            <div class="group-container col-md-12">
                                <div class="group-left-part col-md-2">
                                    <div class="text-ico">
                                        <p>'. $this->gearTraduced .'</p>
                                    </div>
                                    <div class="icon-container">
                                        '. $img .'
                                    </div>
                                </div>
                                <div class="group-right-part col-md-10">
                                    <div class="infos-container col-md-12">
                                        '. $body .'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

        return $content;
    }

    public function showUserEquipment()
    {
        //test du type de materiel
        switch($this->typegear){
            case 'mouse':
                $img = '<img src="public/img/icon/mouse_white.png" alt="WorldEsport mouse logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span> '. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span> '. $this->model .'</p>
                        </div>';
                break;
            case 'keyboard':
                $img = '<img src="public/img/icon/keyboard_white.png" alt="WorldEsport keyboard logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span> '. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span> '. $this->model .'</p>
                        </div>';
                break;
            case 'screen':
                $img = '<img src="public/img/icon/screen_white.png" alt="WorldEsport screen logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span>'. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span>'. $this->model .'</p>
                        </div>';
                break;
            case 'headset':
                $img = '<img src="public/img/icon/headset_white.png" alt="WorldEsport headset logo">';
                $body = '<div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentbrand .'</span> '. $this->brand .'</p>
                        </div>
                        <div class="info-line">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentreference .'</span> '. $this->model .'</p>
                        </div>';
                break;
            case 'cfg':
                $img = '<img src="public/img/icon/cfg_white.png" alt="WorldEsport config logo">';
                $body = '<div class="info-line info-line-cfg">
                            <p class="info"><span class="bold">'. $this->langFile[$this->pageName]->title_field_myprofile_gamer_myequipment_equipmentlink .'</span> <a href="'. $this->configlink .'" target="_blank">'. $this->configlink .'</a></p>
                        </div>';
                break;
            default:
                $img = '';
                $body = '';
                break;
        }

        $content = '<div class="profile-elem profil-equipement-container col-md-12" data-elem="'. $this->id .'"> 
                        <div class="profile-aside-container">                            
                            <div class="group-container col-md-12">
                                <div class="group-left-part col-md-2">
                                    <div class="text-ico">
                                        <p>'. $this->gearTraduced .'</p>
                                    </div>
                                    <div class="icon-container">
                                        '. $img .'
                                    </div>
                                </div>
                                <div class="group-right-part col-md-10">
                                    <div class="infos-container col-md-12">
                                        '. $body .'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

        return $content;
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            return $this->showMyEquipment();
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            return $this->showUserEquipment();
        }
    }
}