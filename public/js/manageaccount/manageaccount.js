function saveAccountParameters(
    email, pwd,
    notifOnAsk, notifOnAskAccepted, notifOnLike, notifOnComment,
    emailOnAsk, emailOnAskAccepted, emailOnLike, emailOnComment, emailOnFollow,
    weeklyEmailVisitors, weeklyRecmdContacts
)
{
    $.ajax({
        method: 'POST',
        url: "inc/ManageAccount/updateAccountParameters.php",
        data: {
            email: email,
            pwd: pwd,
            notifOnAsk: notifOnAsk,
            notifOnAskAccepted: notifOnAskAccepted,
            notifOnLike: notifOnLike,
            notifOnComment: notifOnComment,
            emailOnAsk:emailOnAsk,
            emailOnAskAccepted:emailOnAskAccepted,
            emailOnLike:emailOnLike,
            emailOnComment:emailOnComment,
            emailOnFollow:emailOnFollow,
            weeklyEmailVisitors:weeklyEmailVisitors,
            weeklyRecmdContacts:weeklyRecmdContacts
        }
    }).done(function(msg){
        if(msg != 'err' && msg !='err_email')
        {
            $('.manage-elems-container').html(msg);
            setTimeout(
                function () {
                    $("input[name='manage_password']").val(''); //#todo CHANGER CA
                }, 500);
            AJAXloader(false);
        }
        if(msg == 'err_email')
        {
            AJAXloader(false);
            $("input[name='manage_email']").attr('value',email);
            $('.error-email-inuse').removeClass('novisible');
        }
        else{
            //window.location.reload();
        }
    });
}

/* PREPARE */
//sert a empecher l'autocompletion ds le cas ou l'utilisateur a choisi: enregistrer mon mot de passe lor de la connexion
setTimeout(
    function () {
        $("input[name='manage_password']").val(''); //#todo CHANGER CA
    }, 500);

//verifications du formulaire d'inscription
$('button[data-action="save-account-parameters"]').click(function(){ //#todo simplifier la gestion de ce formulaire
    
    $email =        $("input[name='manage_email']");
    $email.attr('value',$email.val());

    $pwd =          $("input[name='manage_password']");
    $pwdconfirm =   $("input[name='manage_password_confirm']");

    $notifOnAsk =           $("input[name='notif_when_user_wtb_part_of_network']");
    $notifOnAskAccepted =   $("input[name='notif_when_user_accept_contact_request']");
    $notifOnLike =          $("input[name='notif_when_user_like_post']");
    $notifOnComment =       $("input[name='notif_when_user_comment_post']");

    $emailOnAsk =           $("input[name='email_when_user_wtb_part_of_network']");
    $emailOnAskAccepted =   $("input[name='email_when_user_accept_contact_request']");
    $emailOnLike =          $("input[name='email_when_user_like_post']");
    $emailOnComment =       $("input[name='email_when_user_comment_post']");
    $emailOnFollow =        $("input[name='email_when_user_follow_you']");

    $weeklyEmailVisitors =  $("input[name='email_weekly_summary_of_visitors']");
    $weeklyRecmdContacts =  $("input[name='email_weekly_list_of_recommended_contacts']");

    regexName =     "^([a-zA-Z-]{2,36})$";
    regexEmail =    "^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$";
    regexPwd =      "(?=.{6,}).*";

    if($.trim($email.val()) == '' || !$.trim($email.val()).match(regexEmail))
    {
        $('.error-email').addClass('novisible');
        $('.error-email-invalid').removeClass('novisible');
        return false;
    }
    if($pwd.val() != "" && !$pwd.val().match(regexPwd))
    {
        $('.error-email').addClass('novisible');
        $('.error-password').removeClass('novisible');
        $('.error-password-match').addClass('novisible');
        return false;
    }
    if($pwd.val() != "" && $pwd.val() != $pwdconfirm.val())
    {
        $('.error-email').addClass('novisible');
        $('.error-password').addClass('novisible');
        $('.error-password-match').removeClass('novisible');
        return false;
    }
    else{
        $('.error-input-container').addClass('novisible');
        AJAXloader(true, '#loader-manage-account');
        saveAccountParameters(
            $.trim($email.val()),
            $pwd.val(),
            $notifOnAsk.is(':checked'),
            $notifOnAskAccepted.is(':checked'),
            $notifOnLike.is(':checked'),
            $notifOnComment.is(':checked'),
            $emailOnAsk.is(':checked'),
            $emailOnAskAccepted.is(':checked'),
            $emailOnLike.is(':checked'),
            $emailOnComment.is(':checked'),
            $emailOnFollow.is(':checked'),
            $weeklyEmailVisitors.is(':checked'),
            $weeklyRecmdContacts.is(':checked')
        );
        return false;
    }
});


