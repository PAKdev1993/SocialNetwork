//form vars
$signin_inputs =        $('#log-form input');
emailsignin_input =     $('input[name="email-log"]');
$pwdsignin_input =      $('input[name="pwd-log"]');
$remember_me =          $('input[name="remember-me"]');

//reset de password et test de l'existence de l'user
function resetPassword(email){
     $.ajax({
         method: 'POST',
         url: "inc/Auth/forget.php",
         data: {email: email}
         }).done(function(msg){
             if(msg == 'user issue')
             {
                 AJAXloader(false, '#loader-pass-forgot');
                 displayMessagesOut();
                 $('.error-field').addClass('novisible');
                 $('.error-forgot-exist').removeClass('novisible');
                 displayError([], '#pass-forgot-form', ['input[name="pass-forgot"]'], '');
             }
            if(msg == 'confirm issue')
             {
                 AJAXloader(false, '#loader-pass-forgot');
                 displayMessagesOut();
                 $('.error-field').addClass('novisible');
                 $('.error-forgot-confirm').removeClass('novisible');
                 displaySpecial([], '#pass-forgot-form', ['input[name="pass-forgot"]'], '');
             }
            //ce msg est un retour de la fonction de mail et non de la fonction de reset
             if(msg == 'success')
             {
                 AJAXloader(false, '#loader-pass-forgot');
                 displayMessagesOut();
                 $('.error-field').addClass('novisible');
                 $('.valid-forgot').removeClass('novisible');
                 displaySuccess([], '#pass-forgot-form', ['input[name="pass-forgot"]'], '');
             }
     });
}

//login user
function log(email, pwd, rememberme){
    $.ajax({
        method: 'POST',
        url: "inc/Auth/login.php",
        data: {email: email, pwd: pwd, rememberme:rememberme}
    }).done(function(msg){
        if(msg == 'user issue')
        {
            $('.error-field').addClass('novisible');
            $('.error-emaillog-invalid').removeClass('novisible');
        }
        if(msg == 'confirm issue')
        {
            $('.error-field').addClass('novisible');
            $('.error-confirm-account').removeClass('novisible');
        }
        if(msg == 'err')
        {
            $('.error-field').addClass('novisible');
            $('.error-emaillog-invalid').removeClass('novisible');
        }
        if(msg == 'logged')
        {
            window.location.reload();
        }
    });
};

//afficher le champ email sur clique on "pwd forgot" et reset des champs email et password
$('#pwd-forgot').click(function(){
    viderChamps('#log-form');
    $('.error-field').addClass('novisible');
    $('#log-form-bloc').addClass('log-form-bloc-active');
});

//verifications de la validité des infos du formulaire de login
$('input[name="signin"]').click(function(){

    $errors = new Array();
    var regexEmail = "^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$";

    if (emailsignin_input.val() == '' || !emailsignin_input.val().match(regexEmail) || $pwdsignin_input.val() == '') {
        $('.error-field').addClass('novisible');
        $('.error-emaillog-invalid').removeClass('novisible');
        displayError($errors, '#log-form', $signin_inputs);
        return false;
    }
    else {
        log(emailsignin_input.val(),  $pwdsignin_input.val(), $remember_me.is(':checked'));
        return false;
    }
});

//verifications de la validité des infos du formulaire de reset password
$('input[name="send-new-pwd"]').click(function(){
    var regexEmail = "^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$";

    $emailreset_input =     $('input[name="pass-forgot"]');

    if($emailreset_input.val() == "" || !$emailreset_input.val().match(regexEmail))
    {
        $('.error-field').addClass('novisible');
        $('.error-forgot-email').removeClass('novisible');
        displayError([], '#pass-forgot-form', ['input[name="pass-forgot"]'], ''); //#todo changer cette fonction en une fonction qui color en rouge les border
        return false;
    }
    AJAXloader(true, '#loader-pass-forgot');
    resetPassword($emailreset_input.val());
    return false;
});
