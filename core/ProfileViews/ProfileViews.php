<?php

namespace core\ProfileViews;

use app\Table\ProfileViewers\displayProfileViewers;
use app\Table\ProfileViewers\ProfileViewModel;

class ProfileViews
{
    private $currentUser;

    public function __construct($user)
    {
        $this->currentUser = $user;
    }

    public function getMyProfileViewers()
    {
        $model = new ProfileViewModel();
        $profileViewers = $model->getMyProfileViewers();
        $display = new displayProfileViewers($profileViewers, 'ProfileViewers');
        return $display->show();
    }
}