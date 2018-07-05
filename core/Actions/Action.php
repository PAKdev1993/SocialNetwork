<?php

namespace core\Actions;


class Action
{
    //#todo factoriser ca
    //fonction appellée par core\Posts et inc/.../modifspost
    public function definePostAction($imgString, $liveAction = false)
    {
        $actionName = '';

        if(!$imgString && !$liveAction)
        {
            $actionName = 'action_published_post';
            return $actionName;
        }
        if($liveAction)
        {
            $actionName = 'action_published_live';
            return $actionName;
        }
        else{
            $arrayImagsNames = explode('/', $imgString);
            $arrayImgsSize = count($arrayImagsNames);
            if($arrayImgsSize == 1)
            {
                $actionName = 'action_published_picture';
            }
            if($arrayImgsSize > 1)
            {
                $actionName = 'action_published_pictures';
            }
            return $actionName;
        }
    }
}