<?php
namespace core\ManageAccount;

use core\Session\Session;
use app\Table\ManageAccount\ManageAccountModel;
use app\Table\ManageAccount\displayManageAccountOptions;

class ManageAccount
{
    private $currentUser;

    public function __construct()
    {
        $this->currentUser = Session::getInstance()->read('auth');
    }
    
    public function getManageAccountOptions()
    {
        $model = new ManageAccountModel();
        //old $manageAccountOptions = array_merge($model->getWishReceiveNotifWhen(), $model->getWishReceiveEmailWhen(), $model->getWishReceiveWeeklyEmailWhen());
        //old $manageAccountOptions = array_merge($model->getWishReceiveEmailWhen(), $model->getWishReceiveWeeklyEmailWhen());
        $manageAccountOptions = $model->getWishReceiveEmailWhen();
        $display = new displayManageAccountOptions($manageAccountOptions);
        return $display->show();
    }
}