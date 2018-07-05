<?php

namespace app\Displays\SearchMessagerie;

use app\Displays\SearchMessagerie\displayAddUserResult\displayAddUserResult;
use app\Displays\SearchMessagerie\displayConvResult\displayConvResult;

class displayMessagerieSearchResults
{
    private $todisplay;
    private $results;

    public function __construct($results = false, $todisplay = false)
    {
        $this->results =    $results;
        $this->todisplay =  $todisplay;
    }
    
    public function displayResultsFriends()
    {
        $content = '';
        foreach($this->results['friends'] as $result)
        {
            $display = new displayAddUserResult($result, $this->todisplay);
            $content .= $display->show();
        }
        return "<div class='add-user-result-container'>
                    <div class='add-user-result-content'>
                        ". $content ."
                    </div>
                </div>";
    }
    
    public function displayFriendsAndConvs()
    {
        $contentFriends = '';
        $contentConv = '';
        foreach($this->results['friends'] as $result)
        {
            $display = new displayAddUserResult($result, 'friends');
            $contentFriends .= $display->show();
        }
        foreach($this->results['convs'] as $result)
        {
            $display = new displayConvResult($result, 'convs');
            $contentConv .= $display->show();
        }

        if($contentConv)
        {
            $contentConv = "<div class='title-result'>
                                <p>Conversations</p>
                            </div>
                            <div class='add-conv-result-content overflow'>
                                ". $contentConv ."
                            </div>";
        }
        else{
            $contentConv = "";
        }

        if($contentFriends)
        {
            $contentFriends = "<div class='title-result'>
                                    <p>Users</p>
                                </div>
                                <div class='add-user-result-content overflow'>
                                    ". $contentFriends ."
                                </div>";
        }
        else{
            $contentFriends = "";
        }

        return "<div class='add-user-result-container'>
                    ". $contentFriends ."
                    ". $contentConv ."
                </div>";
    }

    public function displayConvs()
    {
        $contentConv = '';
        foreach($this->results['convs'] as $result)
        {
            $display = new displayConvResult($result, 'convs');
            $contentConv .= $display->show();
        }

        if($contentConv)
        {
            $contentConv = "<div class='title-result'>
                                <p></p>
                            </div>
                            <div class='add-conv-result-content overflow'>
                                ". $contentConv ."
                            </div>";
        }
        else{
            $contentConv = "";
        }

        return "<div class='convs-result-container'>
                    ". $contentConv ."
                </div>";
    }

    public function show()
    {
        if($this->todisplay == "friends")
        {
            return $this->displayResultsFriends();
        }
        if($this->todisplay == "friendsandconvs")
        {
            return $this->displayFriendsAndConvs();
        }
        if($this->todisplay == "convs")
        {
            return $this->displayConvs();
        }
    }
}