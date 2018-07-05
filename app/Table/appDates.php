<?php
/**
 * Created by PhpStorm.
 * User: PAK
 * Date: 24/01/2017
 * Time: 20:17
 */

namespace app\Table;

use app\App;
use core\Session\Session;

class appDates
{
    private $date;
    private $dateFormat; //'dateSQL'
    private $todisplay;

    public function __construct($date = false, $dateFormat = 'dateSQL', $todisplay = 'normal')
    {
        $this->date         = $date;
        $this->dateFormat   = $dateFormat;
        $this->todisplay    = $todisplay;

        $this->session      = Session::getInstance();
        $this->langModel    = App::getLangModel();

        //on ecrit le tableau de langue en session si nescessaire
        $langFileName = 'dates';
        $this->session->writeLangFileArray($langFileName, $this->langModel->getPageLangFile($langFileName));
        //on get le tableau de langue
        $this->langDatesArray = $this->session->readLangFile($langFileName);
    }

    public function getDate()
    {
        if($this->dateFormat == 'dateSQL')
        {
            $dateElem = strtotime($this->date);
            $currentDate = strtotime(date("Y-m-d H:i:s"));

            //year(s) ago
            $yearElem   = intval(date('Y', $dateElem));
            $year       = intval(date('Y', $currentDate));
            $diffYear   = $year - $yearElem;
            if ($diffYear > 0)
            {
                $date = $this->getDateTracuded('different year');
                return $date;
            }
            else {
                //month(s) ago
                $monthElem  = intval(date('m', $dateElem));
                $month      = intval(date('m', $currentDate));
                $diffMonth  = $month - $monthElem;
                if ($diffMonth > 0)
                {
                    $date = $this->getDateTracuded('different month');
                    return $date;
                }
                else {
                    //day(s) ago
                    $dayElem    = intval(date('d', $dateElem));
                    $day        = intval(date('d', $currentDate));
                    $diffDays   = $day - $dayElem;
                    if ($diffDays > 0)
                    {
                        //yesterday
                        if ($diffDays == 1)
                        {
                            $date = $this->getDateTracuded('yesterday');
                            return $date;
                        }
                        else{
                            //same week
                            if($this->isSameWeek())
                            {
                                $date = $this->getDateTracuded('same week');
                                return $date;
                            }
                            //not same week
                            else
                            {
                                $date = $this->getDateTracuded('normal');
                                return $date;
                            }
                        }

                    }
                    else {
                        //same day
                        if($this->todisplay == 'normal')
                        {
                            $date = $this->getDateTracuded('today normal');
                            return $date;
                        }
                        if($this->todisplay == 'messageChat')
                        {
                            $date = $this->getDateTracuded('today without word');
                            return $date;
                        }
                    }
                }
            }
        }
    }

    public function getDateTracuded($typeDate)
    {
        $dateElem = strtotime($this->date);
        //date elems
        $day    = '';
        $month  = '';
        $year   = '';
        $hour   = date('H:i', $dateElem);
        $dateFinal   = '';

        //define elems, function of typeDate
        switch($typeDate){
            case 'different year':
                $day    = date('d', $dateElem) . ' ';
                $month  = $this->getMonthTraduced(intval(date('m', $dateElem))) . ' ';
                $year   = intval(date('Y', $dateElem)) . ' ';
                break;
            case 'different month':
                $day    = date('d', $dateElem) . ' ';
                $month  = $this->getMonthTraduced(intval(date('m', $dateElem))) . ' ';
                break;
            case 'yesterday':
                $day    = $this->langDatesArray->yesterday . ' ';
                break;
            case 'same week':
                $day    = $this->getDay() . ' ';
                break;
            case 'normal':
                $day    = date('d', $dateElem) . ' ';
                $month  = $this->getMonthTraduced(intval(date('m', $dateElem))) . ' ';
                break;
            case 'today normal':
                $day    = $this->langDatesArray->today . ' ';
                break;
        }

        //put elem in right order function of nationnaloity of current user
        if($this->session->read('auth')->langWebsite == 'en')
        {
            $dateFinal = $month . $day . $year . $hour;
        }
        else{
            $dateFinal = $day . $month . $year . $hour;
        }

        return $dateFinal;
    }

    public function getMonthTraduced($nbMonth)
    {
        if($this->dateFormat == 'dateSQL')
        {
            $arrayMonthTraduced = explode('/', $this->langDatesArray->months);
            $index = $nbMonth - 1;
            return $arrayMonthTraduced[$index];
        }
    }

    public function getDay()
    {
        if($this->dateFormat == 'dateSQL')
        {
            $date           = date_create($this->date);
            $dayInEnglish   = date_format($date, 'l');
            $index = '';
            switch($dayInEnglish){
                case 'Monday':
                    $index = 0;
                    break;
                case 'Tuesday':
                    $index = 1;
                    break;
                case 'Wednesday':
                    $index = 2;
                    break;
                case 'Thursday':
                    $index = 3;
                    break;
                case 'Friday':
                    $index = 4;
                    break;
                case 'Saturday':
                    $index = 5;
                    break;
                case 'Sunday':
                    $index = 6;
                    break;
            }
            $arrayDayTraduced = explode('/', $this->langDatesArray->days);
            return $arrayDayTraduced[$index];
        }
    }

    public function isSameWeek()
    {
        $currentDate = date("Y-m-d H:i:s");
        $dayElem    = intval(date('d', strtotime($this->date)));
        $day        = intval(date('d', strtotime($currentDate)));
        $diffDays   = $day - $dayElem;
        if($diffDays > 7)
        {
            return false;
        }
        else{
            //if not, get current day index
            $dayInEnglish   = date_format(date_create($currentDate), 'l');
            $dayNumber = '';
            switch($dayInEnglish){
                case 'Monday':
                    $dayNumber = 1;
                    break;
                case 'Tuesday':
                    $dayNumber = 2;
                    break;
                case 'Wednesday':
                    $dayNumber = 3;
                    break;
                case 'Thursday':
                    $dayNumber = 4;
                    break;
                case 'Friday':
                    $dayNumber = 5;
                    break;
                case 'Saturday':
                    $dayNumber = 6;
                    break;
                case 'Sunday':
                    $dayNumber = 7;
                    break;
            }
            if($diffDays < $dayNumber)
            {
                return true;
            }
            else{
                return false;
            }
        }
    }
}