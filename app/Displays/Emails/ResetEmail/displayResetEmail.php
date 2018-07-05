<?php
namespace app\Displays\Emails\ResetEmail;

use app\Table\AppEmailElems;
use app\Table\UserModel\UserModel;
use app\App;

class displayResetEmail extends AppEmailElems
{
    private $resetlink;

    public function __construct($tokenReset, $userid)
    {
        parent::__construct();
        $this->resetlink =      $this->baseLink . 'inc/Auth/prepareReset.php?id='. $userid .'&token='. $tokenReset.'';
    }

    public function showBody()
    {
        return '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            '. $this->header .'
                            <tr>
                                <td class="padding-left" rowspan="20" width="5%"></td>
                                <td class="padding-top" width="90%" height="20" style="font-size:20px;line-height:20px;">&nbsp;</td>
                                <td class="padding-left" rowspan="20" width="5%"></td>
                            </tr>
                            '. $this->spaceLittleX .'                           
                            <tr>
                                <td valign="center" height="150" style="font-size:20px;line-height:100px;text-align:center;">
                                    <!--[if mso]>
                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="'. $this->resetlink .'" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="25%" strokecolor="#ED7D31" fillcolor="#F9B14C">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">Show me the button!</center>
                                    </v:roundrect>
                                    <![endif]-->
                                    <a href="'. $this->resetlink .'" style="background-color:#F9B14C;border:4px solid #ED7D31;border-radius:15px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:18px;font-weight:bold;line-height:51px;text-align:center;text-decoration:none;width:263px;-webkit-text-size-adjust:none;mso-hide:all;">Reset your password</a>
                                </td>
                            </tr>
                            <tr>
                                <td valign="center" '. $this->normalTextStylesCenterBold .'>If you can’t click the link above, please click:</td>
                            </tr>
                            <tr>
                                <td valign="center" '. $this->normalTextStylesCenterBold .'>'. $this->resetlink .'</td>
                            </tr>
                            '. $this->spaceBig .'
                            <tr>
                                <td width="80%" '. $this->normalTextStylesCenterBold .'>Your World eSport team</td>
                            </tr>
                            '. $this->spaceBig .'
                            '. $this->spaceBig .'
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