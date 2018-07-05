<?php

namespace app\Table\Profile\Quickinfos;

use app\Table\UserModel\UserModel;
use core\Session\Session;
use app\Table\AppDisplay;

class displayQuickinfos extends AppDisplay
{
    private $id;
    private $fk_iduser;
    private $complete_name;
    private $nickname;
    private $birthdate;
    private $nationnality = ' ';
    private $language;
    private $current_team;
    private $role;
    private $game;
    private $previous_team;
    private $current_company;
    private $jobtitle;
    private $location;
    private $slug;
    private $sponsorToken;

    private $dayBirth = 'dd';
    private $monthBirth = 'mm';
    private $monthValue = '';
    private $yearBirth = 'yyyy';
    private $age;

    private $todisplay;

    protected $pageName;

    public function __construct($quickinfo = false, $todisplay = false)
    {
        $this->pageName = 'Profile';
        parent::__construct(false, $this->pageName);
        if($quickinfo != false)
        {
            $this->id =             (empty($quickinfo->pk_quickInfoentry))  ? '' : $quickinfo->pk_quickInfoentry;
            $this->fk_iduser =      (empty($quickinfo->fk_iduser))          ? '' : $quickinfo->fk_iduser;
            $this->complete_name =  (empty($quickinfo->complete_name))      ? '' : $quickinfo->complete_name;
            $this->birthdate =      (empty($quickinfo->birthdate))          ? '' : $quickinfo->birthdate;
            $this->nationnality =   (empty($quickinfo->nationnality))       ? '' : $quickinfo->nationnality;
            $this->language =       (empty($quickinfo->language))           ? '' : $quickinfo->language;
            $this->current_team =   (empty($quickinfo->current_team))       ? '' : $quickinfo->current_team;
            $this->previous_team =  (empty($quickinfo->previous_team))      ? '' : $quickinfo->previous_team;
            $this->role =           (empty($quickinfo->role))               ? '' : $quickinfo->role;
            $this->game =           (empty($quickinfo->game))               ? '' : $quickinfo->game;
            $this->current_company= (empty($quickinfo->current_company))    ? '' : $quickinfo->current_company;
            $this->jobtitle =       (empty($quickinfo->jobtitle))           ? '' : $quickinfo->jobtitle;
            $this->location =       (empty($quickinfo->location))           ? '' : $quickinfo->location;
        }

        //extract nickname
        $tmp = explode('"', $this->complete_name);
        $this->nickname = $tmp[1];

        //get slug
        $model = new UserModel();
        $this->slug = $model->getSlugFromId($this->fk_iduser);

        //get invitationoken
        $this->sponsorToken = $model->getSponsorTokenFromId($this->fk_iduser);

        if($this->birthdate)
        {
            //calculate age
            $from = new \DateTime($this->birthdate);
            $to   = new \DateTime('today');
            $this->age = $from->diff($to)->y;

            //transform date to insert in form
            $dateFormated = date("d-m-Y", strtotime($this->birthdate));
            $pieces = explode('-', $dateFormated);
            $this->dayBirth = $pieces[0];
            $this->monthValue = $pieces[1];
            $this->yearBirth = $pieces[2];

            $corresMonth = (int) ltrim($pieces[1], '0');
            $monthName = $this->montharray[(int)$corresMonth];
            $this->monthBirth = $this->monthTraduce->$monthName;
        }

        $this->todisplay = !$todisplay ? 'gamer' : $todisplay;
    }
    
