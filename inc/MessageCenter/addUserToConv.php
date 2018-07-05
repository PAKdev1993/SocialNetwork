<?php
use inc\Autoloader;
use app\Table\Messages\Conversations\ConversationModel;
use core\Session\Session;
use app\Table\Messages\Conversations\Conversation\displayConversation;

if(isset($_POST['idConv']) && isset($_POST['idUsersToAdd']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $model =        new ConversationModel();
    $idconv =       $_POST['idConv'];
    if($model->isMyConv($idconv))
    {
        $idUsersToAdd = explode(',', $_POST['idUsersToAdd']);
        if(!empty($idUsersToAdd))
        {
            $model->addUserToConv($idUsersToAdd, $idconv, false, true);
            $typeConv = $model->getTypeConv($idconv);
            $from = $_POST['from'];
            if($from == 'MessageCenter')
            {
                //get apercu conv
                $conv = $model->getOnlyConversationFromId($idconv);
                $displayApDiscussion = new displayConversation($conv, 'apercuDiscutions', true);

                //get title of conv
                $picUser =  $displayApDiscussion->getPicUsers();
                $title =    $displayApDiscussion->getTitleConv();

                //get activated state
                $activated = $model->isConvIsActivated($idconv);
                
                $result['apConvHtml'] =     $displayApDiscussion->show();
                $result['convActivated'] =  $activated;
                $result['picUsers'] =       $picUser;
                $result['titleConv'] =      $title;
                echo(json_encode($result));
                exit();
            }
            else{
                if($typeConv == 'userTouser')
                {
                    $title = $model->getTitleForUsertoUserConv($idconv);
                }
                else{
                    $idusersInConv = $model->getIdParticipants($idconv);
                    $title = $model->getTitleForGroupedConv($idconv, $idusersInConv);
                }
                $activated = $model->isConvIsActivated($idconv);
                $result['convActivated'] = $activated;
                $result['titleConv'] = $title;
                echo(json_encode($result));
                exit();
            }

        }
        else{
            echo('noUser');
            exit();
        }
    }
    else{
        echo('errIsMyConv');
        exit();
    }
}
else{
    echo('errPost');
    exit();
}