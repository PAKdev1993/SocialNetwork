
Skip to content
This repository

Pull requests
Issues
Gist

@WEPAKdev

2
0

0

hvtent/tmpchat Private
Code
Issues 0
Pull requests 0
Projects 0
Wiki
Pulse
Graphs
tmpchat/core/MessageCenter/Message.php
bfabd38 6 days ago
@Wolf26 Wolf26 Add comment to document the func.
188 lines (162 sloc) 5.84 KB
<?php
namespace core\MessageCenter;
use app\App;
use core\Actions\Action;
use core\Functions;
use core\Session\Session;
class Message{
	//initiate the Database instruction
	public function __construct()
	{
		$this->db = App::getDatabase();
		$this->currentUser = Session::getInstance()->read('auth');
	}
	//initiate the chat => Select * FROM
	//chatHeartbeat()
	//works: Fonctionne a partir du moment ou: onclick javascript.chatWith() = TRUE => Initalisation de la fenêtre avec appelle de la fonction createChatBox
	//premièrement on récupére les données avec $sql puis le while de $chat, on vérifie également que le chat soit initialisé avec les $_SESSION['openChatBoxes']
	//Ensuite les données sont traités en json ou f = from et m = message
	//les données sont traités dans un tableau nommé $items
	//s = > Temps passé
	public function chatHeartbeat() {
		$this->currentUser = Session::getInstance()->read('auth');
		$sql = "select * FROM message WHERE `to` = '".$this->currentUser->slug."' AND recd = 0 order by id ASC";
		$query = $this->db->query($sql);
		$items = '';
		$chatBoxes = array();
		//fetch pour récupérer la totalité des données du chat
		while ($chat = $query->fetchAll()) {
			//vérification de l'éxistence de la chatbox
			if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
				$items = $_SESSION['chatHistory'][$chat['from']];
			}
			$chat['message'] = $this->sanitize($chat['message']);
			//on envoi dans le json les données from et message
			$items .= <<<EOD
    					   {
    			"s": "0",
    			"f": "{$chat['from']}",
    			"m": "{$chat['message']}"
    	   },
EOD;
			//on vérifi si $_SESSION['chatHistory'] est valide sinon on le retourne vide
			if (!isset($_SESSION['chatHistory'][$chat['from']])) {
				$_SESSION['chatHistory'][$chat['from']] = '';
			}
			//on implante un tableau json dans le $_SESSION['chatHistory'] avec comme valeur $chat['from'] et $chat['message'] et s = 0 (on vient d'ouvrir la fenêtre)
			$_SESSION['chatHistory'][$chat['from']] .= <<<EOD
    						   {
    			"s": "0",
    			"f": "{$chat['from']}",
    			"m": "{$chat['message']}"
    	   },
EOD;
			unset($_SESSION['tsChatBoxes'][$chat['from']]);
			$_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
		}
		//on calcul le temps écoulé entre le temps dans la bdd et NOW()
		//Puis on affiche un p'tit message qui nous dit "Sent at $time" ex "Sent at 12:20"
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
						//on vérifie si un historique du chat éxiste ou non
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
		//on update la BDD pour dire que le message a bien été lu et on le renvoi au json avec le tablea $items
		$sql = "update message set recd = 1 where message.to = '".$this->currentUser->slug."' and recd = 0";
		$query = $this->db->query($sql);
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
	//cette fonction est traité dans chat.js et permet de vérifier l'existence d'une fenêtre de chat
	public function chatBoxSession($chatbox) {
		if (isset($_SESSION['chatHistory'][$chatbox])) {
			$items = $_SESSION['chatHistory'][$chatbox];
		}
		return $items;
	}
	//fonction pour démarrer un chat en complément de chatWith
	public function startChatSession() {
		if (!empty($_SESSION['openChatBoxes']))
		{
			foreach ($_SESSION['openChatBoxes'] as $chatbox => $void)
			{
				$items .= $this->chatBoxSession($chatbox);
			}
		}
		
		if ($items != '')
		{
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
	//envoi du message et INSERT dans la BDD
	public function sendChat() {
		$this->currentUser = Session::getInstance()->read('auth');
		$from = $this->currentUser->slug;
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
		$sql = "insert into message (message.from,message.to,message,sent) values ('".$from."', '".$to."','".$message."',NOW())";
		$this->db->query($sql);
		echo "1";
		exit(0);
	}
	//On ferme le chat et destroy la chatbox
	public function closeChat() {
		unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
		echo "1";
		exit(0);
	}
}