    public function showEdit()
    {
        return ' <div class="bloc-edit-container">
                    <div class="loader-container loader-profile-elem" id="loader-quickinfos-edit">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="field-container col-md-6">                              
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_birthdate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="bday" id="birth-day">
                                <option value="'. $this->dayBirth .'">'. $this->dayBirth .'</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="bmonth" id="birth-month">
                                <option value="'. $this->monthValue .'">'. $this->monthBirth .'</option>
                                <option value="01">'. $this->monthTraduce->January .'</option>
                                <option value="02">'. $this->monthTraduce->February .'</option>
                                <option value="03">'. $this->monthTraduce->March .'</option>
                                <option value="04">'. $this->monthTraduce->April .'</option>
                                <option value="05">'. $this->monthTraduce->May .'</option>
                                <option value="06">'. $this->monthTraduce->June .'</option>
                                <option value="07">'. $this->monthTraduce->July .'</option>
                                <option value="08">'. $this->monthTraduce->August .'</option>
                                <option value="09">'. $this->monthTraduce->September .'</option>
                                <option value="10">'. $this->monthTraduce->October .'</option>
                                <option value="11">'. $this->monthTraduce->November .'</option>
                                <option value="12">'. $this->monthTraduce->December .'</option>
                            </select>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="byear" id="birth-year">
                                <option value="'. $this->yearBirth .'">'. $this->yearBirth .'</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>  
                        <div class="bulle-error">
                            <span class="message message-top"><span>Put valid date please :)</span></span>
                            <span class="pseudo"></span>
                        </div>                             
                    </div>
                                               
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_nationality .'
                        </div>
                        <div class="select input comment-input">
                            <select name="nationality" id="nationnality">
                                <option value="'. $this->nationnality .'">'. $this->nationnality .'</option>
                                <option value="afghan">Afghan</option>
                                <option value="albanian">Albanian</option>
                                <option value="algerian">Algerian</option>
                                <option value="american">American</option>
                                <option value="andorran">Andorran</option>
                                <option value="angolan">Angolan</option>
                                <option value="antiguans">Antiguans</option>
                                <option value="argentinean">Argentinean</option>
                                <option value="armenian">Armenian</option>
                                <option value="australian">Australian</option>
                                <option value="austrian">Austrian</option>
                                <option value="azerbaijani">Azerbaijani</option>
                                <option value="bahamian">Bahamian</option>
                                <option value="bahraini">Bahraini</option>
                                <option value="bangladeshi">Bangladeshi</option>
                                <option value="barbadian">Barbadian</option>
                                <option value="barbudans">Barbudans</option>
                                <option value="batswana">Batswana</option>
                                <option value="belarusian">Belarusian</option>
                                <option value="belgian">Belgian</option>
                                <option value="belizean">Belizean</option>
                                <option value="beninese">Beninese</option>
                                <option value="bhutanese">Bhutanese</option>
                                <option value="bolivian">Bolivian</option>
                                <option value="bosnian">Bosnian</option>
                                <option value="brazilian">Brazilian</option>
                                <option value="british">British</option>
                                <option value="bruneian">Bruneian</option>
                                <option value="bulgarian">Bulgarian</option>
                                <option value="burkinabe">Burkinabe</option>
                                <option value="burmese">Burmese</option>
                                <option value="burundian">Burundian</option>
                                <option value="cambodian">Cambodian</option>
                                <option value="cameroonian">Cameroonian</option>
                                <option value="canadian">Canadian</option>
                                <option value="cape verdean">Cape Verdean</option>
                                <option value="central african">Central African</option>
                                <option value="chadian">Chadian</option>
                                <option value="chilean">Chilean</option>
                                <option value="chinese">Chinese</option>
                                <option value="colombian">Colombian</option>
                                <option value="comoran">Comoran</option>
                                <option value="congolese">Congolese</option>
                                <option value="costa rican">Costa Rican</option>
                                <option value="croatian">Croatian</option>
                                <option value="cuban">Cuban</option>
                                <option value="cypriot">Cypriot</option>
                                <option value="czech">Czech</option>
                                <option value="danish">Danish</option>
                                <option value="djibouti">Djibouti</option>
                                <option value="dominican">Dominican</option>
                                <option value="dutch">Dutch</option>
                                <option value="east timorese">East Timorese</option>
                                <option value="ecuadorean">Ecuadorean</option>
                                <option value="egyptian">Egyptian</option>
                                <option value="emirian">Emirian</option>
                                <option value="equatorial guinean">Equatorial Guinean</option>
                                <option value="eritrean">Eritrean</option>
                                <option value="estonian">Estonian</option>
                                <option value="ethiopian">Ethiopian</option>
                                <option value="fijian">Fijian</option>
                                <option value="filipino">Filipino</option>
                                <option value="finnish">Finnish</option>
                                <option value="french">French</option>
                                <option value="gabonese">Gabonese</option>
                                <option value="gambian">Gambian</option>
                                <option value="georgian">Georgian</option>
                                <option value="german">German</option>
                                <option value="ghanaian">Ghanaian</option>
                                <option value="greek">Greek</option>
                                <option value="grenadian">Grenadian</option>
                                <option value="guatemalan">Guatemalan</option>
                                <option value="guinea-bissauan">Guinea-Bissauan</option>
                                <option value="guinean">Guinean</option>
                                <option value="guyanese">Guyanese</option>
                                <option value="haitian">Haitian</option>
                                <option value="herzegovinian">Herzegovinian</option>
                                <option value="honduran">Honduran</option>
                                <option value="hungarian">Hungarian</option>
                                <option value="icelander">Icelander</option>
                                <option value="indian">Indian</option>
                                <option value="indonesian">Indonesian</option>
                                <option value="iranian">Iranian</option>
                                <option value="iraqi">Iraqi</option>
                                <option value="irish">Irish</option>
                                <option value="israeli">Israeli</option>
                                <option value="italian">Italian</option>
                                <option value="ivorian">Ivorian</option>
                                <option value="jamaican">Jamaican</option>
                                <option value="japanese">Japanese</option>
                                <option value="jordanian">Jordanian</option>
                                <option value="kazakhstani">Kazakhstani</option>
                                <option value="kenyan">Kenyan</option>
                                <option value="kittian and nevisian">Kittian and Nevisian</option>
                                <option value="kuwaiti">Kuwaiti</option>
                                <option value="kyrgyz">Kyrgyz</option>
                                <option value="laotian">Laotian</option>
                                <option value="latvian">Latvian</option>
                                <option value="lebanese">Lebanese</option>
                                <option value="liberian">Liberian</option>
                                <option value="libyan">Libyan</option>
                                <option value="liechtensteiner">Liechtensteiner</option>
                                <option value="lithuanian">Lithuanian</option>
                                <option value="luxembourger">Luxembourger</option>
                                <option value="macedonian">Macedonian</option>
                                <option value="malagasy">Malagasy</option>
                                <option value="malawian">Malawian</option>
                                <option value="malaysian">Malaysian</option>
                                <option value="maldivan">Maldivan</option>
                                <option value="malian">Malian</option>
                                <option value="maltese">Maltese</option>
                                <option value="marshallese">Marshallese</option>
                                <option value="mauritanian">Mauritanian</option>
                                <option value="mauritian">Mauritian</option>
                                <option value="mexican">Mexican</option>
                                <option value="micronesian">Micronesian</option>
                                <option value="moldovan">Moldovan</option>
                                <option value="monacan">Monacan</option>
                                <option value="mongolian">Mongolian</option>
                                <option value="moroccan">Moroccan</option>
                                <option value="mosotho">Mosotho</option>
                                <option value="motswana">Motswana</option>
                                <option value="mozambican">Mozambican</option>
                                <option value="namibian">Namibian</option>
                                <option value="nauruan">Nauruan</option>
                                <option value="nepalese">Nepalese</option>
                                <option value="new zealander">New Zealander</option>
                                <option value="ni-vanuatu">Ni-Vanuatu</option>
                                <option value="nicaraguan">Nicaraguan</option>
                                <option value="nigerien">Nigerien</option>
                                <option value="north korean">North Korean</option>
                                <option value="northern irish">Northern Irish</option>
                                <option value="norwegian">Norwegian</option>
                                <option value="omani">Omani</option>
                                <option value="pakistani">Pakistani</option>
                                <option value="palauan">Palauan</option>
                                <option value="panamanian">Panamanian</option>
                                <option value="papua new guinean">Papua New Guinean</option>
                                <option value="paraguayan">Paraguayan</option>
                                <option value="peruvian">Peruvian</option>
                                <option value="polish">Polish</option>
                                <option value="portuguese">Portuguese</option>
                                <option value="qatari">Qatari</option>
                                <option value="romanian">Romanian</option>
                                <option value="russian">Russian</option>
                                <option value="rwandan">Rwandan</option>
                                <option value="saint lucian">Saint Lucian</option>
                                <option value="salvadoran">Salvadoran</option>
                                <option value="samoan">Samoan</option>
                                <option value="san marinese">San Marinese</option>
                                <option value="sao tomean">Sao Tomean</option>
                                <option value="saudi">Saudi</option>
                                <option value="scottish">Scottish</option>
                                <option value="senegalese">Senegalese</option>
                                <option value="serbian">Serbian</option>
                                <option value="seychellois">Seychellois</option>
                                <option value="sierra leonean">Sierra Leonean</option>
                                <option value="singaporean">Singaporean</option>
                                <option value="slovakian">Slovakian</option>
                                <option value="slovenian">Slovenian</option>
                                <option value="solomon islander">Solomon Islander</option>
                                <option value="somali">Somali</option>
                                <option value="south african">South African</option>
                                <option value="south korean">South Korean</option>
                                <option value="spanish">Spanish</option>
                                <option value="sri lankan">Sri Lankan</option>
                                <option value="sudanese">Sudanese</option>
                                <option value="surinamer">Surinamer</option>
                                <option value="swazi">Swazi</option>
                                <option value="swedish">Swedish</option>
                                <option value="swiss">Swiss</option>
                                <option value="syrian">Syrian</option>
                                <option value="taiwanese">Taiwanese</option>
                                <option value="tajik">Tajik</option>
                                <option value="tanzanian">Tanzanian</option>
                                <option value="thai">Thai</option>
                                <option value="togolese">Togolese</option>
                                <option value="tongan">Tongan</option>
                                <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                                <option value="tunisian">Tunisian</option>
                                <option value="turkish">Turkish</option>
                                <option value="tuvaluan">Tuvaluan</option>
                                <option value="ugandan">Ugandan</option>
                                <option value="ukrainian">Ukrainian</option>
                                <option value="uruguayan">Uruguayan</option>
                                <option value="uzbekistani">Uzbekistani</option>
                                <option value="venezuelan">Venezuelan</option>
                                <option value="vietnamese">Vietnamese</option>
                                <option value="welsh">Welsh</option>
                                <option value="yemenite">Yemenite</option>
                                <option value="zambian">Zambian</option>
                                <option value="zimbabwean">Zimbabwean</option>
                            </select>
                        </div>
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_quickinformation_nationality .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                    
                    <div class="field-container col-md-7">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_languages .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: French, DE" id="language" contenteditable="true" spellcheck="false">'. $this->language .'</div>
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_quickinformation_languages .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                    
                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-quickinfos">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showEditFt()
    {
        return ' <div class="bloc-edit-container-permanent">
                    <div class="loader-container loader-profile-elem" id="loader-quickinfos-edit">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    <div class="field-container col-md-6">                              
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_birthdate .'
                        </div>
                        <div class="select-date day input comment-input col-md-2">
                            <select name="bday" id="birth-day">
                                <option value="'. $this->dayBirth .'">'. $this->dayBirth .'</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>                              
                        <div class="select-date month input comment-input col-md-2">
                            <select name="bmonth" id="birth-month">
                                <option value="'. $this->monthValue .'">'. $this->monthBirth .'</option>
                                <option value="01">'. $this->monthTraduce->January .'</option>
                                <option value="02">'. $this->monthTraduce->February .'</option>
                                <option value="03">'. $this->monthTraduce->March .'</option>
                                <option value="04">'. $this->monthTraduce->April .'</option>
                                <option value="05">'. $this->monthTraduce->May .'</option>
                                <option value="06">'. $this->monthTraduce->June .'</option>
                                <option value="07">'. $this->monthTraduce->July .'</option>
                                <option value="08">'. $this->monthTraduce->August .'</option>
                                <option value="09">'. $this->monthTraduce->September .'</option>
                                <option value="10">'. $this->monthTraduce->October .'</option>
                                <option value="11">'. $this->monthTraduce->November .'</option>
                                <option value="12">'. $this->monthTraduce->December .'</option>
                            </select>
                        </div>
                        <div class="select-date year input comment-input col-md-2">
                            <select name="byear" id="birth-year">
                                <option value="'. $this->yearBirth .'">'. $this->yearBirth .'</option>
                                '. $this->formElems->selectYearValues() .'
                            </select>
                        </div>  
                        <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_entervaliddate .'</span></span>
                            <span class="pseudo"></span>
                        </div>                             
                    </div>
                                               
                    <div class="field-container col-md-6">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_nationality .'
                        </div>
                        <div class="select input comment-input">
                            <select name="nationality" id="nationnality">
                                <option value="'. $this->nationnality .'">'. $this->nationnality .'</option>
                                <option value="afghan">Afghan</option>
                                <option value="albanian">Albanian</option>
                                <option value="algerian">Algerian</option>
                                <option value="american">American</option>
                                <option value="andorran">Andorran</option>
                                <option value="angolan">Angolan</option>
                                <option value="antiguans">Antiguans</option>
                                <option value="argentinean">Argentinean</option>
                                <option value="armenian">Armenian</option>
                                <option value="australian">Australian</option>
                                <option value="austrian">Austrian</option>
                                <option value="azerbaijani">Azerbaijani</option>
                                <option value="bahamian">Bahamian</option>
                                <option value="bahraini">Bahraini</option>
                                <option value="bangladeshi">Bangladeshi</option>
                                <option value="barbadian">Barbadian</option>
                                <option value="barbudans">Barbudans</option>
                                <option value="batswana">Batswana</option>
                                <option value="belarusian">Belarusian</option>
                                <option value="belgian">Belgian</option>
                                <option value="belizean">Belizean</option>
                                <option value="beninese">Beninese</option>
                                <option value="bhutanese">Bhutanese</option>
                                <option value="bolivian">Bolivian</option>
                                <option value="bosnian">Bosnian</option>
                                <option value="brazilian">Brazilian</option>
                                <option value="british">British</option>
                                <option value="bruneian">Bruneian</option>
                                <option value="bulgarian">Bulgarian</option>
                                <option value="burkinabe">Burkinabe</option>
                                <option value="burmese">Burmese</option>
                                <option value="burundian">Burundian</option>
                                <option value="cambodian">Cambodian</option>
                                <option value="cameroonian">Cameroonian</option>
                                <option value="canadian">Canadian</option>
                                <option value="cape verdean">Cape Verdean</option>
                                <option value="central african">Central African</option>
                                <option value="chadian">Chadian</option>
                                <option value="chilean">Chilean</option>
                                <option value="chinese">Chinese</option>
                                <option value="colombian">Colombian</option>
                                <option value="comoran">Comoran</option>
                                <option value="congolese">Congolese</option>
                                <option value="costa rican">Costa Rican</option>
                                <option value="croatian">Croatian</option>
                                <option value="cuban">Cuban</option>
                                <option value="cypriot">Cypriot</option>
                                <option value="czech">Czech</option>
                                <option value="danish">Danish</option>
                                <option value="djibouti">Djibouti</option>
                                <option value="dominican">Dominican</option>
                                <option value="dutch">Dutch</option>
                                <option value="east timorese">East Timorese</option>
                                <option value="ecuadorean">Ecuadorean</option>
                                <option value="egyptian">Egyptian</option>
                                <option value="emirian">Emirian</option>
                                <option value="equatorial guinean">Equatorial Guinean</option>
                                <option value="eritrean">Eritrean</option>
                                <option value="estonian">Estonian</option>
                                <option value="ethiopian">Ethiopian</option>
                                <option value="fijian">Fijian</option>
                                <option value="filipino">Filipino</option>
                                <option value="finnish">Finnish</option>
                                <option value="french">French</option>
                                <option value="gabonese">Gabonese</option>
                                <option value="gambian">Gambian</option>
                                <option value="georgian">Georgian</option>
                                <option value="german">German</option>
                                <option value="ghanaian">Ghanaian</option>
                                <option value="greek">Greek</option>
                                <option value="grenadian">Grenadian</option>
                                <option value="guatemalan">Guatemalan</option>
                                <option value="guinea-bissauan">Guinea-Bissauan</option>
                                <option value="guinean">Guinean</option>
                                <option value="guyanese">Guyanese</option>
                                <option value="haitian">Haitian</option>
                                <option value="herzegovinian">Herzegovinian</option>
                                <option value="honduran">Honduran</option>
                                <option value="hungarian">Hungarian</option>
                                <option value="icelander">Icelander</option>
                                <option value="indian">Indian</option>
                                <option value="indonesian">Indonesian</option>
                                <option value="iranian">Iranian</option>
                                <option value="iraqi">Iraqi</option>
                                <option value="irish">Irish</option>
                                <option value="israeli">Israeli</option>
                                <option value="italian">Italian</option>
                                <option value="ivorian">Ivorian</option>
                                <option value="jamaican">Jamaican</option>
                                <option value="japanese">Japanese</option>
                                <option value="jordanian">Jordanian</option>
                                <option value="kazakhstani">Kazakhstani</option>
                                <option value="kenyan">Kenyan</option>
                                <option value="kittian and nevisian">Kittian and Nevisian</option>
                                <option value="kuwaiti">Kuwaiti</option>
                                <option value="kyrgyz">Kyrgyz</option>
                                <option value="laotian">Laotian</option>
                                <option value="latvian">Latvian</option>
                                <option value="lebanese">Lebanese</option>
                                <option value="liberian">Liberian</option>
                                <option value="libyan">Libyan</option>
                                <option value="liechtensteiner">Liechtensteiner</option>
                                <option value="lithuanian">Lithuanian</option>
                                <option value="luxembourger">Luxembourger</option>
                                <option value="macedonian">Macedonian</option>
                                <option value="malagasy">Malagasy</option>
                                <option value="malawian">Malawian</option>
                                <option value="malaysian">Malaysian</option>
                                <option value="maldivan">Maldivan</option>
                                <option value="malian">Malian</option>
                                <option value="maltese">Maltese</option>
                                <option value="marshallese">Marshallese</option>
                                <option value="mauritanian">Mauritanian</option>
                                <option value="mauritian">Mauritian</option>
                                <option value="mexican">Mexican</option>
                                <option value="micronesian">Micronesian</option>
                                <option value="moldovan">Moldovan</option>
                                <option value="monacan">Monacan</option>
                                <option value="mongolian">Mongolian</option>
                                <option value="moroccan">Moroccan</option>
                                <option value="mosotho">Mosotho</option>
                                <option value="motswana">Motswana</option>
                                <option value="mozambican">Mozambican</option>
                                <option value="namibian">Namibian</option>
                                <option value="nauruan">Nauruan</option>
                                <option value="nepalese">Nepalese</option>
                                <option value="new zealander">New Zealander</option>
                                <option value="ni-vanuatu">Ni-Vanuatu</option>
                                <option value="nicaraguan">Nicaraguan</option>
                                <option value="nigerien">Nigerien</option>
                                <option value="north korean">North Korean</option>
                                <option value="northern irish">Northern Irish</option>
                                <option value="norwegian">Norwegian</option>
                                <option value="omani">Omani</option>
                                <option value="pakistani">Pakistani</option>
                                <option value="palauan">Palauan</option>
                                <option value="panamanian">Panamanian</option>
                                <option value="papua new guinean">Papua New Guinean</option>
                                <option value="paraguayan">Paraguayan</option>
                                <option value="peruvian">Peruvian</option>
                                <option value="polish">Polish</option>
                                <option value="portuguese">Portuguese</option>
                                <option value="qatari">Qatari</option>
                                <option value="romanian">Romanian</option>
                                <option value="russian">Russian</option>
                                <option value="rwandan">Rwandan</option>
                                <option value="saint lucian">Saint Lucian</option>
                                <option value="salvadoran">Salvadoran</option>
                                <option value="samoan">Samoan</option>
                                <option value="san marinese">San Marinese</option>
                                <option value="sao tomean">Sao Tomean</option>
                                <option value="saudi">Saudi</option>
                                <option value="scottish">Scottish</option>
                                <option value="senegalese">Senegalese</option>
                                <option value="serbian">Serbian</option>
                                <option value="seychellois">Seychellois</option>
                                <option value="sierra leonean">Sierra Leonean</option>
                                <option value="singaporean">Singaporean</option>
                                <option value="slovakian">Slovakian</option>
                                <option value="slovenian">Slovenian</option>
                                <option value="solomon islander">Solomon Islander</option>
                                <option value="somali">Somali</option>
                                <option value="south african">South African</option>
                                <option value="south korean">South Korean</option>
                                <option value="spanish">Spanish</option>
                                <option value="sri lankan">Sri Lankan</option>
                                <option value="sudanese">Sudanese</option>
                                <option value="surinamer">Surinamer</option>
                                <option value="swazi">Swazi</option>
                                <option value="swedish">Swedish</option>
                                <option value="swiss">Swiss</option>
                                <option value="syrian">Syrian</option>
                                <option value="taiwanese">Taiwanese</option>
                                <option value="tajik">Tajik</option>
                                <option value="tanzanian">Tanzanian</option>
                                <option value="thai">Thai</option>
                                <option value="togolese">Togolese</option>
                                <option value="tongan">Tongan</option>
                                <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                                <option value="tunisian">Tunisian</option>
                                <option value="turkish">Turkish</option>
                                <option value="tuvaluan">Tuvaluan</option>
                                <option value="ugandan">Ugandan</option>
                                <option value="ukrainian">Ukrainian</option>
                                <option value="uruguayan">Uruguayan</option>
                                <option value="uzbekistani">Uzbekistani</option>
                                <option value="venezuelan">Venezuelan</option>
                                <option value="vietnamese">Vietnamese</option>
                                <option value="welsh">Welsh</option>
                                <option value="yemenite">Yemenite</option>
                                <option value="zambian">Zambian</option>
                                <option value="zimbabwean">Zimbabwean</option>
                            </select>
                        </div>
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_quickinformation_nationality .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                    
                    <div class="field-container col-md-7">
                        <div class="label-field">
                            '. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_languages .'
                        </div>
                        <div class="input min-large comment-input editable-content" placeholder="ex: French, DE" id="language" contenteditable="true" spellcheck="false">'. $this->language .'</div>
                         <div class="bulle-error">
                            <span class="message message-top"><span>'. $this->langFile[$this->pageName]->error_myprofile_quickinformation_languages .'</span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>        
                    
                    <div class="update-button-container">
                        <button class="share-button bt share-button-big" id="update-quickinfos">'. $this->langFile[$this->pageName]->bt_myprofile_updatebutton .'</button>
                    </div>
                </div>';
    }

