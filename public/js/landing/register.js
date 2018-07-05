//form vars
$fname =        $('input[name="fname-signup"]');
$lname =        $('input[name="lname-signup"]');
$nickname =     $('input[name="nickname-signup"]');
$emailsignup =  $('input[name="email-signup"]');
$pwdsignup =    $('input[name="pwd-signup"]');
$pwdmatch =     $('input[name="pwd-match-signup"]');
$cdu =          $('#cdu-checkbox');

//AJAX inscription || test de l'unicité du mail
function register(fname, lname, nickname, email, pwd, invited, invitedByToken) {
    $.ajax({
        method: 'POST',
        url: "inc/Auth/register.php",
        data: {fname: fname, lname: lname, nickname: nickname, email: email, pwd: pwd, invited: invited, invitedByToken:invitedByToken}
    }).done(function(msg){
        if(msg == 'email already used')
        {
            $('.error-field').addClass('novisible');
            $('.error-email-inuse').removeClass('novisible');
            AJAXloader(false);
        }
        else{
            $('.error-field').addClass('novisible');
            $('.valid-account').removeClass('novisible');
            viderChamps('#signup-bloc form');
            AJAXloader(false);
        }
    });
};

//verifications du formulaire d'inscription
$('input[name="submit-signup"]').click(function(){
    var errors = new Array();
    var regexName = "^[a-zA-ZéèàëäöüÄÖÜß._-]";
    var regexEmail = "^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$";
    var regexPwd =  "(?=.{6,}).*";

    if($fname.val() == '' || !$fname.val().match(regexName))
    {
        $('.error-field').addClass('novisible');
        $('.error-firstname').removeClass('novisible');
        displayError(errors, '#signup-bloc', $fname, 0); //#todo après modification du system, cette fonction ne sert plus qu'a colorer la bordure en rouge, mettre ca ds le css
        return false;
    }
    if($lname.val() == "" || !$lname.val().match(regexName))
    {
        $('.error-field').addClass('novisible');
        $('.error-lastname').removeClass('novisible');
        displayError(errors, '#signup-bloc', $lname, 0);
        return false;
    }
    if($nickname.val() == "" || !$nickname.val().match(regexName))
    {
        $('.error-field').addClass('novisible');
        $('.error-nickname').removeClass('novisible');
        displayError(errors, '#signup-bloc', $nickname, 0);
        return false;
    }
    if($emailsignup.val() == "" || !$emailsignup.val().match(regexEmail))
    {
        $('.error-field').addClass('novisible');
        $('.error-email-invalid').removeClass('novisible');
        displayError(errors, '#signup-bloc', $emailsignup, 0);
        return false;
    }
    if($pwdsignup.val() == "" )
    {
        $('.error-field').addClass('novisible');
        $('.error-password-invalid').removeClass('novisible');
        displayError(errors, '#signup-bloc', $pwdsignup, 0);
        return false;
    }
    if(!$pwdsignup.val().match(regexPwd))
    {
        $('.error-field').addClass('novisible');
        $('.error-password-invalid').removeClass('novisible');
        displayError(errors, '#signup-bloc', $pwdsignup, 0);
        return false;
    }
    if($pwdmatch.val() == "" || $pwdsignup.val() != $pwdmatch.val())
    {
        $('.error-field').addClass('novisible');
        $('.error-password-match').removeClass('novisible');
        displayError(errors, '#signup-bloc', $pwdmatch, 0);
        return false;
    }
    if($cdu.is(':checked') == false)
    {
        $('.error-field').addClass('novisible');
        $('.error-cdu').removeClass('novisible');
        displayError(errors, '#signup-bloc', $cdu, 0);
        return false;
    }
    AJAXloader(true, '#pg-1 .loader-container');
    if(getURLParameter('p') == 'invitation')
    {
        if(getURLParameter('tki') != '')
        {
            var token = getURLParameter('tki');
            register($.trim($fname.val()), $.trim($lname.val()), $.trim($nickname.val()), $emailsignup.val(), $pwdsignup.val(), true, token);
            return false;
        }
        else{
            register($.trim($fname.val()), $.trim($lname.val()), $.trim($nickname.val()), $emailsignup.val(), $pwdsignup.val(), true, false);
            return false;
        }
    }
    else{
        register($.trim($fname.val()), $.trim($lname.val()), $.trim($nickname.val()), $emailsignup.val(), $pwdsignup.val(), false, false);
        return false;
    }
});