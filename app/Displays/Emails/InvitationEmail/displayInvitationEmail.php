<?php
namespace app\Displays\Emails\InvitationEmail;

use app\Table\AppEmailElems;
use core\Session\Session;

class displayInvitationEmail extends AppEmailElems
{
    private $currentUser;

    public function __construct()
    {
        parent::__construct();
        $this->currentUser = Session::getInstance()->read('auth');
    }

    public function showBody()
    {
        return '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            '. $this->header .'
                            <tr>
                                <td class="padding-left" rowspan="30" width="5%"></td>
                                <td class="padding-top" width="90%"></td>
                                <td class="padding-left" rowspan="30" width="5%"></td>
                            </tr>
                            '. $this->spaceLittleX .'
                            <tr>
                                <td valign="center" width="80%" height="50" '. $this->normalTextStylesCenterBold .'>Join us on World eSport!</td>
                            </tr>
                            <tr>
                                <td valign="center" width="80%" '. $this->normalTextStylesLeftBold .'>'. $this->currentUser->firstname .' “'. $this->currentUser->nickname .'” '. $this->currentUser->lastname .' has invited you to join World eSport, the Ultimate Social Network for gamers, video game professionals, eSport enthusiasts and fanatics, where you will be able to:</td>
                            </tr>
                            '. $this->spaceLittle .'
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>- Create your profile and share your eSport information</td>
                            </tr>
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>- Connect with like-minded people & eSport enthusiasts from all around the world</td>
                            </tr>
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>- Share video games & eSport related news and content</td>
                            </tr>
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>- Follow your favourite players, companies or teams & get their latest news</td>
                            </tr>
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>- And much more to discover</td>
                            </tr>
                           '. $this->spaceLittle .'
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>Click <a style="color:rgba(249,177,75,1);" href="'. $this->WEinvitationLink .'">Here</a> to access the website. If it doesn’t work, you can also copy paste the following link into your internet browser. '. $this->WEinvitationLinkHTML .'</td>
                            </tr>
                           '. $this->spaceLittle .'
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>Start using World eSport actively, share it with your friends and invite them to connect. Support the growth and development of eSport. Make eSport a real sport, it is finally in your hands to make this happen!</td>
                            </tr>
                            '. $this->spaceLittle .'
                            <tr>
                                <td valign="center" '. $this->normalTextStylesLeftBold .'>Your World eSport team</td>
                            </tr>
                            '. $this->spaceLittle .'
                            <tr>
                                <td valign="center" '. $this->normalTextStylesCenterBold .'>You can also follow us on:</td>
                            </tr>
                            '. $this->socialBloc .'
                            '. $this->spaceMedium .'
                            '. $this->copyright .'
                            '. $this->termsAndConditions .'
                            '. $this->spaceMedium .'
                        </tbody>
                    </table>
                </body>';
    }

    public function show()
    {
        return $this->showBody();
    }
}