    public function showMyQiBody()
    {
        $role = '';
        $currentteam = '';
        $previousteam = '';

        if($this->role)
        {
            $role = '<div class="quick-infos-container" id="qi-role">
                        <h1 class="quickinfo-title">'. $this->role .'</h1>
                        <p class="quickinfo-text">'. $this->langGenerals->word_on .' '. $this->game .'</p>
                    </div>';
        }
        if($this->current_team)
        {
            $currentteam = '<div class="quick-infos-container" id="qi-currentteam">
                                <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_currentteam .' </h1>
                                <p class="quickinfo-text">'. $this->current_team .'</p>           
                            </div>';
        }
        if($this->previous_team)
        {
            $previousteam = '<div class="quick-infos-container" id="qi-previousteam">
                                <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_previousteam .' </h1>
                                <p class="quickinfo-text">'. $this->previous_team .'</p>
                             </div>';
        }
        return  '<div class="bloc-container">
                    <div class="loader-container loader-profile-elem" id="loader-quickinfos-body">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    '. $role
                     . $currentteam
                     . $previousteam .'
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_age .' </h1>
                        <p class="quickinfo-text">'. $this->age .'</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_nationnality .' </h1>
                        <p class="quickinfo-text">' . $this->nationnality . '</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_language .' </h1>
                        <p class="quickinfo-text">'. $this->language .'</p>
                    </div>
                    <div class="quick-infos-container qi-link-container" id="profile-link-container">
                        <h1 class="quickinfo-title qi-link-title" id="profile-link-title">'. $this->langFile[$this->pageName]->title_qi_profileLink .' </h1>
                        <input class="quickinfo-text col-md-8 qi-link" id="profile-link" value="http://worldesport.com/index.php?p=profile&u='. $this->slug .'" data-action="select-text">           
                    </div>
                    <div class="quick-infos-container qi-link-container" id="invit-link-container">
                        <h1 class="quickinfo-title qi-link-title" id="invit-link-title">'. $this->langFile[$this->pageName]->title_qi_invitLink .' </h1>
                        <input class="quickinfo-text col-md-8 qi-link" id="invit-link" value="http://worldesport.com/index.php?p=invitation&tki='. $this->sponsorToken .'" data-action="select-text">           
                    </div>
                 </div>';
    }

