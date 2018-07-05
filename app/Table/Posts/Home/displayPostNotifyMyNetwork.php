<?php

namespace app\Table\Posts\Home;

use app\Table\appDates;
use core\Session\Session;
use app\App;

use app\Table\AppDisplay;
use app\Table\Comments\displayComment;
use app\Table\Comments\CommentModel;
use app\Table\Likes\LikeModel;
use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use app\Table\Profile\Quickinfos\QuickInfosModel;

class displayPostNotifyMyNetwork extends AppDisplay
{
    private $id;
    private $text;
    private $type_action;
    private $action;
    private $date;
    private $nbLikes;
    private $nbComments;

    protected $pageName;

    private $comments;
    private $commentDisplayMode; //0: nb comment <= 5, 1: nb comment >5 & <= 10, 2: nb comment > 10
    private $noCommentIndicator = 'empty-comments'; //si le post ne possède aucun commentaire on lui assigne cette classe

    private $user;
    private $qiUser;
    private $UserImages;

    private $lineJob;
    private $lineRole;

    private $data_elem;

    private $likeClass = '';

    private $actionTraduced;

    //comment display otpion is nescessary to know what kind of displaying function we will call for display posts comments
    public function __construct($post, $user, $comments = [], $commentDisplayMode = false)
    {
        $this->pageName = 'home';
        parent::__construct(false, $this->pageName);
        $this->id =             $post->pk_idpost;
        $this->text =           $post->texte;
        $this->type_action =    $post->type_action;
        $this->action =         $post->action;
        $this->elem_type =      $post->elem_type;
        $this->elem_id =        $post->elem_id;
        $this->date =           $post->date;
        $this->user =           $user;
        $this->nbLikes =        $post->nbLikes;
        $this->nbComments =     $post->nbComments;
        $this->comments =       $comments;
        $this->commentDisplayMode = $commentDisplayMode;
        $this->date =           $post->date;

        //change date
        $appDates   = new appDates($this->date, 'dateSQL', 'normal');
        $this->date = $appDates->getDate();

        //get traduce of action
        $this->actionTraduced =     $this->langModel->getActionTraduce($this->action);

        //DEFINITION DES INFOS A AFFICHER
        $model = new QuickInfosModel();
        $this->qiUser = $model->getQuickInfosFromIdUser($this->user->pk_iduser);

        //si l'user possède un job courant
        if($this->qiUser->current_company)
        {
            $this->lineJob = $this->qiUser->jobtitle.' '.$this->langGenerals->word_at.' '.$this->qiUser->current_company;
        }
        else{
            $this->lineJob = '<span class="bold">'.$this->langGenerals->title_infoLine_nationnality.'</span> '.$this->qiUser->nationnality;
        }

        //si l'user possède une current team
        if($this->qiUser->current_team)
        {
            $this->lineRole = $this->qiUser->role.' '.$this->langGenerals->word_on.' '.$this->qiUser->game.' '.$this->langGenerals->word_at.' '.$this->qiUser->current_team;
        }
        else{
            if($this->qiUser->previous_team)
            {
                $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_previous_team.'</span> '.$this->qiUser->previous_team;
            }
            else{
                $this->lineRole = '<span class="bold">'.$this->langGenerals->title_infoLine_languages.'</span> '.$this->qiUser->language;
            }
        }

        //detect when there is no comment
        if(count($this->comments) != 0)
        {
            $this->noCommentIndicator = '';
        }

        //check if current user like the post
        $model = new LikeModel();
        if($model->checkIfCurrentUserLikePost($this->id))
        {
            $this->likeClass = 'focus-on-active';
        }
        $this->nbLikes = $model->getNbLikesFromPost($this->id);

        if($this->user->pk_iduser == Session::getInstance()->read('auth')->pk_iduser)
        {
            $this->data_elem = '{"wepid":"'. $this->id .'"}';
        }
        else{
            $this->data_elem = '{"wepid":"'. $this->id .'","weid":"'. $this->user->pk_iduser .'"}';
        }

        //images
        $this->UserImages = new displayUsersImages($user);
    }

    public function showPostHeader()
    {
        return "
            <div class='post-header col-md-12'>               
                <div class='post-intro col-md-7'>
                     <div class='timeline-elem-userpic pic mobile'>
                        <a href='index.php?p=profile&u=". $this->user->slug ."' alt='". $this->user->slug ." profile picture'>". $this->UserImages->showProfileUserPic_little() ."</a>
                    </div>
                    <div class='action'>
                        <a href='index.php?p=profile&u=". $this->user->slug ."' class='nickname-link'><p class='post-user-name'>". $this->user->nickname ."</p></a>
                        <p class='action-name'>". $this->actionTraduced ."</p>
                    </div>
                    <div class='time'>
                    ". $this->date ."
                    </div>
                </div>
                <div class='post-edit-options  col-md-5' tabindex='24'>                             
                    <div class='bt-display-options' tabindex='25'>
                    </div>
                    <div class='post-edit-options-container col-md-12'>                                                                  
                    </div>                             
                </div>
            </div>";
    }

