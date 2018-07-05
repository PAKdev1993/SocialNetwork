<?php
namespace app\Displays\Emails\ContactEmail;

use app\Table\AppEmailElems;
use core\Session\Session;

class displayContactEmail extends AppEmailElems
{
    private $currentUser;
    private $object;
    private $message;

    public function __construct($object, $message)
    {
        parent::__construct();
        $this->currentUser =    Session::getInstance()->read('auth');
        $this->object =         $object;
        $this->message =        $message;
    }

    public function showBody()
    {
        return '<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                             <tr>
                                <td class="padding-left" rowspan="30" width="5%"></td>
                                <td class="padding-top" width="90%"></td>
                                <td class="padding-left" rowspan="30" width="5%"></td>
                            </tr>
                            <tr>
                                <td width="100" style="font-weight:bold">OBJECT :</td>                               
                            </tr>
                            <tr>
                                <td height="20" style="font-size:20px;line-height:20px;text-align:left">'. $this->object .'</td>
                            </tr>
                            '. $this->spaceMedium .'
                            <tr>
                                <td width="100" style="font-weight:bold">MESSAGE :</td>
                            </tr>
                            <tr>
                                <td height="20" style="font-size:20px;line-height:20px;text-align:left">'. $this->message .'</td>
                            </tr>
                            '. $this->socialBloc .'
                            '. $this->spaceMedium .'
                            '. $this->copyright .'                           
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