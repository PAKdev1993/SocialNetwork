<?php
use inc\Autoloader;

use core\Tables\Search\Search;
use app\Displays\SearchPage\displaySearchResults;

if(isset($_POST['strinToSearch']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //init search parameters
    $filters =      array(
                        array(
                            'filterName' => 'users',
                            'idToExlude' => array()
                        )
                    );
    $from =         'SearchBarHeader';
    $begin =        0;
    $nbResults =    7;
    $coreSearch =   new Search($filters, $_POST['strinToSearch'], $from, $begin, $nbResults);
    
    //display search
    $results = $coreSearch->search();
    $display = new displaySearchResults('searchBar', $results);
    echo($display->show());
    exit();
}
else{
    echo('err');
    exit();
}