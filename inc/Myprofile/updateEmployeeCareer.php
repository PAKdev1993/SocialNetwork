<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\Profile\Career\CareerModel;
use app\Table\Profile\Career\displayCareer;

use app\Table\Profile\Quickinfos\QuickInfosModel;

use core\Profile\ProfileEmployee;

use core\Files\Images\Images;

if(isset($_POST['companyname']) && isset($_POST['city']) && isset($_POST['country']) && isset($_POST['jobtitle']) && isset($_POST['startDate']) && isset($_POST['endDate']) && isset($_POST['currentWork']) && isset($_POST['desc']) && isset($_POST['typeaction']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $model =            new CareerModel();
    $qimodel =          new QuickInfosModel();
    $EmployeeProfile =  new ProfileEmployee();

    //create date format to insert (end date is below)
    $startdate = date("Y-m-d", strtotime($_POST['startDate']));

    /****************************************************************************\
     *                          CREATION D'UNE COMPANY                          *
    \****************************************************************************/
    if($_POST['typeaction'] == 'create') {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        $valid = true;
        //test si l'etat courant est bien celui de propriétaire du profil
        if (Session::getInstance()->read('current-state')['state'] != 'owner') {
            $valid = false;
        }
        if ($valid)
        {
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * IMAGE PART
             */
            //si l'image est definie on fais les operation dessus
            $ImgName = '';
            if (isset($_FILES['logo'])) {
                //save game image
                $images = new Images();
                $images->uploadCompanyImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            } //sinon l'image default est mise
            else {
                $ImgName = $_POST['logo'];
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * PREPARE SAVE NEW COMPANY
             */
            //definition de la current company
            if ($_POST['currentWork']) {
                //preparation avant l'ajout de la current company
                //la date de fin de la precedente current company = yesterday car le classement des equipes se fait par date de fin descendant
                $date = new DateTime();
                $date->add(DateInterval::createFromDateString('yesterday'));
                $enddatePreviousCurrentCompanie = $date->format("Y-m-d");
                $model->resetCurrentCompany($enddatePreviousCurrentCompanie);

                //la date de fin de la current companie = today
                $today = new \DateTime('today');
                $enddateNewCurrentCompany = $today->format("Y-m-d");
            }
            else{
                $enddateNewCurrentCompany = date("Y-m-d", strtotime($_POST['endDate']));
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * STATE OF QI FOR QI MODIFICATIONS
             */
            //definition du code d'etat pour l'update des quickinfos: 01: nouvelle current company, 10: plus de current company, default: on ne touche pas aux qi
            //cas ou la company etait current et ne l'est plus
            if($_POST['currentWork'])
            {
                $state = '01';
            }
            else{
                $state ='default';
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * SAVE NEW COMPANY
             */
            //save company et affichage de la vouvelle carrière
            $model->saveMyComapny($_POST['companyname'], $ImgName, $_POST['city'], $_POST['country'], $_POST['jobtitle'], $startdate, $enddateNewCurrentCompany, $_POST['desc'], $_POST['currentWork']);
            $careerCompanies = $model->getMyEmployeeCareer();
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * MAJ COMPANY DISPLAYED (SECURITY)
             */
            //update displayed array for security checks
            $idsArray = $EmployeeProfile->getArrayCompaniesIdFromDBobj($careerCompanies);
            $EmployeeProfile->updateCompaniesDisplayed($idsArray);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * NOTIFY MY NETWORK IF NESCESSARY
             */
            $typeaction = $EmployeeProfile->getProfilePart();
            $action = "action_updated_employeeprofile";
            $elemType = NULL;
            $elemid = NULL;
            $EmployeeProfile->notifyMyNetwork($typeaction, $action, $elemType, $elemid);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $displayCareer = new displayCareer(false, $careerCompanies);
            echo($displayCareer->showEmployeeCareer() . '//' . $state);
            exit();
        }
        else{
            echo('err');
            exit();
        }
    }
    /****************************************************************************\
     *                          UPDATE D'UNE COMPANY                          *
    \****************************************************************************/
    if($_POST['typeaction'] == 'update')
    {
        $elemid =  Session::getInstance()->read('current-action')['idelem'];
        $valid = true;

        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SECURE CHECK
         */
        if($elemid != $_POST['companyid'])
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-state')['state'] != 'owner')
        {
            $valid = false;
        }
        if(Session::getInstance()->read('current-action')['actionname'] != 'editingCompany')
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
            $ImgName = '';
            if (isset($_FILES['logo'])) {
                //save company image
                $images = new Images();
                $images->uploadCompanyImages();

                //put logo if logo exist or put default logo
                $ImgName = empty($images->formatFileName($_FILES['logo']['name'])) ? 'default' : $images->formatFileName($_FILES['logo']['name']);
            }
            else {
                $ImgName = 'didnttouch';
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * PREPARE UPDATE COMPANY
             */
            //definition de la current company
            if ($_POST['currentWork'])
            {
                //preparation avant l'ajout de la current company
                //la date de fin de la precedente current company = yesterday car le classement des equipes se fait par date de fin descendant
                $date = new DateTime();
                $date->add(DateInterval::createFromDateString('yesterday'));
                $enddatePreviousCurrentCompanie = $date->format("Y-m-d");
                $model->resetCurrentCompany($enddatePreviousCurrentCompanie);

                //la date de fin de la current companie = today
                $today = new \DateTime('today');
                $enddateNewCurrentCompany = $today->format("Y-m-d");
            }
            else{
                $enddateNewCurrentCompany = date("Y-m-d", strtotime($_POST['endDate']));
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * STATE OF QI FOR QI MODIFICATIONS
             */
            //definition du code d'etat pour l'update des quickinfos: 01: nouvelle current company, 10: plus de current company, default: on ne touche pas aux qi
            //cas ou la company etait current et ne l'est plus
            if($model->isCurrentCompany($elemid))
            {
                if(!$_POST['currentWork'])
                {
                    $state = '10';
                }
                else{
                    $state ='default';
                }
            }
            else{
                if($_POST['currentWork'])
                {
                    $state = '01';
                }
                else{
                    $state ='default';
                }
            }
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * UPDATE COMPANY
             */
            //save company et affichage de la vouvelle carrière
            $model->updateMyCompany($elemid, $_POST['companyname'], $ImgName, $_POST['city'], $_POST['country'], $_POST['jobtitle'], $startdate, $enddateNewCurrentCompany, $_POST['desc'], $_POST['currentWork']);
            //--------------------------------------------------------------------------------------------------------------------
            /*
             * DISPLAY
             */
            $careerCompanies = $model->getMyEmployeeCareer();
            $displayCareer = new displayCareer(false, $careerCompanies);
            echo($displayCareer->showEmployeeCareer(). '//' . $state); //#todo trouver une autre solution que le // pour envoyer
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