    public function showUserQiBody()
    {
        $role = '';
        $currentteam = '';
        $previousteam = '';

        if($this->role)
        {
            $role = '<div class="quick-infos-container" id="qi-role">
                        <h1 class="quickinfo-title">'. $this->role .'</h1>
                        <p class="quickinfo-text">'. $this->langGenerals->word_on .' '. $this->game .'</p>
                    </div>';
        }
        if($this->current_team)
        {
            $currentteam = '<div class="quick-infos-container" id="qi-currentteam">
                                <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_currentteam .' </h1>
                                <p class="quickinfo-text">'. $this->current_team .'</p>           
                            </div>';
        }
        if($this->previous_team)
        {
            $previousteam = '<div class="quick-infos-container" id="qi-previousteam">
                                <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_previousteam .' </h1>
                                <p class="quickinfo-text">'. $this->previous_team .'</p>
                             </div>';
        }
        return  '<div class="bloc-container">                  
                    '. $role
                     . $currentteam
                     . $previousteam .'
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_age .' </h1>
                        <p class="quickinfo-text">'. $this->age .'</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_nationnality .' </h1>
                        <p class="quickinfo-text">' . $this->nationnality . '</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_language .' </h1>
                        <p class="quickinfo-text">'. $this->language .'</p>
                    </div>
                    <div class="quick-infos-container qi-link-container" id="profile-link-container">
                        <h1 class="quickinfo-title qi-link-title" id="profile-link-title">'. $this->langFile[$this->pageName]->title_qi_profileLink .' </h1>
                        <input class="quickinfo-text col-md-8 qi-link" id="profile-link" value="http://worldesport.com/index.php?p=profile&u='. $this->slug .'" data-action="select-text">           
                    </div>                   
                 </div>';
    }