    public function showPostBody()
    {
        //check type action
        switch ($this->type_action){
            case "employee":
                $link = '<a href="index.php?p=profile&u='. $this->user->slug .'&s=employee">'. $this->UserImages->showProfileUserPic_little() .'</a>'; //#todo trouver uen facon automatique de generer ces liens avec photo
                break;
            default:
                $link = '<a href="index.php?p=profile&u='. $this->user->slug .'">'. $this->UserImages->showProfileUserPic_little() .'</a>';
        }

        $postBody = '<div class="post-body col-md-12">
                        <div class="contact-container col-md-12">
                            <div class="contact-pic-container col-md-3">
                                <div class="contact-pic pic">
                                    '. $link .'
                                </div>
                            </div>
                            <div class="contact-infos-container col-md-9">
                                <div class="contact-infos">
                                    <h1 class="complete-name">'. $this->user->firstname .' "'. $this->user->nickname .'" '.$this->user->lastname.'</h1>
                                    <h3 class="role">'. $this->lineRole .'</h3>
                                    <h3 class="job">'. $this->lineJob .'</h3>
                                </div>
                            </div>                            
                        </div>
                     </div>';
        return $postBody;
    }

    public function showComments()
    {
        $comments = '';
        $others = '';
        $commentContainer = '';
        if($this->commentDisplayMode == 0)
        {
            foreach($this->comments as $comment)
            {
                $displayComment = new displayComment($comment);
                $comments = $comments . $displayComment->show();
            }
            $commentContainer = '
                                <div class="comments-wrap col-md-12">
                                    <div class="your-comment">
                                    </div>
                                    '. $comments.'
                                </div>';
        }
        if($this->commentDisplayMode == 1)
        {
            //afficher les 5 premiers normalement
            for($i = 0; $i < 5; ++$i)
            {
                $displayComment = new displayComment($this->comments[$i]);
                $comments = $comments . $displayComment->show();
            }

            //afficher les suivant de facon cachée
            $nbComments = count($this->comments);
            for($i = 5; $i<$nbComments; ++$i)
            {
                $displayComment = new displayComment($this->comments[$i]);
                $others = $others . $displayComment->show();
            }

            //nb com a afficher en plus to pass to the button show more
            $restToDisplay = $nbComments - 5;

            $commentContainer = '
                        <div class="comments-wrap col-md-12">
                            <div class="your-comment">
                            </div>
                            '. $comments .'                           
                            <div class="bt-show-more" role="button">
                                <p>'. $this->langFile[$this->pageName]->bt_showMore_nodotted .' (+ '. $restToDisplay .')</p>
                            </div>
                            '. $others .'
                        </div>';
        }
        if($this->commentDisplayMode == 2)
        {
            //afficher les 5 premiers normalement
            for($i = 0; $i < 5; ++$i)
            {
                $displayComment = new displayComment($this->comments[$i]);
                $comments = $comments . $displayComment->show();
            }

            //calculer combien il reste de commentaires a afficher
            $model = new CommentModel();
            $TotalComToDisplay = $model->getNbCommentToDisplayFromPost($this->id);
            $commentContainer = '
                        <div class="comments-wrap col-md-12">
                            <div class="your-comment">
                            </div>
                            '. $comments .'                           
                            <div class="bt-show-next col-md-12" role="button" data-nb="'. $TotalComToDisplay .'">
                                <p class="show-comments"'. $this->langFile[$this->pageName]->bt_showMore_nodotted .' (5/'. $TotalComToDisplay .')</p>
                                <div class="loader-container little" id="loader-show-nexts">
                                    <span class="loader loader-double">
                                    </span>
                                </div>                                 
                            </div>                          
                        </div>';
        }
        return $commentContainer;
    }

    public function show()
    {
        return "
            <div class='timeline-elem'>
                <div class='timeline-elem-leftpart col-md-3'>
                    <div class='timeline-elem-userpic pic'>
                        <a href='index.php?p=profile&u=". $this->user->slug ."' alt='nam user pic photo'>". $this->UserImages->showProfileUserPic_little() ."</a>
                    </div>
                    <div class='timeline-elem-usertitle'>
                        <h1 class='h1-nickname'>
                            ". $this->user->nickname ."
                        </h1>
                        <h2 class='h2-name'>
                            ". $this->user->firstname."  ". $this->user->lastname ."
                        </h2>
                    </div>
                </div>
                <div class='timeline-elem-rightpart col-md-9'>
                    <div class='post-wrap col-md-12'  data-elems='". $this->data_elem ."'>                      
                        ". $this->showPostHeader() ."
                        ". $this->showPostBody() ."
                        <div class='post-options ". $this->noCommentIndicator ." col-md-12'>
                            <div class='com-like-bloc col-md-12'>
                                <div class='comment-bloc'>
                                    <div class='counter'>
                                        ". $this->nbComments ."
                                    </div>
                                    <div class='name'>
                                        <h3>". $this->langFile[$this->pageName]->title_comments_counter ."</h3>
                                    </div>
                                </div>
                                <div class='like-bloc'>
                                    <div class='counter'>
                                        ". $this->nbLikes ."
                                    </div>                                  
                                    <div class='name'>
                                        <h3>". $this->langFile[$this->pageName]->title_like_counter ."</h3>
                                        <span class='focus-on like ". $this->likeClass ."'>
                                            <span class='focus-on-after'></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='comment-input-container editable-container col-md-9'>
                                <div class='input comment-input editable-content' placeholder='". $this->langFile[$this->pageName]->placeholder_add_comment ."' contenteditable='true'></div>
                            </div>
                            <div class='share-button-container col-md-3'>
                                <button role='button' type='submit' class='share-button bt-comment bt'>". $this->langFile[$this->pageName]->bt_share ."</button>
                            </div>
                        </div>
                        ". $this->showComments() ."
                    </div>
                </div>
            </div>
        ";
    }
}