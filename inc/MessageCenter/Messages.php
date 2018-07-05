<?php
use inc\Autoloader;
use core\MessageCenter\Message;

require_once '../Autoloader.php';
Autoloader::register();

$chat = new Message();

if (isset($_GET['action']) && $_GET['action'] == "startchatsession")
{
    echo($chat->startChatSession());
}

if (!$session->read('chatHistory')) {
    $session->write('chatHistory', array());
}

if (!$session->read('openChatBoxes')) {
    $session->write('openChatBoxes', array());
}