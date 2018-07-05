<?php

//#todo gere le cas ou c'est ton post et ecrire "You" et non nickname
namespace app\Table\Posts\Home;

use app\Table\appDates;
use core\Session\Session;

use app\Table\AppDisplay;
use app\Table\Comments\displayComment;
use app\Table\Comments\CommentModel;
use app\Table\Likes\LikeModel;
use app\Table\UserModel\displayUser;
use app\Table\Images\Images\ImagesUsers\displayUsersImages;

class displayPost extends AppDisplay
{
    private $id;
    private $text;
    private $previewContent;
    private $liveContent;
    private $images;
    private $action;
    private $date;
    private $nbLikes;
    private $nbComments;

    private $user_profile_pic;
    private $actionTraduced;

    private $comments;
    private $commentDisplayMode; //0: nb comment <= 5, 1: nb comment >5 & <= 10, 2: nb comment > 10
    private $user;
    private $data_elem;
    private $likeClass = '';
    private $noCommentIndicator = 'empty-comments'; //si le post ne possède aucun commentaire on lui assigne cette classe
    private $UserImages;

    protected $pageName;

    //comment display otpion is nescessary to know what kind of displaying function we will call for display posts comments
    public function __construct($post, $user, $comments = [], $commentDisplayMode = false)
    {
        $this->pageName = 'home';
        parent::__construct(false, $this->pageName);
        $this->id =                 $post->pk_idpost;
        $this->text =               $post->texte;
        $this->previewContent =     $post->previewContent;
        $this->liveContent =        $post->liveContent;
        $this->action =             $post->action;
        $this->user =               $user;
        $this->nbLikes =            $post->nbLikes;
        $this->nbComments =         $post->nbComments;
        $this->comments =           $comments;
        $this->commentDisplayMode = $commentDisplayMode;
        $this->date =               $post->date;

        //change date
        $appDates   = new appDates($this->date, 'dateSQL', 'normal');
        $this->date = $appDates->getDate();

        $this->actionTraduced =     $this->langModel->getActionTraduce($this->action);

        //profile pic of user
        $displayUser = new displayUser($user);
        $this->user_profile_pic = $displayUser->showMyProfilePic(); //#todo generer ca avec un Image manager

        //affect images to array
        if($post->images != 'noNeedImgUpdate')
        {
            $nbImgs = strlen(($post->images));
            if($nbImgs != 0)
            {
                $this->images = explode("/",$post->images);
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
                        <a href='index.php?p=profile&u=". $this->user->slug ."'>". $this->UserImages->showProfileUserPic_little() ."</a>
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
        $postBody = '';
        $sizeOfImgs = sizeof($this->images);
        if($sizeOfImgs == 0)
        {
            $postBody = '<div class="post-body col-md-12">                           
                            <div class="post-text col-md-12">                                
                                '. $this->text .'
                            </div>
                            <button class="valid-modifs valid">'. $this->langFile[$this->pageName]->bt_validate_modifications .'</button>
                            '. $this->showLinkPreview() .'
                            '. $this->showLiveContent() .'
                         </div>';
        }
        if($sizeOfImgs == 1)
        {
            if($this->text == '')
            {
                $postBody = '<div class="post-body col-md-12">                                
                                <div class="post-text empty-text col-md-12">
                                    '. $this->text .'
                                </div>
                                <button class="valid valid-modifs valid">'. $this->langFile[$this->pageName]->bt_validate_modifications .'</button>
                                '. $this->showLinkPreview() .'
                                '. $this->showLiveContent() .'
                                <div class="post-pic-bloc col-md-12">
                                    <div class="post-pic-container only-pic col-md-12">
                                        <div role="button" class="bt-preview col-md-12" data-elem="'. $this->images[0] .'" data-p="'. $this->id .'">
                                            <div class="post-pic col-md-12" data-toggle="0">
                                                <div class="trash-container" data-toggle="0">
                                                </div>                                                
                                                <img src="inc/img/img.php?imgname='. $this->images[0] .'&p='. $this->id .'&u='. $this->user->pk_iduser .'" alt="'. $this->user->nickname .'" data-name="'. $this->images[0] .'">                                                                      
                                                <div class="cancel-container" data-toggle="0">
                                                    <p class="cancel-pic-modif">'. $this->langGenerals->word_cancel .'</p>
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                            </div>';
            }
            if($this->text != '')
            {
                $postBody = '<div class="post-body col-md-12">                                
                                <div class="post-text col-md-12">
                                    '. $this->text .'
                                </div>
                                <button class="valid-modifs valid">'. $this->langFile[$this->pageName]->bt_validate_modifications .'</button>
                                '. $this->showLinkPreview() .'
                                '. $this->showLiveContent() .'
                                <div class="post-pic-bloc col-md-12">
                                    <div class="post-pic-container only-pic col-md-12">
                                        <div role="button" class="bt-preview col-md-12" data-elem="'. $this->images[0] .'" data-p="'. $this->id .'">
                                            <div class="post-pic only-pic col-md-12" data-toggle="0">
                                                <div class="trash-container" data-toggle="0">
                                                </div>                       
                                                <img src="inc/img/img.php?imgname='. $this->images[0] .'&p='. $this->id .'&u='. $this->user->pk_iduser .'" alt="'. $this->user->nickname .'" data-name="'. $this->images[0] .'">
                                                <div class="cancel-container" data-toggle="0">
                                                    <p class="cancel-pic-modif">'. $this->langGenerals->word_cancel .'</p>
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                            </div>';
            }

        }
        if($sizeOfImgs == 2)
        {
            $imgleft = [];
            $imgright = [];
            $imgsRightHTML = '';
            $imgsLeftHTML = '';

            for($indexImage = 0; $indexImage < $sizeOfImgs; ++$indexImage)
            {
                if($indexImage % 2 == 0)
                {
                    array_push($imgleft, $this->images[$indexImage]);
                }
                else{
                    array_push($imgright, $this->images[$indexImage]);
                }
            }

            foreach ($imgleft as $image)
            {
                $imgsLeftHTML = $imgsLeftHTML . '<div class="post-pic col-md-12" data-toggle="0"><div class="trash-container" data-toggle="0"></div><img src="inc/img/img.php?imgname='. $image . '&p='. $this->id .'&u='. $this->user->pk_iduser .'" alt="'. $this->user->nickname .'" data-name="'. $image .'"><div class="cancel-container" data-toggle="0"><p class="cancel-pic-modif">Cancel</p></div></div>';
            }
            foreach ($imgright as $image)
            {
                $imgsRightHTML = $imgsRightHTML . '<div class="post-pic col-md-12" data-toggle="0"><div class="trash-container" data-toggle="0"></div><img src="inc/img/img.php?imgname='. $image . '&p='. $this->id .'&u='. $this->user->pk_iduser .'" alt="'. $this->user->nickname .'" data-name="'. $image .'"><div class="cancel-container" data-toggle="0"><p class="cancel-pic-modif">Cancel</p></div></div>';
            }

            if($this->text == '')
            {
                $postBody = '<div class="post-body col-md-12">
                                <div class="post-text empty-text col-md-12">
                                    '. $this->text .'
                                </div>
                                <button class="valid-modifs valid">'. $this->langFile[$this->pageName]->bt_validate_modifications .'</button>
                                '. $this->showLinkPreview() .'
                                '. $this->showLiveContent() .'
                                <div class="post-pic-bloc col-md-12">
                                    <div role="button" class="bt-preview" data-elem="'. $this->images[0] .'" data-p="'. $this->id .'">
                                        <div class="post-pic-container lots-pic col-md-6">
                                            '. $imgsLeftHTML .'
                                        </div>
                                        <div class="post-pic-container lots-pic col-md-6">
                                           '. $imgsRightHTML .'
                                        </div>
                                    </div>
                                </div>                                        
                            </div>';
            }
            if($this->text != '')
            {
                $postBody = '<div class="post-body col-md-12">
                                <div class="post-text col-md-12">
                                    '. $this->text .'
                                </div>
                                <button class="valid-modifs valid">'. $this->langFile[$this->pageName]->bt_validate_modifications .'</button>
                                '. $this->showLinkPreview() .'
                                '. $this->showLiveContent() .'
                                <div class="post-pic-bloc col-md-12">
                                    <div role="button" class="bt-preview" data-elem="'. $this->images[0] .'" data-p="'. $this->id .'">
                                        <div class="post-pic-container lots-pic col-md-6">
                                            '. $imgsLeftHTML .'
                                        </div>
                                        <div class="post-pic-container lots-pic col-md-6">
                                           '. $imgsRightHTML .'
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }

        }
        if($sizeOfImgs > 2)
        {
            $imgleft = [];
            $imgright = [];
            $imgsRightHTML = '';
            $imgsLeftHTML = '';

            for($indexImage = 0; $indexImage < $sizeOfImgs; ++$indexImage)
            {
                if($indexImage % 2 == 0)
                {
                    array_push($imgleft, $this->images[$indexImage]);
                }
                else{
                    array_push($imgright, $this->images[$indexImage]);
                }
            }

            foreach ($imgleft as $image)
            {
                $imgsLeftHTML = $imgsLeftHTML . '<div class="post-pic col-md-12" data-toggle="0"><div class="trash-container" data-toggle="0"></div><img src="inc/img/img.php?imgname='. $image . '&p='. $this->id .'&u='. $this->user->pk_iduser .'" alt="'. $this->user->nickname .'" data-name="'. $image .'"><div class="cancel-container" data-toggle="0"><p class="cancel-pic-modif">Cancel</p></div></div>';
            }
            foreach ($imgright as $image)
            {
                $imgsRightHTML = $imgsRightHTML . '<div class="post-pic col-md-12" data-toggle="0"><div class="trash-container" data-toggle="0"></div><img src="inc/img/img.php?imgname='. $image . '&p='. $this->id .'&u='. $this->user->pk_iduser .'" alt="'. $this->user->nickname .'" data-name="'. $image .'"><div class="cancel-container" data-toggle="0"><p class="cancel-pic-modif">Cancel</p></div></div>';
            }

            if($this->text == '')
            {
                $postBody = '<div class="post-body col-md-12">
                                <div class="post-text empty-text col-md-12">
                                    '. $this->text .'
                                </div>
                                <button class="valid-modifs valid">'. $this->langFile[$this->pageName]->bt_validate_modifications .'</button>
                                '. $this->showLinkPreview() .'
                                '. $this->showLiveContent() .'
                                <div class="post-pic-bloc col-md-12">
                                    <div role="button" class="bt-preview" data-elem="'. $this->images[0] .'" data-p="'. $this->id .'">
                                        <div class="post-pic-container lots-pic col-md-6">
                                            '. $imgsLeftHTML .'
                                        </div>
                                        <div class="post-pic-container lots-pic col-md-6">
                                           '. $imgsRightHTML .'
                                        </div>                                       
                                    </div>
                                    <div class="display-next-pic">
                                    </div>
                                </div>
                             </div>';
            }
            if($this->text != '')
            {
                $postBody = '<div class="post-body col-md-12">                               
                                <div class="post-text col-md-12">
                                    '. $this->text .'
                                </div>
                                <button class="valid-modifs valid">'. $this->langFile[$this->pageName]->bt_validate_modifications .'</button>
                                '. $this->showLinkPreview() .'
                                '. $this->showLiveContent() .'
                                <div class="post-pic-bloc lot-pics col-md-12">
                                    <div role="button" class="bt-preview" data-elem="'. $this->images[0] .'" data-p="'. $this->id .'">
                                        <div class="post-pic-container lots-pic col-md-6">
                                            '. $imgsLeftHTML .'
                                        </div>
                                        <div class="post-pic-container lots-pic col-md-6">
                                           '. $imgsRightHTML .'
                                        </div>                                        
                                    </div>
                                    <div class="display-next-pic">
                                    </div>
                                </div>
                            </div>';
            }
        }

        return $postBody;
    }

    public function showLinkPreview(){
        if($this->previewContent != ''){
            return '<div class="post-preview preview-container" >
                        <div class="loader-preview-container">
                            <div class="loader-container loader-profile-elem" data-elem="loader-preview">
                                <div class="loader-double-container">
                                    <span class="loader loader-double">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-bloc">
                            '. $this->previewContent .'
                        </div> 
                    </div>';
        }
        else{
            return '<div class="post-preview preview-container empty" >
                        <div class="loader-preview-container">
                            <div class="loader-container loader-profile-elem" data-elem="loader-preview">
                                <div class="loader-double-container">
                                    <span class="loader loader-double">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-bloc">
                            '. $this->previewContent .'
                        </div> 
                    </div>';
        }
    }

    public function showLiveContent()
    {
        return '<div class=post-live-container>
                    <div class="post-live-content">
                        '. $this->liveContent .'
                    </div>
                </div>';
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
                                <p class="show-comments">'. $this->langFile[$this->pageName]->bt_showMore_nodotted .' (5/'. $TotalComToDisplay .')</p>
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
                        <a href='index.php?p=profile&u=". $this->user->slug ."'>". $this->UserImages->showProfileUserPic_little() ."</a>
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