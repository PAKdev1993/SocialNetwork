<?php
/**
 * Created by PhpStorm.
 * User: PAK
 * Date: 07/08/2016
 * Time: 23:19
 */

namespace app\Displays;

use app\App;
use app\Table\AppDisplay;

//#todo fairel e menage ds ces class de display trop mal triées

class displayContact extends AppDisplay
{
    private $todisplay;

    public function __construct($todisplay)
    {
        parent::__construct();
        $this->todisplay = $todisplay;
    }

    /*
    * POPUP REPORT ABUSE
    */
    public function popupReportAbuse()
    {
        return '<div class="box contact-box" id="report-abuse-box">
                    <div class="contact-form-container">
                        <div class="title-box">
                            <p>'. $this->langFileContactUs->title_report_abuse_form .'</p>
                        </div>                 
                        <div class="box-form col-md-12">
                            <div class="field-container col-md-12">                                 
                                 <div class="label-field">
                                    <p>'. $this->langFileContactUs->title_field_object_abuse .'</p>
                                 </div>
                                 <div class="messages-container col-md-12">
                                    <div class="error-input-container error-field error-object novisible" tabindex="0">
                                        <p>'. $this->langFileContactUs->error_form_object .'</p>
                                    </div>                                                       
                                 </div>
                                 <div class="input full comment-input editable-content" placeholder="'. $this->langFileContactUs->placeholder_field_object_abuse .'" spellcheck="false" id="report-abuse-object" contenteditable="true"></div>
                            </div>
                            <div class="field-container col-md-12">                                 
                                 <div class="label-field">
                                    <p>'. $this->langFileContactUs->title_field_message .'</p>
                                </div>
                                <div class="messages-container col-md-12">
                                    <div class="error-input-container error-field error-details novisible" tabindex="0">
                                        <p>'. $this->langFileContactUs->error_form_message .'</p>
                                    </div>                                                      
                                </div>
                                <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFileContactUs->placeholder_message .'" spellcheck="false" id="report-abuse-message" contenteditable="true"></div>
                            </div>
                        </div>
                        <div class="bt-container col-md-12">
                             <div class="loader-container loader-profile-elem loader-profile-upload-logo" id="loader-mail-abuse">
                                <div class="loader-double-container">
                                    <span class="loader loader-double">
                                    </span>
                                </div>                               
                             </div>
                            <button class="share-button bt share-button-big" data-action="send-report" id="share-post">'. $this->langFileContactUs->bt_form_send .'</button>
                            <button class="share-button bt share-button-big cancel" data-action="cancel-contact" id="share-post">'. $this->langFileContactUs->bt_form_cancel .'</button>                       
                        </div>       
                    </div>
                    <div class="confirm-container">
                        <div class="title-box">
                            <p>'. $this->langFileContactUs->title_field_confirmation .'</p>
                        </div> 
                        <div class="bt-container col-md-12">                            
                             <button class="valid" data-close="valid-sent">
                                '. $this->langFileContactUs->bt_form_close .'
                            </button>
                        </div>
                    </div>                           
                </div>';
    }

    /*
    * POPUP REPORT ABUSE
    */
    public function popupContactUs()
    {
        return '<div class="box contact-box" id="report-abuse-box">
                    <div class="contact-form-container">
                        <div class="title-box">
                            <p>'. $this->langFileContactUs->title_contact_form .'</p>
                        </div>                 
                        <div class="box-form col-md-12">
                            <div class="field-container col-md-12">                                 
                                 <div class="label-field">
                                    <p>'. $this->langFileContactUs->title_field_object .'</p>
                                 </div>
                                 <div class="messages-container col-md-12">
                                    <div class="error-input-container error-field error-object novisible" tabindex="0">
                                        <p>'. $this->langFileContactUs->error_form_object .'</p>
                                    </div>                                                       
                                 </div>
                                 <div class="input full comment-input editable-content" placeholder="'. $this->langFileContactUs->placeholder_object .'" spellcheck="false" id="contact-object" contenteditable="true"></div>
                            </div>
                            <div class="field-container col-md-12">                                 
                                 <div class="label-field">
                                    <p>'. $this->langFileContactUs->title_field_message .'</p>
                                </div>
                                <div class="messages-container col-md-12">
                                    <div class="error-input-container error-field error-details novisible" tabindex="0">
                                        <p>'. $this->langFileContactUs->error_form_message .'</p>
                                    </div>                                                      
                                </div>
                                <div class="input descript comment-input share-bloc-text editable-content desc-input" placeholder="'. $this->langFileContactUs->placeholder_message .'" spellcheck="false" id="contact-message" contenteditable="true"></div>
                            </div>
                        </div>
                        <div class="bt-container col-md-12">
                            <div class="loader-container loader-profile-elem loader-profile-upload-logo" id="loader-mail-contact">
                                <div class="loader-double-container">
                                    <span class="loader loader-double">
                                    </span>
                                </div>                               
                             </div>
                            <button class="share-button bt share-button-big" data-action="send-contact" id="share-post">'. $this->langFileContactUs->bt_form_send .'</button>
                            <button class="share-button bt share-button-big cancel" data-action="cancel-contact" id="share-post">'. $this->langFileContactUs->bt_form_cancel .'</button>                       
                        </div>       
                    </div>
                    <div class="confirm-container">
                        <div class="title-box">
                            <p>'. $this->langFileContactUs->title_field_confirmation .'</p>
                        </div> 
                        <div class="bt-container col-md-12">
                             <button class="valid" data-close="valid-sent">
                                '. $this->langFileContactUs->bt_form_close .'
                            </button>
                        </div>
                    </div>                           
                </div>';
    }

    public function show()
    {
        if($this->todisplay == 'ReportAbuse')
        {
            return $this->popupReportAbuse();
        }
        if($this->todisplay == 'Contactus')
        {
            return $this->popupContactUs();
        }
    }

}