<?php

namespace core\Timeline;

use app\App;
use app\Table\Posts\Home\displayPost;
use app\Table\Posts\Home\displayPostNotifyMyNetwork;
use core\Session\Session;
use app\Table\Comments\CommentModel;

class Timeline
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = App::getDatabase();
        $this->session = Session::getInstance();

        $this->mainUser = App::getMainUser();
    }
    
    public function getTimelineElem()
    {
        $timelineElemContent = '';
        $posts_id = [];

        //recupère les utilisateur habilité a affiché leur posts ds ma timeline
        $users = $this->getUsersToDisplay();
        $postsAssociatesWithUser = $this->getPostsAssociateToUsersToDisplay($users);

        //creation du tableau de contenu des posts/com a passer a la vue Home/Timeline
        //creation du tableau ordonné de post en session afin de faire correspondre l'index du post séléctionné a l'index de son ID dans le tableau d'id post en session
        foreach($postsAssociatesWithUser as $assocUserPost)
        {
            //recuperation de l'user, de son post, des commentaires
            $user = $assocUserPost['user'];
            $post = $assocUserPost['post'];
            $comments = $assocUserPost['comments'];

            //recuperaton du nombre de commentaire et definition du $commentDisplayMode pour le displayPost
            $model = new CommentModel();
            $nbComments = $model->getNbCommentToDisplayFromPost($post->pk_idpost); //todo optimisable
            $commentDisplayMode = $this->getCommentDisplayMode($nbComments);

            //recuperation des resultats de show pour les passer a la vue dans le HomeController
            if($post->typeNotifyMyNetwork)
            {
                $display = new displayPostNotifyMyNetwork($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();
                //array_push($timelineElemContent, $display->show());

            }
            else{
                $display = new displayPost($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();
                //array_push($timelineElemContent, $display->show());
            }

            //creation du tableau ordonné de post_id pour l'inscription en session, parsé en int pour le controle de type lor de l'appel de la fonction in_array sur inc/comment.php
            array_push($posts_id, (int)$post->pk_idpost);
        }
        //inscription du tableau en session
        $this->writePostsIdInSession($posts_id);

        return $timelineElemContent;
    }

    public function getNextTimelineElem($begin, $nbPostToDisplay = 5)
    {
        $timelineElemContent = '';
        $posts_id = [];

        //recupère les utilisateur habilité a affiché leur posts ds ma timeline
        $users = $this->getUsersToDisplay($begin, $nbPostToDisplay);
        $postsAssociatesWithUser = $this->getPostsAssociateToUsersToDisplay($users);

        //creation du tableau de contenu des posts/com a passer a la vue Home/Timeline
        //creation du tableau ordonné de post en session afin de faire correspondre l'index du post séléctionné a l'index de son ID dans le tableau d'id post en session
        foreach($postsAssociatesWithUser as $assocUserPost)
        {
            //recuperation de l'user, de son post, des commentaires
            $user = $assocUserPost['user'];
            $post = $assocUserPost['post'];
            $comments = $assocUserPost['comments'];

            //recuperaton du nombre de commentaire et definition du $commentDisplayMode pour le displayPost
            $model = new CommentModel();
            $nbComments = $model->getNbCommentToDisplayFromPost($post->pk_idpost); //todo optimisable
            $commentDisplayMode = $this->getCommentDisplayMode($nbComments);

            //recuperation des resultats de show pour les passer a la vue dans le HomeController
            if($post->typeNotifyMyNetwork)
            {
                $display = new displayPostNotifyMyNetwork($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();

            }
            else{
                $display = new displayPost($post, $user, $comments, $commentDisplayMode);
                $timelineElemContent .=  $display->show();
            }

            //creation du tableau ordonné de post_id pour l'inscription en session, parsé en int pour le controle de type lor de l'appel de la fonction in_array sur inc/comment.php
            array_push($posts_id, (int)$post->pk_idpost);
        }
        //inscription du tableau en session
        $this->updatePostsIdSessionAddInBack($posts_id);

        return $timelineElemContent;
    }

    //recuperation des utilisateurs amis, folowed, sans ceux bloqué, ou ceux dont on a décidé de ne plus voir les posts
    public function getUsersToDisplay($begin = 0, $nbpostToDisplay = 5)
    {
        //recuperation des ids des utilisateurs amis, folowed, sans ceux bloqué, ou ceux dont on a décidé de ne plus voir les posts
        $currentUserId = $this->session->read('auth')->pk_iduser;

        $users = $this->db->query(
            "SELECT * FROM we__user WHERE pk_iduser IN ("
            ."SELECT * FROM ( SELECT id_contact AS id_user FROM we__contacts WHERE we__contacts.fk_iduser = ? "
            ."UNION "
            ."SELECT id_userfolowed AS id_user FROM we__folowingusers WHERE we__folowingusers.fk_iduser = ? "
            ."UNION "
            ."SELECT pk_iduser FROM we__user WHERE pk_iduser = ?) t1 "
            ."WHERE t1.id_user NOT IN("
            ."SELECT id_userHided AS id_user FROM we__usershided WHERE fk_iduser = ?) "
            ."AND t1.id_user NOT IN("
            ."SELECT id_userBlocked AS id_user FROM we__usersblocked WHERE fk_iduser = ?) "
            ."UNION SELECT pk_iduser FROM we__user WHERE pk_iduser = ?) "
            ."ORDER BY last_post_activity DESC LIMIT $begin,$nbpostToDisplay",[
            $currentUserId,
            $currentUserId,
            $this->mainUser->pk_iduser,
            $currentUserId,
            $currentUserId,
            $currentUserId
        ])->fetchAll();

        //ajouter le main user aux results afins que ses posts s'affichent pour tout le monde
        //$users = $this->addMainUserToResults($users);

        return $users;
    } //#todo les limites

    //get les posts / comments associés aux users selectionné par l'algorithme getUsersToDisplay pour figurer dans la timeline, creer un tableau tab[i]/['user']['post a afficher'] ne chargera jamais
    public function getPostsAssociateToUsersToDisplay($users)
    {
        $postsAssociatesWithUsers = [];
        $nbUsers = count($users);

        $model = new CommentModel();

        //pour chaques id, recuperation des posts les plus récents qui n'ont pas étés hidés
        for ($indexUser = 0; $indexUser < $nbUsers; ++$indexUser) {

            $userid = $users[$indexUser]->pk_iduser;
            $post = $model->getPostToDisplayFromUserId($userid);

            //creation du tableau associatif contenant par collonne: l'user / son dernier post / 10 premiers commentaires associés
            //creation du tableau d'id posts en session
            if ($post) {
                $commentModel = new CommentModel();
                $comments = $commentModel->getCommentsFromPost($post->pk_idpost);

                $postsAssociatesWithUsers[$indexUser]['user'] = $users[$indexUser];
                $postsAssociatesWithUsers[$indexUser]['post'] = $post;
                $postsAssociatesWithUsers[$indexUser]['comments'] = $comments;
            }
        }

        return $postsAssociatesWithUsers;
    }

    //en fonction du nombre de comment recupéré par post, defnir le mode d'affichage (pour le show more et/ou show more AJAX)
    public function getCommentDisplayMode($nbComments)
    {
        //CommentDisplayMode used by displayPost
        if($nbComments <= 5)
        {
            return 0;
        }
        if($nbComments > 5 && $nbComments < 10)
        {
            return 1;
        }
        if($nbComments = 10)
        {
            return 2;
        }
    }
    
    //fonction destinnée a mettre en session les id des posts affiché, pour le test notement lor du comment, que le post qu'essaye de commenter l'utilisateur est bien affiché. Ceci afin d'eviter que l'utilisateur change les valeurs d'ids afin de commenter a distance d'autres posts
    protected function writePostsIdInSession($arrayPostIds)
    {
        $this->session->write('PostDisplayed', $arrayPostIds);
        return true;
    }

    //fonction appellée lor du share d'un article par un user
    public function updatePostsIdSessionAddInFront($post_id)
    {
        //creer le tableau s'il n'existe pas = le mec n'as aucun post affiché sur son mur
        if(!isset($_SESSION['PostDisplayed']))
        {
            $this->session->write('PostDisplayed', []);
        }
        //ajouter l'id du nouveau post au tableau, l'ajouter en premier pour conserver l'ordre par rapport a celui d'affichage
        $oldPostIdArray = $this->session->read('PostDisplayed');
        $key = count($oldPostIdArray);
        $inter = array_reverse($oldPostIdArray);
        $inter[$key] = (int)$post_id;
        $newPostIdArray = array_reverse($inter);
        $this->session->write('PostDisplayed', $newPostIdArray);

        //ajouter l'id au tableau de MES posts ajoutés
        $this->updatePostsAddedByMyself($post_id);
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

    public function deleteFromPostDisplayed($post_id)
    {
        $postIdArray = $this->session->read('PostDisplayed');
        $indexValue = array_search($post_id, $postIdArray);
        unset($postIdArray[$indexValue]);
        $this->session->write('PostDisplayed', $postIdArray);
    }

    public function getNbPostDisplayed()
    {
        $nbPostDisplayed = sizeof($this->session->read('PostDisplayed'));
        $nbJustAddedPostDisplayed = sizeof($this->session->read('PostsJustSharedByUser'));
        return $nbPostDisplayed - $nbJustAddedPostDisplayed;
    }

    public function updatePostsAddedByMyself($postid)
    {
        //ajouter l'id du nouveau post au tableau de mes posts Just Shared
        //creer le tableau s'il n'existe pas = le mec n'as aucun post affiché sur son mur
        if(!isset($_SESSION['PostsJustSharedByUser']))
        {
            $this->session->write('PostsJustSharedByUser', []);
        }
        $idArray = $this->session->read('PostsJustSharedByUser');
        $this->session->write('PostsJustSharedByUser', $idArray);
    }
}