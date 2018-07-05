<?php
use inc\Autoloader;
use core\MessageCenter\Message;
use core\Tables\Search\Search;
use app\Displays\SearchMessagerie\displayMessagerieSearchResults;
use app\Table\Messages\Conversations\ConversationModel;

require_once '../Autoloader.php';
Autoloader::register();

if(isset($_POST['tosearch']) && isset($_POST['convId']) && isset($_POST['userAdedToExclude']) && isset($_POST['from']))
{
    //secure test
    $model =    new ConversationModel();
    $convid =   $_POST['convId'];

    if($model->isMyConv($convid))
    {
        $stringToSearch = $_POST['tosearch'];
        $userAlrdyAdded = explode(',', $_POST['userAdedToExclude']);

        //get type conv to define wich kind of display wee need
        $typeConv = $model->getTypeConv($convid);
        
        //si la conv est une empty conv alors on display conv et users en resultat
        if($typeConv == "emptyConv")
        {
            //prepare search
            $filters =  array(
                            array(
                                'filterName' => 'friends',
                                'idToExlude' => $userAlrdyAdded
                            ),
                            array(
                                'filterName' => 'convs',
                                'idToExlude' => $convid
                            )
                        );

            $params =   array(
                'convid' => $convid
            );

            //define nb result function of 'from'
            $from = $_POST['from'];
            if($from == 'chatBox')
            {
                $begin =            0;
                $nbResults =        5;
            }
            else if($from == 'MessageCenter')
            {
                $begin =            0;
                $nbResults =        10;
            }

            //search results
            $coreSearch = new Search($filters, $stringToSearch, $from, $begin, $nbResults);
            
            //get search result list to display
            $resultList = $coreSearch->search($params);
            
            //display
            if($resultList)
            {
                $display = new displayMessagerieSearchResults($resultList, 'friendsandconvs');
                echo($display->show());
                exit();
            }
            
        }
        //on display just des users
        else{
            //prepare search
            $filters =  array(
                            array(
                                'filterName' => 'friends',
                                'idToExlude' => $userAlrdyAdded
                            )
                        );
            
            $params =   array(
                            'convid' => $convid
                        );

            //define nb result function of 'from'
            $from = $_POST['from'];
            if($from == 'chatBox')
            {
                $begin =            0;
                $nbResults =        5;
            }
            else if($from == 'MessageCenter')
            {
                $begin =            0;
                $nbResults =        10;
            }

            //search friends
            $coreSearch =       new Search($filters, $stringToSearch, $from, $begin, $nbResults);
            //get search result list to display
            $friendList =   $coreSearch->search($params);

            //display
            if($friendList)
            {
                $display = new displayMessagerieSearchResults($friendList, 'friends');
                echo($display->show());
                exit();
            }
            else{
                //echo empty result (doesnt exist yet)
            }
        }        
    }
    else{
        echo('err');
        exit();
    }
}
else{
    echo('err');
    exit();
};