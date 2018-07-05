<?php
use inc\Autoloader;
use core\Timeline\Posts;
use app\Table\UserModel\UserModel;
use app\Table\Posts\PostModel;
use app\Table\Posts\Home\displayPost;
use app\App;
use core\Session\Session;
use core\Files\Images\Images;
use core\Tmp\Tmp;
use core\Timeline\Timeline;
use app\Table\Images\ImagesModel;
use core\Functions;

if(isset($_POST['texte']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $user =         Session::getInstance()->read('auth');
    $post =         new Posts();
    $userModel =    new UserModel(App::getDatabase());  //#todo remove le paramettre db a l'userModel
    $postModel =    new PostModel();
    $imgModel =     new ImagesModel();
    $images =       new Images();
    $tmp =          new Tmp();
    $timeline =     new Timeline();

    $content = $_POST['texte'];
    $imgs = '';

    //tests de contenu des images
    if(strlen($_POST['imgsToPost']) > 2) // supperieur a 2 car chaine vide = '[]' <- deux char
    {
        $imgArray =             json_decode($_POST['imgsToPost']);
        $arraySize =            sizeof($imgArray);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * SAVE POST
         */
        //on post sans les images, ces denières sont iseréées après
        $post->post($content, false, false);
        $dateLastPostActivity = $userModel->getLastPostActivity($user->pk_iduser);
        $postId = $postModel->getPostIdFromLastPostActivity($dateLastPostActivity);

        //get images slug et rename
        $newImgArray = [];
        for($i = 0; $i < $arraySize; $i++)
        {
            //format img name
            $imgArray[$i] = $images->formatFileName($imgArray[$i]);
            $imgName = $imgArray[$i];

            //get img slug to insert in array
            $imgSlug = substr(Functions::HASH('img', $postId . $imgName . $i), 0, 20);

            //add slug to new imgArray
            array_push($newImgArray,$imgSlug);

            //upload img in database
            $imgModel->saveImagePosted($postId, $user->pk_iduser, $imgArray[$i], $i);
        }
        //creer le tableau d'imagesSlug
        $imgs = implode('/',$newImgArray);
        $post->addImagesToPost($postId, $imgs);
        //ad imgs to post

        //upload images in the right folder

        //--------------------------------------------------------------------------------------------------------------------
        /*
         * UPLOAD IMAGES
         */
        $images->uploadPostImages($postId);
    }
    else{
        //pas d'images, Poster
        $post->post($content, '', false);
    }

    //effacer le dossier temporaire
    $tmp->deleteTmpFolder();

    //display post in timeline
    //recuperer l'id du post, grace a la date 'last_post_activity' ecrire via trigger sur Post
    $dateLastPostActivity = $userModel->getLastPostActivity($user->pk_iduser);
    $postId = $postModel->getPostIdFromLastPostActivity($dateLastPostActivity);

    //rendu html
    $postObject = $postModel->getPostFromid($postId);
    $display = new displayPost($postObject, $user);

    //add post in PostDisplayed SESSION array
    $timeline->updatePostsIdSessionAddInFront($postId);

    echo($display->show());
}