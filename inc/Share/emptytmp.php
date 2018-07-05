<?php
use inc\Autoloader;
use core\Tmp\Tmp;

require_once '../Autoloader.php';
Autoloader::register();

$tmp = new Tmp();
$tmp->deleteTmpFolder();