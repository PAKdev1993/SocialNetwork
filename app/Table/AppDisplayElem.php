<?php
/**
 * Created by PhpStorm.
 * User: PAK
 * Date: 19/08/2016
 * Time: 15:40
 */

namespace app\Table;

use app\App;
use core\Session\Session;

class AppDisplayElem
{
    protected $monthArray  = ['empty','January','February','March','April','May','June','July','August','September','October','November','December'];
    protected $monthTraduce;
    protected $langfile;
    protected $gearTraduce;
    protected $unauthorizedChar = [' ', '\'','\\','/','.','^'];

    public function __construct()
    {
        $this->langModel =      App::getLangModel();
        $this->monthTraduce =   $this->langModel->getLangMonths();
        $this->gearTraduce =    $this->langModel->getLangGear();
        $this->currentUser =    Session::getInstance()->read('auth');
    }
}