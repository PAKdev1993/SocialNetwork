<?php
use inc\Autoloader;

use core\Session\Session;

use app\Table\Profile\Games\GamesModel;
use app\Table\Profile\Games\displayGames;

use core\Files\Images\Images;

use core\Profile\ProfileGamer;

if(isset($_POST['gamename']) && isset($_POST['gameaccount']) && isset($_POST['platform']) && isset($_POST['typeaction']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $model = new GamesModel();
    $GamerProfile = new ProfileGamer();

    /****************************************************************************\
     *                          CREATION D'UN GAME                              *
    \****************************************************************************/
    if($_POST['typeaction'] == 'create')
    {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        $valid = true;
        if(Session::getInstance()->read('current-state')['state'] != 'owner')
        {
            $valid = false;
        }
        if($valid)
        {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            //save game image
            $images = new Images();
            $images->uploadGameImages();
            $newImgName = $images->formatFileName($_FILES['logo']['name']);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * SAVE NEW GAME
             */
            $content = '';
            $model->saveMyGames($_POST['gamename'], $_POST['gameaccount'], $newImgName, $_POST['platform']);
            $games = $model->getMyGames();
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * MAJ GAMES DISPLAYD (SECURITY)
             */
            //update displayed array for security checks
            $idsArray = $GamerProfile->getArrayGamesIdFromDBobj($games);
            $GamerProfile->updateGamesDisplayed($idsArray);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * NOTIFY MY NETWORK IF NESCESSARY
             */
            $typeaction = $GamerProfile->getProfilePart();
            $action = "action_updated_gamerprofile";
            $elemType = NULL;
            $elemid = NULL;
            $GamerProfile->notifyMyNetwork($typeaction, $action, $elemType, $elemid);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $displayEvents = new displayGames($games);
            echo($displayEvents->show());
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    /****************************************************************************\
     *                          UPDATE D'UN GAME                                *
    \****************************************************************************/
    if($_POST['typeaction'] == 'update')
    {
        $elemid =  Session::getInstance()->read('current-action')['idelem'];
        $valid = true;

        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        if($elemid != $_POST['gameid'])
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-state')['state'] != 'owner')
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-action')['actionname'] != 'editingGame')
        {
            $valid = false;
        }
        if($valid)
        {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            if(isset($_FILES['logo']))
            {
                //save game image
                $images = new Images();
                $images->uploadGameImages();
                $newImgName = $images->formatFileName($_FILES['logo']['name']);
            }
            else{
                $newImgName = 'didnttouch';
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * SAVE NEW GAME
             */
            $model->updateMyGames($elemid, $_POST['gamename'], $newImgName, $_POST['platform'], $_POST['gameaccount']);
            $games = $model->getMyGames();
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $displayEvents = new displayGames($games);
            echo($displayEvents->show());
            exit();
        }
        else{
            echo('err1');
            exit();
        }
    }
}
else{
    echo('err2');
    exit();
}