    public function showUserEmptyQiBody()
    {
        //#todo gerer le cas ou l'user a mis des teams mais n'a pas rempli ses quick infos
        return  '<div class="bloc-container empty">
                    <div class="message-empty-container">
                        <p><span class="bold">'. $this->nickname .'</span> '. $this->langFile[$this->pageName]->text_quickinfos_empty .'</p>
                    </div>                    
                 </div>';
    }

    public function showMyQiEmployeeBody()
    {
        $jobtitle = '';
        $currentcomp = '';
        $location = '';

        if($this->jobtitle != '')
        {
            $jobtitle = '<div class="quick-infos-container" id="qi-jobtitle">
                            <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_jobtitle .' </h1>
                            <p class="quickinfo-text">'. $this->jobtitle .'</p>           
                        </div>';
        }
        if($this->current_company != "")
        {
            $currentcomp = '<div class="quick-infos-container" id="qi-currentcomp">
                                <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_currentcompany .' </h1>
                                <p class="quickinfo-text">'. $this->current_company .'</p>
                            </div>';
        }
        if($this->location != '')
        {
            $location = '<div class="quick-infos-container" id="qi-location">
                            <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_location .' </h1>
                            <p class="quickinfo-text">'. $this->location .'</p>
                         </div>';
        }
        return  '<div class="bloc-container">
                    <div class="loader-container loader-profile-elem" id="loader-quickinfos-body">
                        <span class="loader loader-double">
                        </span>
                    </div>
                    '. $jobtitle
                    . $currentcomp
                    . $location .'
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_age .' </h1>
                        <p class="quickinfo-text">'. $this->age .'</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_nationnality .' </h1>
                        <p class="quickinfo-text">' . $this->nationnality . '</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_language .' </h1>
                        <p class="quickinfo-text">'. $this->language .'</p>
                    </div>
                     <div class="quick-infos-container qi-link-container" id="profile-link-container">
                        <h1 class="quickinfo-title qi-link-title" id="profile-link-title">'. $this->langFile[$this->pageName]->title_qi_profileLink .' </h1>
                        <input class="quickinfo-text col-md-8 qi-link" id="profile-link" value="http://worldesport.com/index.php?p=profile&u='. $this->slug .'" data-action="select-text">           
                    </div>
                    <div class="quick-infos-container qi-link-container" id="invit-link-container">
                        <h1 class="quickinfo-title qi-link-title" id="invit-link-title">'. $this->langFile[$this->pageName]->title_qi_invitLink .' </h1>
                        <input class="quickinfo-text col-md-8 qi-link" id="invit-link" value="http://worldesport.com/index.php?p=invitation&tki='. $this->sponsorToken .'" data-action="select-text">           
                    </div>
                 </div>';
    }

