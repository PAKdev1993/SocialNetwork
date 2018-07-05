<?php
namespace app\Table;

use app\App;

class AppEmailElems
{
    protected $spaceBig;
    protected $spaceMedium;
    protected $spaceLittle;
    protected $spaceLittleX;

    protected $normalTextStylesCenter;
    protected $normalTextStylesLeft;
    protected $normalTextStylesLeftBold;
    protected $normalTextStylesCenterBold;

    protected $copyright;
    protected $termsAndConditions;

    protected $facebookLink;
    protected $linkedInLink;
    protected $instagramLink;
    protected $twitterLink;

    protected $faceBookImg;
    protected $linkedInImg;
    protected $twitterImg;
    protected $instagramImg;

    protected $socialBloc;

    protected $imgHeaderLink;
    protected $header;

    protected $WEinvitationLinkHTML;
    protected $WElinkHTML;
    protected $WEinvitationLink;
    protected $WElink;
    protected $WEtermsConditions;
    protected $WEprivacy;
    protected $WEcodeofconduct;

    //LANGS
    protected $langFile;
    protected $langGenerals;

    public function __construct($langtodisplayInMail = false)
    {
        //BASE LINK
        $this->baseLink = "localhost/WEindev/";
        //TR SPACES STANDARTS
        $this->spaceBig =       '<tr><td class="space" width="80%" height="50" style="font-size:30px;line-height:30px;">&nbsp;</td></tr>';
        $this->spaceMedium =    '<tr><td class="space" width="80%" height="20" style="font-size:20px;line-height:20px;">&nbsp;</td></tr>';
        $this->spaceLittle =    '<tr><td class="space" width="80%" height="10" style="font-size:10px;line-height:10px;">&nbsp;</td></tr>';
        $this->spaceLittleX =   '<tr><td class="space" width="80%" height="5" style="font-size:5px;line-height:5px;">&nbsp;</td></tr>';

        //STYLES FOR NOTMAL TEXT BLOC
        $this->normalTextStylesCenter =     'style="font-size:13px; line-height:20px; text-align:center; font-family:\'Hellvetica\', sans-serif"';
        $this->normalTextStylesLeft =       'style="font-size:13px; line-height:20px; text-align:left; font-family:\'Hellvetica\', sans-serif"';
        $this->normalTextStylesLeftBold =   'style="font-size:13px; line-height:20px; text-align:left; font-weight: bold; font-family:\'Hellvetica\', sans-serif"';
        $this->normalTextStylesCenterBold = 'style="font-size:13px; line-height:20px; text-align:center; font-weight: bold; font-family:\'Hellvetica\', sans-serif"';

        //LINKS & IMAGES
        $this->facebookLink =   'http://www.facebook.com/worldesport.ltd';
        $this->linkedInLink =   'http://www.linkedin.com/company/world-esport';
        $this->instagramLink =  'http://www.instagram.com/worldesport.ltd';
        $this->twitterLink =    'http://www.twitter.com/WorldeSport_Ltd';

        $this->faceBookImg =    'http://worldesport.com/public/img/email/fb.png';
        $this->linkedInImg =    'http://worldesport.com/public/img/email/linkedIn.png';
        $this->twitterImg =     'http://worldesport.com/public/img/email/twitter.png';
        $this->instagramImg =   'http://worldesport.com/public/img/email/insta.png';

        //BLOC SOCIAL
        $this->socialBloc = '<tr><td width="80%" valign="center" align="center" style="text-align:center"><a href="'. $this->facebookLink .'"><img width="30" height="30" src="'. $this->faceBookImg .'"></a>&nbsp;<a href="'. $this->twitterLink .'"><img width="30" height="30" src="'. $this->twitterImg .'"></a>&nbsp;<a href="'. $this->linkedInLink .'"><img width="30" height="30" src="'. $this->linkedInImg .'"></a>&nbsp;<a href="'. $this->instagramLink .'"><img width="30" height="30" src="'. $this->instagramImg .'"></a></td></tr>';

        //HEADER BLOC & IMAGES
        $this->imgHeaderLink =      'http://worldesport.com/public/img/email/bgconfirm.jpg';
        $this->imgHeaderNotifs =    'http://worldesport.com/public/img/email/headerNotifs.jpg';
        /*Old $this->header = '<tr>
                            <!--[if gte mso 9]>
                                <div style="background-color:#7bceeb;">
                                    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                                        <v:fill type="tile" src="http://i.imgur.com/XPCwjiX.jpg" color="#7bceeb"/>
                                    </v:background>
                                </div>
                            <![endif]-->
                            <td width="80%" valign="top" align="center" colspan="3"><img width="100%" src="'. $this->imgHeaderLink .'"></td>
                        </tr>';*/

        $this->header = '<tr>                           
                            <td width="80%" valign="top" align="center" colspan="3"><img width="100%" src="'. $this->imgHeaderLink .'"></td>
                         </tr>';
        $this->headerNotifs =   '<tr>                           
                                    <td width="80%" valign="top" align="center" colspan="6"><img width="80%;margin-left:10%;" src="'. $this->imgHeaderNotifs .'"></td>
                                 </tr>';

        //WE Links
        $this->WEinvitationLinkHTML =   '<a href="'.App::getWeInvitationLink().'">'.App::getWeInvitationLink().'</a>';
        $this->WElinkHTML =             '<a href="'.App::getWElink().'">'.App::getWElink().'</a>';
        $this->WEinvitationLink =       App::getWeInvitationLink();
        $this->WEtermsConditions =      $this->baseLink . "index.php?p=terms-and-conditions";
        $this->WEprivacy =              $this->baseLink . "index.php?p=privacy";
        $this->WEcodeofconduct =        $this->baseLink . "index.php?p=code-of-conduct";

        //COPYRIGHTS & Terms & conficitons... BLOCS
        $this->copyright =          '<tr><td valign="center" style="font-size:10px;line-height:15px;text-align:center;">Copyright © 2016 World eSport Ltd</td></tr>';
        $this->termsAndConditions = '<tr><td valign="center" style="font-size:10px;line-height:15px;text-align:center;"><a href="'. $this->WEtermsConditions .'">Terms & Conditions</a> | <a href="'. $this->WEprivacy .'">Privacy Statement</a> | <a href="'. $this->WEcodeofconduct .'">Code of Conduct</a></td></tr>';

        //LANG FILE
        if($langtodisplayInMail)
        {
            $this->langFile =       App::getLangModel()->getMailLangs();
            $this->langGenerals =   App::getLangModel()->getLangGenerals($langtodisplayInMail);
        }
        else{
            $this->langGenerals =   App::getLangModel()->getLangGenerals();
        }

    }
}