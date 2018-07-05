<?php
namespace app\Table\Messages\Messages;

use app\Table\AppDisplay;
use app\Table\Messages\Messages\Message\displayMessage;

class displayMessages extends AppDisplay
{
    private $messages;
    protected $todisplay;
    protected $displayViewedBy;   /*
                            *   chatBoxe : pour display une conversation et messages de chatBox
                            */

    protected $pageName;

    public function __construct($messages = false, $todisplay = false, $displayViewedBy = true)
    {
        $this->pageName =   'messagecenter';
        parent::__construct();
        $this->todisplay =  $todisplay;
        $this->messages =   $messages;
        $this->displayViewedBy = $displayViewedBy;
    }

    public function showChatBoxesMessages()
    {
        $messages       = array();
        $lengthMessages = sizeof($this->messages);
        $lastMessageIndex = $lengthMessages - 1;

        //si la taille du tableau est egale a 1
        if($lengthMessages <= 1)
        {
            //pour le dernier message il s'agit de determiner quelle users l'ont vu ou non
            $display = new displayMessage($this->messages[0], false, $this->displayViewedBy);
            array_push($messages,$display->show());
        }
        //sinon
        else{
            for($i = 0; $i < $lengthMessages; $i++)
            {
                //pour le premier message de la liste, $prevMessAuthId est a false car il s'agit de determiner l'auth id du precédent message au premier message de la liste
                if($i < $lastMessageIndex)
                {
                    $display = new displayMessage($this->messages[$i], false, false);
                    array_push($messages, $display->show());
                }
                //pour le dernier message il s'agit de determiner quelle users l'ont vu ou non
                elseif($i == $lastMessageIndex)
                {
                    $display = new displayMessage($this->messages[$i], false, $this->displayViewedBy);
                    array_push($messages,$display->show());
                }
            }
        }
        return $messages;
    }
    
    public function showDiscussionMessages()
    {
        $messages       = array();
        $lengthMessages = sizeof($this->messages);
        $lastMessageIndex = $lengthMessages - 1;

        //si la taille du tableau est egale a 1
        if($lengthMessages <= 1)
        {
            //pour le dernier message il s'agit de determiner quelle users l'ont vu ou non
            $display = new displayMessage($this->messages[0], false, $this->displayViewedBy);
            array_push($messages,$display->show());
        }
        //sinon
        else{
            for($i = 0; $i < $lengthMessages; $i++)
            {
                //pour le premier message de la liste, $prevMessAuthId est a false car il s'agit de determiner l'auth id du precédent message au premier message de la liste
                if($i < $lastMessageIndex)
                {
                    $display = new displayMessage($this->messages[$i], false, false);
                    array_push($messages, $display->show());
                }
                //pour le dernier message il s'agit de determiner quelle users l'ont vu ou non
                elseif($i == $lastMessageIndex)
                {
                    $display = new displayMessage($this->messages[$i], false, $this->displayViewedBy);
                    array_push($messages,$display->show());
                }
            }
        }
        return $messages;
    }
    
    public function show()
    {
        if($this->messages)
        {
            if($this->todisplay == 'chatBoxe')
            {
                return $this->showChatBoxesMessages();
            }
            if($this->todisplay == 'discussion')
            {
                return $this->showDiscussionMessages();
            }
        }
    }
}