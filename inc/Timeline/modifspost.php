<?php
use inc\Autoloader;

use app\Table\Posts\PostModel;
use app\Table\Posts\Home\displayPost;
use app\Table\Images\ImagesModel;

use core\Session\Session;
use core\Timeline\Posts;
use core\Actions\Action;
use core\Files\Images\Images;

if(isset($_POST['newtext'])&& isset($_POST['newPreview']) && isset($_POST['newimgstring']) && isset($_POST['datas']))
{
    //si on est ici c'est que le text a forcément été modifié
    require_once '../Autoloader.php';
    Autoloader::register();
    
    $datasArray =   json_decode($_POST['datas']);
    $postid =       $datasArray->wepid;
    $newtext =      $_POST['newtext'];
    $newimgstring = $_POST['newimgstring'];
    $newPreview =   $_POST['newPreview'];
    $actionName =   '';
    $currentUser =  Session::getInstance()->read('auth');

    $html = '';

    $model = new PostModel();
    $imgModel = new ImagesModel();

    //on veridfie que le post que l'user essaye de modifier fait bien partis des posts affichés 
    if(Session::getInstance()->checkValueInSession($postid, 'PostDisplayed'))
    {
        //conbtrole que l'auteur est bien l'user courant
        if($model->getPostAuthor($postid) == $currentUser->pk_iduser)
        {
            //sert pour le controle de l'action en cours au cas ou quelqu'un s'amuserai a mettre post-text-active dans le html pout activer le mode edition
            if(Session::getInstance()->read('current-action')['actionname'] == 'editingPost' && Session::getInstance()->read('current-action')['idelem'] == $postid)
            {
                //on genère l'object post précédent les modifications pour permettre l'affichage du nouveau post-body
                $model = new PostModel();
                $postDB = $model->getPostFromid($postid);
                if($postDB)
                {
                    //si les images ont été modifiées, redefinition de l'action
                    if($newimgstring != 'noNeedImgUpdate')
                    {
                        //delete des images de la BD ET du serveur
                        $imgModel->deletePostImagesFromPostidAndNewImgString($postid, $newimgstring);

                        //redefinitions des nouveaus paramètres du post en fonction des données
                        $action = new Action();
                        $actionName = $action->definePostAction($newimgstring);
                        $postDB->action =           $actionName;
                        $postDB->images =           $newimgstring;
                        $postDB->texte =            $newtext;
                        $postDB->previewContent =   $newPreview;

                        //display du CORP du nouveau post
                        $display = new displayPost($postDB, $currentUser);
                        $html = $display->showPostHeader() . $display->showPostBody();

                        //edition du post
                        $post = new Posts();
                        $post->editPost($postid, $newtext, $newPreview, $newimgstring);
                    }
                    //les images n'ont pas été modifiées
                    else{
                        //redefinitions des nouveaus paramètres du post en fonction des données
                        $action = new Action();
                        $actionName = $action->definePostAction($imgString = false);
                        $postDB->action =           $actionName;
                        $postDB->texte =            $newtext;
                        $postDB->previewContent =   $newPreview;

                        //display du CORP du nouveau post
                        $display = new displayPost($postDB, $currentUser);
                        $html = $display->showPostHeader() . $display->showPostBody();

                        //edition du post
                        $post = new Posts();
                        $post->editPost($postid, $newtext, $newPreview);
                    }
                    Session::getInstance()->delete('current-action');
                    echo($html);
                    exit();
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
}
else{
    echo('err');
    exit();
}