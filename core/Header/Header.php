<?php

namespace core\Header;

use app\App;
use app\Displays\Header\displayHeader;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Notifications\NotificationModel;
use app\Table\ProfileViewers\ProfileViewModel;
use core\Session\Session;

class Header
{
    protected $session;

    protected $userToDisplay;
    protected $myself;

    protected $nbPostsToDisplay;

    public function __construct()
    {
        $this->session = Session::getInstance();
        $this->myself = Session::getInstance()->read('auth');
    }

    public function getHeader()
    {
        $modelViewer =          new ProfileViewModel();
        $modelNotifications =   new NotificationModel();
        $modelConversation =    new ConversationModel();

        $profileViewers = $modelViewer->getMyProfileViewerForHeader();
        $notifications = $modelNotifications->getMyNotificationsUnderMenu();
        $unreadedConvs = $modelConversation->getUnreadedConvs();
        $display = new displayHeader($profileViewers, $notifications, $unreadedConvs);
        return $display->show();
    }
}