<?php

namespace app\Displays\Emails\NotifsEmails;

use app\App;
use app\Table\AppEmailElems;
use app\Table\Images\Images\ImagesUsers\displayUsersImages;
use app\Table\LangModel\LangModel;
use app\Table\Profile\Quickinfos\QuickInfosModel;
use app\Table\UserModel\UserModel;
use core\ConfigAJAX;
use core\Files\Images\Images;

class displayContactNotifEmail extends AppEmailElems
{
    private $userTo;
    private $userFrom;
    private $traducedMessage;
    private $traducedBt;
    private $linkNotif;
    private $imgUser;

    public function __construct($useridFrom, $useridTo, $notifLink)
    {
        //on recupère les users from et to
        $model = new UserModel();
        $this->userFrom =   $model->getUserFromId($useridFrom);
        $this->userTo =     $model->getUserFromId($useridTo);

        //on construit le parent avec la langue a affiché qui depend de l'user de destinataire
        parent::__construct($this->userTo->langWebsite);

        //on definit le message traduit en fonction de l'user To
        $langModel = new LangModel();
        $contactNotif =     11; //code notif
        $this->traducedMessage = $langModel->getMessageLangNotif($this->userTo->langWebsite, $contactNotif);
        $this->traducedBt = $langModel->getMessageLangBtNotifMail($this->userTo->langWebsite, $contactNotif);
        $this->unsubscribeBt = $langModel->getBtUnsubscribe($this->userTo->langWebsite);

        //date notif
        $this->date = date("Y/m/d");

        //other parameters
        $this->linkNotif = $notifLink;

        //#todo refactoriser ds une fonction
        //DEFINITION DES INFOS A AFFICHER
        //show quickinfos
        $qiModel =                new QuickInfosModel();
        $userFromQuckinfos =      $qiModel->getQuickInfosFromIdUser($this->userFrom->pk_iduser);

        //#todo factoriser cette fonction qui apparait ds recommend contacts, tout les display contact et tout les mails de notif
        //si l'user possède un job courant
        if($userFromQuckinfos->current_company)
        {
            $this->lineJob = $userFromQuckinfos->jobtitle.' '.$this->langGenerals->word_at.' '.$userFromQuckinfos->current_company;
        }
        else{
            $this->lineJob = $this->langGenerals->title_infoLine_nationnality . $userFromQuckinfos->nationnality;
        }

        //si l'user possède une current team
        if($userFromQuckinfos->current_team)
        {
            $this->lineRole = $userFromQuckinfos->role.' '.$this->langGenerals->word_on.' '.$userFromQuckinfos->game.' '.$this->langGenerals->word_at.' '.$userFromQuckinfos->current_team;
        }
        else{
            if($userFromQuckinfos->previous_team)
            {
                $this->lineRole = $this->langGenerals->title_infoLine_previous_team . $userFromQuckinfos->previous_team;
            }
            else{
                $this->lineRole = $this->langGenerals->title_infoLine_languages . $userFromQuckinfos->language;
            }
        }

        //get user image real path
        if($this->userFrom->background_profile)
        {
            $img = new Images(false, $this->userFrom->pk_iduser);

            $fileFolder =           $img->getPublicProfileDir();
            $userProfileImgFolder = $img->getProfilePicUploadDir();;
            $thumbFolder =          $img->getThumbsDir();
            $imageName =            'thumb_w200_' . $this->userFrom->background_profile;
            $this->imgUser  =       $fileFolder . $userProfileImgFolder . $thumbFolder . $imageName;

            $root = ConfigAJAX::getROOT();
            $this->imgUser = str_replace($root, $this->baseLink, $this->imgUser);
        }
        else{
            $this->imgUser = $this->baseLink .'public/img/default/defaultprofile.jpg';
        }
    }

