<?php

namespace core\Timeline;

use app\Table\Comments\CommentModel;
use app\Table\Posts\PostModel;

use app\Table\Posts\MyTimeline\displayMyPost;
use app\Table\Posts\UserTimeline\displayTimelineUserPost;

use app\Table\Posts\MyTimeline\displayMyPostNotifyMyNetwork;
use app\Table\Posts\UserTimeline\displayTimelineUserPostNotifyMyNetwork;

use app\Table\Posts\Home\displayPostNotifyMyNetwork;
use core\Session\Session;

class ProfileTimeline extends Timeline
{
    private $user;

    public function __construct($user = false)
    {
        parent::__construct();
        $this->user = $user; //#todo OPTIMISER checker si on a reelement besoin de l'user et non de seulement certaines de ses propriétés
    }

    /****************************************************************************\
     *                          MY PROFILE PART                                 *
    \****************************************************************************/
    public function getMyTimeline()
    {
        $timelineElemContent = '';
        $posts_id = [];
        $user = Session::getInstance()->read('auth');

        //recupère l'user a afficher
        $myPostsAssociatesWithComments = $this->getMyPostsAssociatesWithComments();

        //creation du tableau de contenu des posts/com a passer a la vue Home/Timeline
        //creation du tableau ordonné de post en session afin de faire correspondre l'index du post séléctionné a l'index de son ID dans le tableau d'id post en session
        foreach ($myPostsAssociatesWithComments as $assocMyPost) {
            //recuperation de l'user, de son post, des commentaires
            $post = $assocMyPost['post'];
            $comments = $assocMyPost['comments'];

            //recuperaton du nombre de commentaire et definition du $commentDisplayMode pour le displayPost
            $model = new CommentModel();
            $nbComments = $model->getNbCommentToDisplayFromPost($post->pk_idpost);
            $commentDisplayMode = $this->getCommentDisplayMode($nbComments);

            //recuperation des resultats de show pour les passer a la vue dans le Profile Gamer/Employee Controller
            if($post->typeNotifyMyNetwork)
            {
                $display = new displayMyPostNotifyMyNetwork($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();

            }
            else{
                $display = new displayMyPost($post, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();;
            }

            //creation du tableau ordonné de post_id pour l'inscription en session, parsé en int pour le controle de type lor de l'appel de la fonction in_array sur inc/comment.php
            array_push($posts_id, (int)$post->pk_idpost);
        }
        //inscription du tableau en session
        $this->writePostsIdInSession($posts_id);
        return $timelineElemContent;
    }

    public function getNextMyTimeline($begin, $nbPostToDisplay = 5)
    {
        $timelineElemContent = '';
        $posts_id = [];
        $user = Session::getInstance()->read('auth');

        //recupère l'user a afficher
        $myPostsAssociatesWithComments = $this->getMyPostsAssociatesWithComments($begin, $nbPostToDisplay);

        //creation du tableau de contenu des posts/com a passer a la vue Home/Timeline
        //creation du tableau ordonné de post en session afin de faire correspondre l'index du post séléctionné a l'index de son ID dans le tableau d'id post en session
        foreach ($myPostsAssociatesWithComments as $assocMyPost) {
            //recuperation de l'user, de son post, des commentaires
            $post = $assocMyPost['post'];
            $comments = $assocMyPost['comments'];

            //recuperaton du nombre de commentaire et definition du $commentDisplayMode pour le displayPost
            $model = new CommentModel();
            $nbComments = $model->getNbCommentToDisplayFromPost($post->pk_idpost);
            $commentDisplayMode = $this->getCommentDisplayMode($nbComments);

            //recuperation des resultats de show pour les passer a la vue dans le Profile Gamer/Employee Controller
            if($post->typeNotifyMyNetwork)
            {
                $display = new displayMyPostNotifyMyNetwork($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();

            }
            else{
                $display = new displayMyPost($post, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();
            }

            //creation du tableau ordonné de post_id pour l'inscription en session, parsé en int pour le controle de type lor de l'appel de la fonction in_array sur inc/comment.php
            array_push($posts_id, (int)$post->pk_idpost);
        }
        //inscription du tableau en session
        $this->updatePostsIdSessionAddInBack($posts_id);
        return $timelineElemContent;
    }

    public function getMyPostsAssociatesWithComments($begin = 0, $nbposts = 5)
    {
        $postModel = new PostModel();
        $myposts = $postModel->getMyPosts($begin, $nbposts);

        $myPostsAssociatesWithComments = [];
        $nbPosts = count($myposts);

        for ($indexPost = 0; $indexPost < $nbPosts; ++$indexPost)
        {
            $commentModel = new CommentModel();
            $comments = $commentModel->getCommentsFromPost($myposts[$indexPost]->pk_idpost, 0, 10);
            
            $myPostsAssociatesWithComments[$indexPost]['post'] = $myposts[$indexPost];
            $myPostsAssociatesWithComments[$indexPost]['comments'] = $comments;
        }

       return $myPostsAssociatesWithComments;
    }

    /****************************************************************************\
     *                          SOMEONE ELESE PART                              *
    \****************************************************************************/
    public function getTimeline($begin, $nbposttodisplay)
    {
        $timelineElemContent = '';
        $posts_id = [];
        $user = $this->user;

        //recupère l'user a afficher
        $myPostsAssociatesWithComments = $this->getPostsAssociatesWithComments($begin, $nbposttodisplay);

        //creation du tableau de contenu des posts/com a passer a la vue Home/Timeline
        //creation du tableau ordonné de post en session afin de faire correspondre l'index du post séléctionné a l'index de son ID dans le tableau d'id post en session
        foreach ($myPostsAssociatesWithComments as $assocMyPost) {
            //recuperation de l'user, de son post, des commentaires
            $post = $assocMyPost['post'];
            $comments = $assocMyPost['comments'];

            //recuperaton du nombre de commentaire et definition du $commentDisplayMode pour le displayPost
            $model = new CommentModel();
            $nbComments = $model->getNbCommentToDisplayFromPost($post->pk_idpost);
            $commentDisplayMode = $this->getCommentDisplayMode($nbComments);

            //recuperation des resultats de show pour les passer a la vue dans le Profile Gamer/Employee Controller
            if($post->typeNotifyMyNetwork)
            {
                $display = new displayTimelineUserPostNotifyMyNetwork($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();

            }
            else{
                $display = new displayTimelineUserPost($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();
            }

            //creation du tableau ordonné de post_id pour l'inscription en session, parsé en int pour le controle de type lor de l'appel de la fonction in_array sur inc/comment.php
            array_push($posts_id, (int)$post->pk_idpost);
        }
        //inscription du tableau en session
        $this->writePostsIdInSession($posts_id);
        return $timelineElemContent;
    }

    public function getNextTimeline($begin, $nbposttodisplay = 5)
    {
        $timelineElemContent = '';
        $posts_id = [];
        $user = $this->user;

        //recupère l'user a afficher
        $myPostsAssociatesWithComments = $this->getPostsAssociatesWithComments($begin, $nbposttodisplay);

        //creation du tableau de contenu des posts/com a passer a la vue Home/Timeline
        //creation du tableau ordonné de post en session afin de faire correspondre l'index du post séléctionné a l'index de son ID dans le tableau d'id post en session
        foreach ($myPostsAssociatesWithComments as $assocMyPost) {
            //recuperation de l'user, de son post, des commentaires
            $post = $assocMyPost['post'];
            $comments = $assocMyPost['comments'];

            //recuperaton du nombre de commentaire et definition du $commentDisplayMode pour le displayPost
            $model = new CommentModel();
            $nbComments = $model->getNbCommentToDisplayFromPost($post->pk_idpost);
            $commentDisplayMode = $this->getCommentDisplayMode($nbComments);

            //recuperation des resultats de show pour les passer a la vue dans le Profile Gamer/Employee Controller
            if($post->typeNotifyMyNetwork)
            {
                $display = new displayTimelineUserPostNotifyMyNetwork($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();

            }
            else{
                $display = new displayTimelineUserPost($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();
            }

            //creation du tableau ordonné de post_id pour l'inscription en session, parsé en int pour le controle de type lor de l'appel de la fonction in_array sur inc/comment.php
            array_push($posts_id, (int)$post->pk_idpost);
        }
        //inscription du tableau en session
        $this->updatePostsIdSessionAddInBack($posts_id);
        return $timelineElemContent;
    }

    //public function

    public function getPostsAssociatesWithComments($begin, $nbposts)
    {
        $postModel = new PostModel();
        $posts = $postModel->getPostsFromIdUser($begin, $nbposts, $this->user->pk_iduser);

        $PostsAssociatesWithComments = [];
        $nbPosts = count($posts);

        for ($indexPost = 0; $indexPost < $nbPosts; ++$indexPost)
        {
            $commentModel = new CommentModel();
            $comments = $commentModel->getCommentsFromPost($posts[$indexPost]->pk_idpost, 0, 10);

            $PostsAssociatesWithComments[$indexPost]['post'] = $posts[$indexPost];
            $PostsAssociatesWithComments[$indexPost]['comments'] = $comments;
        }

        return $PostsAssociatesWithComments;
    }

    public function updatePostsIdSessionAddInBack($arrayPostIds)
    {
        //ici pas besoin de creer le tableau il existe deja
        //ajouter l'id du nouveau post au tableau, l'ajouter en dernier pour conserver l'ordre par rapport a celui d'affichage
        $oldPostIdArray = $this->session->read('PostDisplayed');
        $newPostIdArray = array_merge($oldPostIdArray, $arrayPostIds);
        //MAJ du tableau de post id displayd dans l'ordre d'affichage
        $this->session->write('PostDisplayed', $newPostIdArray);
    }
}