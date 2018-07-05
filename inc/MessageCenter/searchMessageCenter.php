<?php
use inc\Autoloader;
use core\MessageCenter\Message;
use core\Tables\Search\Search;
use app\Displays\SearchMessagerie\displayMessagerieSearchResults;
use app\Table\Messages\Conversations\ConversationModel;

require_once '../Autoloader.php';
Autoloader::register();

if(isset($_POST['tosearch']))
{
    //secure test
    $model = new ConversationModel();
    $stringToSearch = $_POST['tosearch'];

    //prepare search
    $filters =  array(
        array(
            'filterName' => 'convs'
        )
    );

    $params =       array();
    $from =         'messageCenter';
    $begin =        0;
    $nbResults =    15;

    //search results
    $coreSearch =       new Search($filters, $stringToSearch, $from, $begin, $nbResults);
    //get search result list to display
    $resultList =   $coreSearch->search($params);

    //display
    if($resultList)
    {
        $display = new displayMessagerieSearchResults($resultList, 'convs');
        echo($display->show());
        exit();
    }
}
else{
    echo('err');
    exit();
};