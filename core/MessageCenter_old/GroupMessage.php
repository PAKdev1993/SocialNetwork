<?php
namespace core\MessageCenter;
use app\App;
use core\Actions\Action;
use core\Functions;
use core\Session\Session;

/*Class Group MessageCenter
*World eSport
*/
/*
Function:
-selectMessageGroup() => Select GROUP with MESSAGES looks same as chatHeartbeat()
-startGroupMessage() => Setup Group => looks like startChatSession
-closeGroup() => die() => Delete Chat/group => closeChat
-sendMessagetoGroup() =>Send message to group => GroupID $this->currentUser->id => sendChat
*/
class GroupMessage
{
    public function __construct(){
        $this->db = App::getDatabase();
        $this->currentUser = Session::getInstance->read('auth');
    }

    public function selectMessageGroup(){
        //Select d'abord le group par l'id du currentUser
        $group                   = "SELECT * FROM enregConversation WHERE `userID`='".$this->currentUser->pkid."'";
        $group_selected          = $this->db->query($group);
        //Selection du nom du group (a changer de place..)
        $getTitleGroup           = "SELECT `title` FROM Conversation WHERE `idconversation`= '".$group_selected['id_conversation']."'";
        $getTitleGroup_selected  = $this->db->query($getTitleGroup);
        //Select * messages from Conversation
        $getMessage              = "SELECT * FROM messConv WHERE `idconversation`= '".$group_selected['id_conversation']."'";
        $getMessage_selected     = $this->db->query($getMessage);

                //initialisation  de la boucle et du reste
        $items = '';
        $chatBoxes = array():
        while($mess = $getMessage_selected->fetch()){
            $getUserDetails          = "SELECT * FROM we__users WHERE `pkid`='".$mess['userid']"'";
            $getUserDetails_selected = $this->db->query($getUserDetails);
            if (!isset($_SESSION['openChatBoxes'][$getUserDetails_selected['slug']]) && isset($_SESSION['chatHistory'][$getUserDetails_selected['slug']])) {
    			$items = $_SESSION['chatHistory'][$getUserDetails_selected['slug']];
    		}

    		$items .= <<<EOD
    					   {
    			"s": "0",
    			"f": "{$getUserDetails_selected['slug']}",
    			"m": "{$mess['message']}"
    	   },
EOD;
        }
        if (!isset($_SESSION['chatHistory'][$getUserDetails_selected['slug']])) {
            $_SESSION['chatHistory'][$getUserDetails_selected['slug']] = '';
        }

        $_SESSION['chatHistory'][$getUserDetails_selected['slug']] .= <<<EOD
                               {
                "s": "0",
                "f": "{$getUserDetails_selected['slug']}",
                "m": "{$mess['message']}"
           },
        EOD;

            unset($_SESSION['tsChatBoxes'][$getUserDetails_selected['slug']]);
            $_SESSION['openChatBoxes'][$getUserDetails_selected['slug']] = $mess['sent'];
        }

        if (!empty($_SESSION['openChatBoxes'])) {
        foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
            if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
                $now = time()-strtotime($time);
                $time = date('g:iA M dS', strtotime($time));

                $message = "Sent at $time";
                if ($now > 180) {
                    $items .= <<<EOD
        {
        "s": "2",
        "f": "$chatbox",
        "m": "{$message}"
        },
        EOD;

        if (!isset($_SESSION['chatHistory'][$chatbox])) {
            $_SESSION['chatHistory'][$chatbox] = '';
        }

        $_SESSION['chatHistory'][$chatbox] .= <<<EOD
            {
        "s": "2",
        "f": "$chatbox",
        "m": "{$message}"
        },
        EOD;
                $_SESSION['tsChatBoxes'][$chatbox] = 1;
            }
            }
        }
        }

        if ($items != '') {
            $items = substr($items, 0, -1);
        }
        //header('Content-type: application/json');
        ?>
        {
            "items": [
                <?php echo $items;?>
            ]
        }

        <?php
                exit(0);


    }

    public function chatBoxSession($chatbox) {
    	if (isset($_SESSION['chatHistory'][$chatbox])) {
    		$items = $_SESSION['chatHistory'][$chatbox];
    	}

    	return $items;
    }
    public function startGroupMessage(){
        if (!empty($_SESSION['openChatBoxes'])) {
            foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
                $items .= $this->chatBoxSession($chatbox);
            }
        }


        if ($items != '') {
            $items = substr($items, 0, -1);
        }
        header('Content-type: application/json');
    ?>
    {
            "username": "<?php echo $this->currentUser->slug;?>",
            "items": [
                <?php echo $items;?>
            ]
    }

    <?php


        exit(0);
    }

    public function closeGroup(){
        unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);

    	echo "1";
    	exit(0);
    }

    public function sendMessagetoGroup(){
        $this->currentUser = Session::getInstance()->read('auth');
        $from = $this->currentUser->pkid;
        $to = $_POST['to'];
        $message = $_POST['message'];

        $_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());

        if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
            $_SESSION['chatHistory'][$_POST['to']] = '';
        }

        $_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
                           {
                "s": "1",
                "f": "{$to}",
                "m": "{$message}"
           },
        EOD;


        unset($_SESSION['tsChatBoxes'][$_POST['to']]);
        $save_message = "INSERT INTO messConv (message, messConv.to, messConv.sent, messConv.from) values ('".$message."', '".$_POST['to']."', NOW(), '".$from."')";
        $this->db->query($save_message);
        echo "1";
        exit(0);
    }

    public function addMembers($member, $idconversation){
        $addMembers = "INSERT INTO enregConversation (userID, idconversation) VALUES ('".$member."', '".$idconversation."')";
        $this->db->query($addMembers);
        return $membre.' ajout&eacute; avec succ&egrave;s!';
    }


}
