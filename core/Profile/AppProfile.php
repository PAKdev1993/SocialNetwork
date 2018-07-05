<?php

namespace core\Profile;

use app\App;
use core\Session\Session;

use app\Table\UserModel\UserModel;

use app\Displays\displayNotifyMyNetwork;
use app\Table\UserModel\displayUser;

use app\Table\MyCommunity\Contacts\displayContacts;
use app\Table\MyCommunity\PendingContacts\displayPendingContacts;
use app\Table\MyCommunity\Followers\displayFollowers;

use app\Table\Profile\ProfileCommunity\ProfileFollowerPart\displayProfileFollowerPart;
use app\Table\Profile\ProfileCommunity\ProfileContactsPart\displayProfileContactPart;


use app\Table\MyCommunity\MyCommunityModel;
use app\Table\MyCommunity\RecommendedContacts\displayRecommendedContacts;
use core\Tables\RecommendedContacts\MyRecommendedContacts;

use core\Timeline\ProfileTimeline;
use core\Timeline\Posts;

use app\Table\Images\ImagesModel;
use app\Table\Images\AlbumPreview\displayAlbumPreview;

use app\Table\ProfileViewers\ProfileViewModel;
use app\Table\ProfileViewers\displayProfileViewers;

class AppProfile
{
    protected $session;

    protected $userToDisplay;
    protected $myself;

    protected $nbPostsToDisplay;
    
    public function __construct($user = false)
    {
        $this->session = Session::getInstance();

        $this->userToDisplay = $user;
        $this->myself = Session::getInstance()->read('auth');
        $this->defineWhoIs($this->userToDisplay);
    }

    /****************************************************************************\
     *                          MY PROFILE PART                                 *
    \****************************************************************************/
    public function getMyInterests()
    {
        $display = new displayUser($this->myself, 'interests');
        return $display->show();
    }

    public function getMyCoverPic()
    {
        $display = new displayUser($this->myself);
        return $display->showCoverPic();
    }

    public function getMyProfilePic()
    {
        $display = new displayUser($this->myself);
        return $display->showMyProfilePic();
    }

    public function getMyTimeline()
    {
        $begin = 0;
        $nbPostToDisplay = 20;
        $timeline = new ProfileTimeline();
        $timelineContent = $timeline->getMyTimeline($begin, $nbPostToDisplay);
        return $timelineContent;
    }

    public function getMyGaleryPreview()
    {
        //get photos
        $model = new ImagesModel();
        $imgsToPreview = $model->getMyAlbumPreview();
        $display = new displayAlbumPreview($imgsToPreview);

        //retour
        return $display->show();
    }

    public function getNotifyMyNetworkBloc()
    {
        $display = new displayNotifyMyNetwork();
        return $display->show();
    }

    public function getMyContacts()
    {
        $model = new MyCommunityModel();
        $contacts = $model->getMyContacts(0, 100);
        $display = new displayContacts($contacts);
        return $display->show();
    }

    public function getMyPendingContacts()
    {
        $model = new MyCommunityModel();
        $contactsQi = $model->getMypendingContactsQi(0, 100);
        $display = new displayPendingContacts($contactsQi);
        return $display->show();
    }

    public function getMyFollowers()
    {
        $model = new MyCommunityModel();
        $followers = $model->getMyFolowers(0, 100);
        $display = new displayFollowers($followers);
        return $display->show();
    }

    //get recommended contacts in the right bloc
    public function getMyRecommendedContactsRightBloc($pageName)
    {
        $model =        new MyRecommendedContacts($pageName);
        $userModel =    new UserModel(App::getDatabase());
        $usersIdAssoc = $model->getMyRecommendedContacts();
        $recommendedUsers = [];
        foreach($usersIdAssoc as $assoc)
        {
            foreach($assoc as $userid)
            {
                array_push($recommendedUsers, $userModel->getUserFromId($userid));
            }
        }
        $display = new displayRecommendedContacts($recommendedUsers);
        return $display->show();
    }

    //get recommended contacts in the recommended contacts section
    public function getMyRecommendedContacts()
    {
        $pageName = 'MyRecommendedContacts';
        $model =        new MyRecommendedContacts($pageName);
        $userModel =    new UserModel(App::getDatabase());
        $usersIdAssoc = $model->getMyRecommendedContacts();
        $recommendedUsers = [];
        foreach($usersIdAssoc as $assoc)
        {
            foreach($assoc as $userid)
            {
                array_push($recommendedUsers, $userModel->getUserFromId($userid));
            }
        }
        $display = new displayRecommendedContacts($recommendedUsers, $pageName);
        return $display->show();
    }

