<?php

namespace core\Date;

class Date
{
    private $currentMonthm;

    public function __construct()
    {
        $this->currentMonthm    = date('m'); //avec zero initiaux
        $this->currentMonthn    = date('n'); //sans zero initiaux
        $this->currentYear      = date('Y'); //année sur 4 chiffres
        $this->timezone         = date_default_timezone_get();
    }


    /**
     * @param $date: date a partir de laquelle one recule format date('m Y')
     * @param $range nombre de mois a reculer
     * @return tableau de ['month']['year'] de $range entrées, correspondant aux date moi/year precedent la date fournie ne entrée
     */
    public function getPreviousMYarray($date, $range) //#todo simplifier ca c'est une horreur
    {
        $tmp = explode(' ', $date);
        $monthNum   = intval($tmp[0]);
        $yearNum    = intval($tmp[1]);

        //premier element du tableau associatif
        $fmonthNum = $monthNum;
        if(strlen(strval($monthNum)) < 2)
        {
            $fmonthNum = '0'. $monthNum;
        }
        $datesArray[0]['month'] = strval($fmonthNum);
        $datesArray[0]['year']  = strval($yearNum);

        //crear le reste du tableua associatif
        for($i = 1; $i < $range; $i++)
        {
            //moi precedent
            $monthNum = $monthNum - 1;

            //si le moi précédent est < 0 alor changement d'année
            if($monthNum <= 0)
            {
                //année en cours prend -1
                $yearNum    = $yearNum - 1;
                //moi prend 12
                $monthNum   = 12;
            }
            //si le numero du mois en cours est a un chiffre on ajoute le zero devant
            $month = strval($monthNum);
            if(strlen(strval($monthNum)) < 2)
            {
                $month = '0'. $month;
            }
            $year  = strval($yearNum);
            $datesArray[$i]['month'] = $month;
            $datesArray[$i]['year']  = $year;
        }
        return $datesArray;
    }

}