    public function showUserQiEmployeeBody()
    {
        $jobtitle = '';
        $currentcomp = '';
        $location = '';

        if($this->jobtitle != '')
        {
            $jobtitle = '<div class="quick-infos-container" id="qi-jobtitle">
                            <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_jobtitle .' </h1>
                            <p class="quickinfo-text">'. $this->jobtitle .'</p>           
                        </div>';
        }
        if($this->current_company != "")
        {
            $currentcomp = '<div class="quick-infos-container" id="qi-currentcomp">
                                <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_currentcompany .' </h1>
                                <p class="quickinfo-text">'. $this->current_company .'</p>
                            </div>';
        }
        if($this->location != '')
        {
            $location = '<div class="quick-infos-container" id="qi-location">
                            <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_location .' </h1>
                            <p class="quickinfo-text">'. $this->location .'</p>
                         </div>';
        }
        return  '<div class="bloc-container">                   
                    '. $jobtitle
                     . $currentcomp
                     . $location .'
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->title_field_myprofile_quickinformation_age .' </h1>
                        <p class="quickinfo-text">'. $this->age .'</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_nationnality .' </h1>
                        <p class="quickinfo-text">' . $this->nationnality . '</p>
                    </div>
                    <div class="quick-infos-container">
                        <h1 class="quickinfo-title">'. $this->langFile[$this->pageName]->infoline_language .' </h1>
                        <p class="quickinfo-text">'. $this->language .'</p>
                    </div>                    
                    <div class="quick-infos-container qi-link-container" id="profile-link-container">
                        <h1 class="quickinfo-title qi-link-title" id="profile-link-title">'. $this->langFile[$this->pageName]->title_qi_profileLink .' </h1>
                        <input class="quickinfo-text col-md-8 qi-link" id="profile-link" value="http://worldesport.com/index.php?p=profile&u='. $this->slug .'" data-action="select-text">           
                    </div>
                 </div>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            if($this->todisplay == 'gamer')
            {
                //si l'user n'a jamais rempli ses infos
                if(!$this->birthdate)
                {
                    return $this->showMyQuickInfosft($this->showEditFt());
                }
                else{
                    return $this->showMyQuickInfos($this->showMyQiBody());
                }
            }
            if($this->todisplay == 'employee')
            {
                //si l'user n'a jamais rempli ses infos
                if(!$this->birthdate)
                {
                    return $this->showMyQuickInfosft($this->showEdit());
                }
                else{
                    return $this->showMyQuickInfos($this->showMyQiEmployeeBody());
                }
            }
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            if($this->todisplay == 'gamer')
            {
                //si l'user n'a jamais rempli ses infos
                if(!$this->birthdate) //#todo simple: remplacer ca par un quickinfos state
                {
                    return $this->showUserQuickInfosEmpty($this->showUserEmptyQiBody());
                }
                else{
                    return $this->showUserQuickInfos($this->showUserQiBody());
                }
            }
            if($this->todisplay == 'employee')
            {
                //si l'user n'a jamais rempli ses infos
                if(!$this->birthdate)
                {
                    return $this->showUserQuickInfosEmpty($this->showUserEmptyQiBody());
                }
                else{
                    return $this->showUserQuickInfos($this->showUserQiEmployeeBody());
                }
            }
        }
    }

    public function createLocationString($city, $country)
    {
        return $city .', '. $country;
    }
}