<?php

namespace app\Table;

class AppForms
{
    public function __construct()
    {

    }

    public function selectYearValues()
    {
        $values = '';
        $date = date("Y");
        $nbYearToDisplay = 120;
        for($i = 0; $i < $nbYearToDisplay; $i++)
        {
            $dateToDisplay = $date - $i;
            $values .= '<option value="'. $dateToDisplay .'">'. $dateToDisplay .'</option>';
        }

        return $values;
    }
}