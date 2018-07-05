//SEND INVITATION
function addUser(dataid) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/askForAddUser.php",
        data: {profileid:dataid}
    }).done(function(msg) {
        if(msg != 'err')
        {
            $('.contacts .bt-add-container').html(msg);
        }
        else{
            window.location.reload();
        }
    });
};

//FOLLOW USER
function followUser(dataid) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/followUser.php",
        data: {profileid:dataid}
    }).done(function(msg) {
        if(msg != 'err')
        {
            $('.folowers .bt-add-container').html(msg);
        }
        else{
            window.location.reload();
        }
    });
};

/*----------------------------------------------------------------------*\
 * ADD CONTACT FROM PROFILE USER
 \*----------------------------------------------------------------------*/
$('.body').on('click', 'button[data-toadd]', function(e) {
    var userid = $(this).attr('data-toadd');
    addUser(userid);
});

$('.body').on('click', 'button[data-cancelask]', function(e) {
    var userid = $(this).attr('data-cancelask');
    addUser(userid);
});

$('.body').on('click', 'button[data-toremove]', function(e) {
    $elemToIncrease = $('.counter-contacts');
    counter = parseInt($elemToIncrease.text()) - 1;
    $elemToIncrease.text(counter);
    var userid = $(this).attr('data-toremove');
    addUser(userid);
});

/*----------------------------------------------------------------------*\
 * FOLLOW FROM PROFILE USER
\*----------------------------------------------------------------------*/
$('.body').on('click', 'button[data-tofollow]', function(e) {
    $elemToIncrease = $('.counter-folower');
    counter = parseInt($elemToIncrease.text()) + 1;
    $elemToIncrease.text(counter);
    var userid = $(this).attr('data-tofollow');
    followUser(userid);
});

$('.body').on('click', 'button[data-tounfollow]', function(e) {
    $elemToIncrease = $('.counter-folower');
    counter = parseInt($elemToIncrease.text()) - 1;
    $elemToIncrease.text(counter);
    var userid = $(this).attr('data-tounfollow');
    followUser(userid);
});