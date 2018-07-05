$password = $("input[name='pwd-reset']");
$passwordmatch = $("input[name='pwd-match-reset']");

//LOAD LE LOGO
imgSmoothLoading();

//FORM CONTROLS
$('input[name="reset"]').click(function(){

    $regexPwd =  "(?=.{6,}).*";

    if(!$password.val().match($regexPwd))
    {
        $('.error-field').addClass('novisible');
        $('.error-password-size').removeClass('novisible');
        displayError([], '#signup-bloc', $password, 10);
        return false;
    }
    if($password.val() == "" || $password.val() != $passwordmatch.val())
    {
        $('.error-field').addClass('novisible');
        $('.error-password-match').removeClass('novisible');
        displayError([], '#signup-bloc', $passwordmatch, 10);
        return false;
    }
    return true;
});