    public function notifyMyNetwork($typeaction, $action, $elemType = false, $elemid = false)
    {
        //creer le post dans la timeline
        if($this->myself->notifyMyNetwork == 1)
        {
            //check interval entre ce post et le dernier, doit etre superieur a 1h
            $postCore = new Posts();
            //check l'interval entre le moment du nouveua post et le dernier, si c'est le premier post de notifiymynetwork de l'user, $valid return true
            $valid = $postCore->checkIntervalLastNotifyPost($typeaction);

            if($valid == '1')
            {
                $postCore->postNotifMyNetwork($typeaction, $action, $elemType, $elemid);
            }
            else{
                //sinon on update la datetime du dernier post similaire
                $postCore->updateDateLastNotifyMyNetworkPost($typeaction);
            }
        }
        else{
            return false;
        }
    }

    public function getMyNbContacts()
    {
        $model = new MyCommunityModel();
        return $model->getMyNbContacts();
    }

    public function getMyNbFolowers()
    {
        $model = new MyCommunityModel();
        return $model->getMyNbFollowers();
    }

    public function getBlocProfileViewers()
    {
        $model = new ProfileViewModel();
        $profileViewers = $model->getMyProfileViewers();
        $display = new displayProfileViewers($profileViewers, 'rightBloc');
        return $display->show();
    }

    /****************************************************************************\
     *                          SOMEONE ELESE PART                              *
    \****************************************************************************/
    public function getUserCoverPic()
    {
        $display = new displayUser($this->userToDisplay);
        return $display->showCoverPic();
    }

    public function getUserProfilePic()
    {
        $display = new displayUser($this->userToDisplay);
        return $display->showUserProfilePic();
    }

    public function getUserTimeline()
    {
        $begin = 0;
        $nbPostToDisplay = 10;
        $timeline = new ProfileTimeline($this->userToDisplay);
        $timelineContent = $timeline->getTimeline($begin, $nbPostToDisplay);
        return $timelineContent;
    }

    public function getUserInterests()
    {
        $display = new displayUser($this->userToDisplay, 'interests');
        return $display->show();
    }

    public function getUserGaleryPreview()
    {
        //get photos
        $model = new ImagesModel();
        $imgsToPreview = $model->getUserAlbumPreviewFromUserId($this->userToDisplay->pk_iduser);
        $display = new displayAlbumPreview($imgsToPreview, $this->userToDisplay);

        //retour
        return $display->show();
    }

    public function getUserNbContacts()
    {
        $model = new MyCommunityModel();
        return $model->getNbContactsFromIduser($this->userToDisplay->pk_iduser);
    }

    public function getUserNbFolowers()
    {
        $model = new MyCommunityModel();
        return $model->getNbFollowersFromIduser($this->userToDisplay->pk_iduser);
    }

    public function getUserProfileFollowers()
    {
        $model = new MyCommunityModel();
        $personnalState = $model->amIFollowingFromIduser($this->userToDisplay->pk_iduser);
        $nbFollowers = $model->getNbFollowersFromIdUser($this->userToDisplay->pk_iduser);
        $display = new displayProfileFollowerPart($nbFollowers, $this->userToDisplay->pk_iduser, $personnalState);
        return $display->show();
    }

    public function getUserProfileContacts()
    {
        $model = new MyCommunityModel();
        $nbContacts = $model->getNbContactsFromIduser($this->userToDisplay->pk_iduser);
        if($model->amIContactFromIdUser($this->userToDisplay->pk_iduser))
        {
            $personnalState = 2;
        }
        else{
            $personnalState = 0;
        }
        
        if($model->amIPendingContactFromIdUser($this->userToDisplay->pk_iduser))
        {
            $personnalState = 1;
        }
        $display = new displayProfileContactPart($nbContacts, $this->userToDisplay->pk_iduser, $personnalState);
        return $display->show();
    }

    public function defineWhoIs($user)
    {
        if($user)
        {
            $arrayWhoIs = array('id' => $this->userToDisplay->pk_iduser, 'firstname' => $this->userToDisplay->firstname, 'nickname' => '"'. $this->userToDisplay->nickname.'"', 'lastname' => $this->userToDisplay->lastname);
            Session::getInstance()->write('whois',$arrayWhoIs);
        }
        else{
            $arrayWhoIs = array('id' => $this->myself->pk_iduser, 'firstname' => $this->myself->firstname, 'nickname' => '"'. $this->myself->nickname.'"', 'lastname' => $this->myself->lastname);
            Session::getInstance()->write('whois',$arrayWhoIs);
        }
    }
}