    public function showBody()
    {
        return '<body marginwidth="0" marginheight="0">
                    <table border="0" style="width:100%" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td rowspan="30" width="0%"></td>
                                <td colspan="10" width="80%"></td>
                                <td rowspan="30" width="0%"></td>
                            </tr>
                            '.  $this->headerNotifs .'
                             <tr>
                                <td style="font-size:10px;line-height:10px" height="10" colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td '. $this->normalTextStylesLeftBold .' valign="center" colspan="6">'. $this->userTo->nickname .',</td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;line-height:10px" height="10" colspan="6">&nbsp;</td>
                            </tr>                          
                            <tr>
                                <td '. $this->normalTextStylesLeftBold .' valign="center" colspan="6">'. $this->userFrom->firstname .' "'. $this->userFrom->nickname .'" '. $this->userFrom->lastname .' '. $this->traducedMessage .'</td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;line-height:10px" height="10" colspan="6">&nbsp;</td>
                            </tr> 
                            <tr>
                                <td width="10%" style="background-color:#F3F3F3;padding:5px;border-top: 2px solid rgba(243,177,75,1);border-left:2px solid rgba(243,177,75,1);border-top-left-radius:8px;" rowspan="3" align="center">
                                    <img style="width:50px; height:50px; margin:auto; border-radius:150px; border: 2px solid rgba(243,177,75,1);" src="'. $this->imgUser .'" alt="'. $this->userFrom->firstname .' \''. $this->userFrom->nickname .'\' '. $this->userFrom->lastname .' pic">
                                </td>
                                <td width="30%" style="font-weight:bold;background-color:#F3F3F3;font-family:\'Helvetica\',sans-serif;font-size:12px;border-top: 2px solid rgba(243,177,75,1);">
                                    '. $this->userFrom->firstname .' "'. $this->userFrom->nickname .'" '. $this->userFrom->lastname .'
                                </td>
                                <td width="10%" style="background-color:#F3F3F3;font-weight:bold;font-family:\'Helvetica\',sans-serif;font-size:12px;padding-left:10px;padding-right:10px;border-top: 2px solid rgba(243,177,75,1);border-right:2px solid rgba(243,177,75,1);border-top-right-radius:8px;" rowspan="3" align="center">
                                    '. $this->date .'
                                </td>                              
                            </tr>
                            <tr>
                                <td width="40%" style="background-color:#F3F3F3;font-family:\'Helvetica\',sans-serif;font-size:12px;">
                                    '. $this->lineRole .'
                                </td>
                            </tr>
                            <tr>
                                <td width="40%" style="background-color:#F3F3F3;font-family:\'Helvetica\',sans-serif;">
                                    '. $this->lineJob .'
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;line-height:10px;border-left: 2px solid rgba(243,177,75,1);border-right: 2px solid rgba(243,177,75,1);" width="80%" height="10" colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="border-left: 2px solid rgba(243,177,75,1);border-right: 2px solid rgba(243,177,75,1);font-size:20px;line-height:60px;text-align:right;padding-right:5%;" width="80%" height="10" colspan="6">
                                    <!--[if mso]>
                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="'. $this->baseLink . $this->linkNotif .'" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="25%" strokecolor="#ED7D31" fillcolor="#F9B14C">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">'. $this->traducedBt[0]->bt_view_profile .'</center>
                                    </v:roundrect>
                                    <![endif]-->
                                    <a href="'. $this->baseLink . $this->linkNotif .'" style="background-color:#F9B14C;border:4px solid #ED7D31;border-radius:15px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:14px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">'. $this->traducedBt[0]->bt_view_profile .'</a>
                                </td>
                            </tr>                          
                            <tr>
                                <td style="font-size:10px;line-height:10px;border-left: 2px solid rgba(243,177,75,1);border-right: 2px solid rgba(243,177,75,1);border-bottom: 2px solid rgba(243,177,75,1);border-bottom-left-radius:8px;border-bottom-right-radius:8px;" width="80%" height="10" colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;line-height:10px;" width="80%" height="10" colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="font-size:15px;line-height:30px;text-align:center;" valign="center" colspan="6">
                                    <a style="color:black;" href="'. $this->baseLink .'index.php?p=manageaccount" target="_blank">'. $this->unsubscribeBt .'</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;line-height:10px" width="80%" height="10" colspan="6">&nbsp;</td>
                            </tr>
                            <tr><td style="font-size:20px;line-height:20px" width="80%" height="20" colspan="6">&nbsp;</td></tr>
                            <tr><td style="font-size:10px;line-height:15px;text-align:center" valign="center" colspan="6">Copyright © 2016 World eSport Ltd</td></tr>
                            <tr><td style="font-size:10px;line-height:15px;text-align:center" valign="center" colspan="6"><a href="'. $this->baseLink .'index.php?p=terms-and-conditions" target="_blank">Terms &amp; Conditions</a> | <a href="'. $this->baseLink .'index.php?p=privacy" target="_blank">Privacy Statement</a> | <a href="'. $this->baseLink .'index.php?p=code-of-conduct" target="_blank">Code of Conduct</a></td></tr>
                            <tr><td style="font-size:20px;line-height:20px" width="80%" height="20" colspan="6">&nbsp;</td></tr>
                        </tbody>
                    </table>
                </body>';
    }

    public function show()
    {
        return $this->showBody();
    }
}