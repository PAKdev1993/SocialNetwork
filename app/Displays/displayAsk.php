<?php
/**
 * Created by PhpStorm.
 * User: PAK
 * Date: 07/08/2016
 * Time: 23:19
 */

namespace app\Displays;

use app\Table\AppDisplay;
use app\App;

//#todo fairel e menage ds ces class de display trop mal triées

class displayAsk extends AppDisplay
{
    private $todisplay;

    public function __construct($todisplay)
    {
        parent::__construct();
        $this->todisplay = $todisplay;
    }

    /*
     * ASK DELETE TEAM
     */
    public function askDeleteTeam()
    {
        return '<div class="box confirm-delete" id="delete-team-box">
                    <p>'. $this->langFileAsk->title_ask_delete_team .'</p>
                    <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
     * ASK DELETE EVENT
     */
    public function askDeleteEvent()
    {
        return '<div class="box confirm-delete" id="delete-event-box">
                    <p>'. $this->langFileAsk->title_ask_delete_event .'</p>
                    <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
     * ASK DELETE GAME
     */
    public function askDeleteGame()
    {
        return '<div class="box confirm-delete" id="delete-game-box">
                    <p>'. $this->langFileAsk->title_ask_delete_game .'</p>
                     <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
     * ASK DELETE EQUIPMENT
     */
    public function askDeleteEquipment()
    {
        return '<div class="box confirm-delete" id="delete-equipment-box">
                    <p>'. $this->langFileAsk->title_ask_delete_equipment .'</p>
                     <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
    * ASK DELETE COMPANY
    */
    public function askDeleteCompany()
    {
        return '<div class="box confirm-delete" id="delete-company-box">
                    <p>'. $this->langFileAsk->title_ask_delete_company .'</p>
                    <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
    * ASK DELETE EMPLOYEE EVENT
    */
    public function askDeleteEmpEvent()
    {
        return '<div class="box confirm-delete" id="delete-emp-event-box">
                    <p>'. $this->langFileAsk->title_ask_delete_event .'</p>
                     <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
    * ASK DELETE EMPLOYEE EVENT
    */
    public function askDeletePost()
    {
        return '<div class="box confirm-delete" id="box-delete-post">
                    <p>'. $this->langFileAsk->title_ask_delete_post .'</p>
                    <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
    * ASK DELETE EMPLOYEE EVENT
    */
    public function askHideUsersPosts()
    {
        return '<div class="box confirm-hide-user">
                    <p>'. $this->langFileAsk->title_ask_hide_all_user_posts .'</p>
                   <button class="valid-delete valid bt-y">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    /*
    * ASK DELETE EMPLOYEE EVENT
    */
    public function askDeleteConv()
    {
        return '<div class="box confirm-delete-conv">
                    <p>La conv n\'aparaitra plus, y compris dans le message center. Vous ne recevrez plus les messages et notification venant de cette conversation</p>
                   <button class="valid-delete valid bt-y" data-action="valid-delete-conv">
                        '. $this->langFileAsk->bt_yes .'
                    </button>
                    <button class="valid-delete valid bt-n" data-action="cancel-delete-conv">
                        '. $this->langFileAsk->bt_cancel .'
                    </button>
                </div>';
    }

    public function show()
    {
        if($this->todisplay == 'deleting-team')
        {
            return $this->askDeleteTeam();
        }
        if($this->todisplay == 'deleting-event')
        {
            return $this->askDeleteEvent();
        }
        if($this->todisplay == 'deleting-game')
        {
            return $this->askDeleteGame();
        }
        if($this->todisplay == 'deleting-equipment')
        {
            return $this->askDeleteEquipment();
        }
        if($this->todisplay == 'deleting-company')
        {
            return $this->askDeleteCompany();
        }
        if($this->todisplay == 'deleting-employee-event')
        {
            return $this->askDeleteEmpEvent();
        }
        if($this->todisplay == 'deleting-post')
        {
            return $this->askDeletePost();
        }
        if($this->todisplay == 'hide-users-posts')
        {
            return $this->askHideUsersPosts();
        }
        if($this->todisplay == 'deleting-conv')
        {
            return $this->askDeleteConv();
        }
    }
}