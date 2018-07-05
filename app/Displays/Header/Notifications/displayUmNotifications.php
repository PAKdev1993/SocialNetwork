<?php

namespace app\Displays\Header\Notifications;

use app\Table\Notifications\displayNotifications;

class displayUmNotifications
{
    private $notifications;

    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }

    public function showNotificationsPart()
    {
        $display = new displayNotifications($this->notifications, 'UmNotifications');
        return $display->show();
    }

    public function show()
    {
        return $this->showNotificationsPart();
    }
}