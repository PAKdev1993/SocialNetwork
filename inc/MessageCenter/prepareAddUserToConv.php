<?php
use inc\Autoloader;
use core\MessageCenter\Message;
use app\Table\Messages\Conversations\ConversationModel;
use app\Table\Messages\Conversations\Conversation\displayConversation;

require_once '../Autoloader.php';
Autoloader::register();

if(isset($_POST['idUsersToAdd']))
{
    $model =        new ConversationModel();
    $idconv =       $_POST['idConv'];
    if($model->isMyConv($idconv))
    {
        $userToAdd =    explode(',', $_POST['idUsersToAdd']);
        if(!empty($userToAdd))
        {
            $result = $model->prepareAddUsersToConversation($userToAdd, $idconv);
            if($result)
            {
                $conv = $result['conv'];
                $from = $_POST['from'];
                //si l'addUser provoque la création d'une nouvelle conv, $conv existe
                if($conv)
                {
                    if($result['state'] == 'convExist')
                    {
                        if($from == 'MessageCenter')
                        {
                            $displayDiscussion =        new displayConversation($conv, 'discussion', true);

                            $result['convHtml'] =       $displayDiscussion->show();
                            echo(json_encode($result));
                            exit();
                        }
                        else if($from == "chatBox")
                        {
                            $display = new displayConversation($conv, 'chatBox', true);

                            $result['convHtml'] = $display->show();
                            echo(json_encode($result));
                            exit();
                        }
                    }
                    else{
                        if($from == 'MessageCenter')
                        {
                            $displayDiscussion =        new displayConversation($conv, 'discussion', false);

                            $result['convHtml'] =       $displayDiscussion->show();
                        }
                        else if($from == "chatBox")
                        {
                            //le parametre $generateTitle est a false car a ce moment la les users n'ont pas encoré été ajouté a la conv pour acelerer l'affichage et le titre est généré après
                            $display = new displayConversation($conv, 'chatBox', false);

                            $result['convHtml'] = $display->show();
                        }
                    }
                    echo(json_encode($result));
                    exit();
                }
                //sinon c'est que l'on modifiue une conv de groupe et on renvoi le text de confirmation
                else{
                    echo(json_encode($result));
                    exit();
                }
            }
            else{
                echo('errAdd');
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