<?php

namespace core\Profile;

use app\Table\Profile\Quickinfos\QuickInfosModel;
use app\Table\Profile\Quickinfos\displayQuickinfos;

use app\Table\Profile\Career\CareerModel;
use app\Table\Profile\Career\displayCareer;

use app\Table\Profile\Events\EventModel;
use app\Table\Profile\Events\displayEvents;

use app\Table\Images\ImagesModel;
use app\Table\Images\AlbumPreview\displayAlbumPreview;

use app\Table\UserModel\displayUser;

class ProfileEmployee extends AppProfile
{
    private $profilePart;

    public function __construct($user = false)
    {
        parent::__construct($user);
        $this->profilePart = 'employee';
    }

    //#todo renommer les fonction afi nde bien distinguer l'affichage des données ME concernant et celles concernant l'user que l'on est en train d'afficher
    /****************************************************************************\
     *                          MY PROFILE PART                                 *
    \****************************************************************************/
    public function getProfilePart()
    {
        return $this->profilePart;
    }

    public function getMyQuickInfos()
    {
        $model = new QuickInfosModel();
        $quickinfosObj = $model->getMyQuickInfos();
        $display = new displayQuickinfos($quickinfosObj, $this->profilePart);
        return $display->show();
    }

    public function getMyCareer()
    {

        $model = new CareerModel();
        $companyObj = $model->getMyEmployeeCareer();
        $display = new displayCareer(false, $companyObj);
        $careerContent =  $display->showEmployeeCareer();

        //write ids dans session pour les controle d'edition
        $idsArray = $this->getArrayCompaniesIdFromDBobj($companyObj);
        $this->updateCompaniesDisplayed($idsArray);

        //retour
        return $careerContent;
    }

    public function getMyEvents()
    {
        $model = new EventModel();
        $eventsObj = $model->getMyEmployeeEvents();
        $display = new displayEvents(false, $eventsObj);
        $eventContent = $display->showEmployeeEvents();

        //write ids dans session pour les controle d'edition
        $idsArray = $this->getArrayEmployeeEventsIdFromDBobj($eventsObj);
        $this->updateEmployeeEventsDisplayed($idsArray);

        //retour
        return $eventContent;
    }

    public function getMySummary()
    {
        $display = new displayUser($this->myself, 'employee.summary');
        return $display->show();
    }

    public function getMyGaleryPreview()
    {
        //get photos
        $model = new ImagesModel();
        $imgsToPreview = $model->getMyAlbumPreview();
        $display = new displayAlbumPreview($imgsToPreview);

        //retour
        return $display->show();
    }

    //get l'array de companies displayed pour l'ajouter a la session via "this->writeGamerProfileElemsIdInSession"
    public function getArrayCompaniesIdFromDBobj($companyObj)
    {
        $companiesIds = [];
        foreach($companyObj as $company)
        {
            array_push($companiesIds, $company->pk_idcompanycareer);
        }
        return $companiesIds;
    }

    public function getArrayEmployeeEventsIdFromDBobj($eventsObj)
    {
        $eventsIds = [];
        foreach($eventsObj as $eventObj)
        {
            array_push($eventsIds, $eventObj->pk_ideventcareer);
        }
        return $eventsIds;
    }

    public function updateCompaniesDisplayed($idsArray)
    {
        //write ids dans session pour les controle d'edition
        $this->session->write('CompaniesDisplayed', $idsArray);
    }

    public function updateEmployeeEventsDisplayed($idsArray)
    {
        //write ids dans session pour les controle d'edition
        $this->session->write('EmpEventsDisplayed', $idsArray);
    }

    public function deleteFromCompaniesDisplayed($compid)
    {
        $idArray = $this->session->read('CompaniesDisplayed');
        $indexValue = array_search($compid, $idArray);
        unset($idArray[$indexValue]);
        $this->session->write('CompaniesDisplayed', $idArray);
    }

    public function deleteFromEmpEventsDisplayed($eventid)
    {
        $idArray = $this->session->read('EmpEventsDisplayed');
        $indexValue = array_search($eventid, $idArray);
        unset($idArray[$indexValue]);
        $this->session->write('EmpEventsDisplayed', $idArray);
    }
    /****************************************************************************\
     *                          SOMEONE ELESE PART                              *
    \****************************************************************************/
    public function getUserQuickInfos()
    {
        $model = new QuickInfosModel();
        $quickinfosObj = $model->getQuickInfosFromIdUser($this->userToDisplay->pk_iduser); //#todo, encore une requette de plus qui aurai peux etre été résolue par un joi nde l'user sur ses quickinfos, a voir a ce niveau la pour optimiser
        $display = new displayQuickinfos($quickinfosObj, $this->profilePart);
        return $display->show();
    }

    public function getUserSummary()
    {
        $display = new displayUser($this->userToDisplay, 'employee.summary');
        return $display->show();
    }

    public function getUserEmployeeCareer()
    {
        $model = new CareerModel();
        $companyObj = $model->getUserEmployeeCareerFromIduser($this->userToDisplay->pk_iduser);
        $display = new displayCareer(false, $companyObj, $this->userToDisplay);
        $careerContent =  $display->showEmployeeCareer();

        //write ids dans session pour les controle d'edition
        //$idsArray = $this->getArrayCompaniesIdFromDBobj($companyObj);
        //$this->updateCompaniesDisplayed($idsArray);

        //retour
        return $careerContent;
    }

    public function getUserEmployeeEvents()
    {
        $model = new EventModel();
        $eventsObj = $model->getUserEmployeeEventsFromIduser($this->userToDisplay->pk_iduser);
        $display = new displayEvents(false, $eventsObj, $this->userToDisplay);
        $eventContent = $display->showEmployeeEvents();

        //write ids dans session pour les controle d'edition
        //$idsArray = $this->getArrayEmployeeEventsIdFromDBobj($eventsObj);
        //$this->updateEmployeeEventsDisplayed($idsArray);

        //retour
        return $eventContent;
    }
}