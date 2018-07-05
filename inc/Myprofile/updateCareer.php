<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\Profile\Career\CareerModel;
use app\Table\Profile\Career\displayCareer;
use core\Profile\ProfileGamer;
use app\Table\Profile\Quickinfos\QuickInfosModel;

use core\Files\Images\Images;

//#todo $_FILES['logo'], $_POST['teamid'] et$_POST['logo'] peux etre passé en paramètres mais pas de controles dessus
if(isset($_POST['teamname']) && isset($_POST['game']) && isset($_POST['plateform']) && isset($_POST['role']) && isset($_POST['startDate']) && isset($_POST['endDate']) && isset($_POST['currentTeam']) && isset($_POST['desc']) && isset($_POST['typeaction']))
{
    require_once '../Autoloader.php';
    Autoloader::register();
    
    $model = new CareerModel();
    $GamerProfile = new ProfileGamer();
    
    //create date format to insert (end date is below)
    $startdate = date("Y-m-d", strtotime($_POST['startDate']));

    /****************************************************************************\
     *                          CREATION D'UNE TEAM                             *
    \****************************************************************************/
    if($_POST['typeaction'] == 'create')
    {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        $valid = true;
        //test si l'etat courant est bien celui de propriétaire du profil
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
            //si l'image est definie on fais les operation dessus
            $ImgName = '';
            if(isset($_FILES['logo'])){
                //save game image
                $images = new Images();
                $images->uploadTeamImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            }
            //sinon l'image default est mise
            else{
                $ImgName = $_POST['logo'];
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * PREPARE SAVE NEW TEAM
             */
            //definition de la current team et mise a jour des quick infos
            if($_POST['currentTeam'])
            {
                //preparation avant l'ajout de la current team
                //la date de fin de la precedente current team = yesterday car le classement des equipes se fait par date de fin descendant
                $date = new DateTime();
                $date->add(DateInterval::createFromDateString('yesterday'));
                $enddatePreviousCurrentTeam = $date->format("Y-m-d");
                $model->resetCurrentTeam($enddatePreviousCurrentTeam);

                //la date de fin de la current team = today
                $today = new \DateTime('today');
                $enddateNewCurrentTeam = $today->format("Y-m-d");
            }
            else{
                $enddateNewCurrentTeam = date("Y-m-d", strtotime($_POST['endDate']));
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * STATE OF QI FOR QI MODIFICATIONS
             */
            //definition du code d'etat pour l'update des quickinfos: 01: nouvelle current team, default: on ne touche pas aux qi
            //cas ou la team ajouté est current
            if($_POST['currentTeam'])
            {
                $state = '01';
            }
            else{
                $state ='default';
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * SAVE NEW TEAM
             */
            //save team et affichage de la vouvelle carrière
            $model->saveMyTeam($_POST['teamname'], $ImgName, $_POST['game'], $_POST['role'], $startdate, $enddateNewCurrentTeam, $_POST['plateform'], $_POST['desc'], $_POST['currentTeam']);
            $careerTeams = $model->getMyGamerCareer();
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * MAJ TEAM DISPLAYD (SECURITY)
             */
            //update displayed array for security checks
            $idsArray = $GamerProfile->getArrayTeamsIdFromDBobj($careerTeams);
            $GamerProfile->updateTeamsDisplayed($idsArray);
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
            //display
            $displayCareer = new displayCareer($careerTeams, false);
            echo($displayCareer->showGamerCareer() . '//' . $state);
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    /****************************************************************************\
     *                          UPDATE D'UNE TEAM                               *
    \****************************************************************************/
    if($_POST['typeaction'] == 'update')
    {
        $elemid =  Session::getInstance()->read('current-action')['idelem'];
        $valid = true;

        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        if($elemid != $_POST['teamid'])
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-state')['state'] != 'owner')
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-action')['actionname'] != 'editingTeam')
        {
            $valid = false;
        }
        if ($valid)
        {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            //si l'image est definie on fais les operation dessus
            if(isset($_FILES['logo'])){
                //save team image
                $images = new Images();
                $images->uploadTeamImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            }
            else{
                $ImgName = 'didnttouch';
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*à&é
             * PREPARE UPDATE TEAM
             */
            //traitement liés a la current team
            if($_POST['currentTeam'])
            {
                //preparation avant l'ajout de la current team
                //la date de fin de la precedente current team = yesterday car le classement des equipes se fait par date de fin descendant
                $date = new DateTime();
                $date->add(DateInterval::createFromDateString('yesterday'));
                $enddatePreviousCurrentTeam = $date->format("Y-m-d");
                $model->resetCurrentTeam($enddatePreviousCurrentTeam);

                //la date de fin de la current team = today
                $today = new \DateTime('today');
                $enddateNewCurrentTeam = $today->format("Y-m-d");
            }
            else{
                $enddateNewCurrentTeam = date("Y-m-d", strtotime($_POST['endDate']));
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * STATE OF QI FOR QI MODIFICATIONS
             */
            //definition du code d'etat pour l'update des quickinfos: 01: nouvelle current company, 10: plus de current company, default: on ne touche pas aux qi
            //cas ou la company etait current et ne l'est plus
            if($model->isCurrentteam($elemid))
            {
                if(!$_POST['currentTeam'])
                {
                    $state = '10';
                }
                else{
                    $state ='default';
                }
            }
            else{
                if($_POST['currentTeam'])
                {
                    $state = '01';
                }
                else{
                    $state ='default';
                }
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * UPDATE TEAM
             */
            //save team
            $model->updateMyTeam($elemid, $_POST['teamname'], $ImgName, $_POST['game'], $_POST['role'], $startdate, $enddateNewCurrentTeam, $_POST['plateform'], $_POST['desc'], $_POST['currentTeam']);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $teams = $model->getMyGamerCareer();
            $displayCareer = new displayCareer($teams, false);
            echo($displayCareer->showGamerCareer() . '//' . $state); //#todo trouver une autre solution que le // pour envoyer
            exit();
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
}