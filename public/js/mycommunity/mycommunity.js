//BLOCK | UNBLOCK CONTACT
function blockContact(datas, indexElem, indexParent) {
    $.ajax({
        method: 'POST',
        url: "inc/MyCommunity/block.php",
        data: {profileid:datas}
    }).done(function(msg) {
        if(msg != 'err')
        {
            domParent = $('.alph-bloc').get(indexParent);
            $mainParent = $(domParent);
            domElem = $mainParent.find('.community-elem').get(indexElem);
            $elem = $(domElem);
            $elem.html(msg);
            imgSmoothLoading();
        }
        else{
            window.location.reload();
        }
    });
};

//SEND INVITATION
function sendAskForAdd(dataid) {
    $.ajax({
        method: 'POST',
        url: "inc/MyCommunity/askForAdd.php",
        data: {profileid:dataid}
    }).done(function(msg) {
        if(msg != 'err')
        {
            $elem = $('button[data-add="' + dataid + '"]').parents('.contact-container');
            AJAXloader(false, $elem.find('.loader-container'));
            $elem.find('.add-container').remove();
            $elem.append(msg);
        }
        else{
            window.location.reload();
        }
    });
};


//CANCEL INVITATION FROM RECOMMENDED CONTACTS
function cancelAdd(dataid) {
    $.ajax({
        method: 'POST',
        url: "inc/MyCommunity/deleteAskForAdd.php",
        data: {profileid:dataid}
    }).done(function(msg) {
        if(msg != 'err')
        {
            $elem = $('button[data-cancel-add="' + dataid + '"]').parents('.contact-container');
            $elem.removeClass('request-sent');
            AJAXloader(false, $elem.find('.loader-container'));
            $elem.html(msg);
            imgSmoothLoading();
        }
        else{
            window.location.reload();
        }
    });
};

//ACCEPT INVITATION
function acceptAdd(dataid) {
    $.ajax({
        method: 'POST',
        url: "inc/MyCommunity/acceptAdd.php",
        data: {profileid:dataid}
    }).done(function(msg) {
        if(msg != 'err') {
            $elem = $('button[data-accept-add="' + dataid + '"]').parent();
            $elem.parents('.community-elem-right-part').addClass('active-accept');
        }
        else{
            window.location.reload();
        }
    });
};

//DECLINE INVITATION
function declineAdd(dataid) {
    $.ajax({
        method: 'POST',
        url: "inc/MyCommunity/declineAsk.php",
        data: {profileid:dataid}
    }).done(function(msg) {
        if(msg != 'err') {
            if(msg)
            {
                $('#slide-mypendingcontacts').html(msg);
            }
        }
        else{
            window.location.reload();
        }
    });
};

/*----------------------------------------------------------------------*\
 * HASH EVENTS
\*----------------------------------------------------------------------*/
$(window).on('hashchange',function(e){
    var hash = document.location.hash;
    $('a[href="'+ hash + '"]').parent().trigger('click');
});

//#todo mettre en clik peut etre
$('.body').on('click', '.item-nav-profile', function(){
    $(this).parent().find('li').removeClass('active');
    $(this).addClass('active');
    //slide en fonction de l'elment cliqué
    var index = $(this).index();
    var newMarge = '-' + 100*index + '%';
    $(this).parents('#mycommunity-bottom').find('.slider-profile-chapters').css('margin-left',newMarge);
});

$(document).ready(function() {
    var hash = document.location.hash;
    $('a[href="'+ hash + '"]').parent().trigger('click');
});
/*----------------------------------------------------------------------*\
 * ALPHABET GOTO
\*----------------------------------------------------------------------*/
$('#slide-mycontacts').on('click', '.letter', function(e){
    var datagoto =              $(this).attr('data-goto');
    var posRef =                $('#slide-mycontacts').offset().top;
    var posTogo =               $('div[data-alph-index="'+ datagoto +'"]').offset().top;
    var titleBlocHeightToSub =  $('#slide-mycontacts .title-aside-bloc').height();
    var toAdd =                 posTogo - posRef - titleBlocHeightToSub - 10; //-10 pour ajouter un peu de marge, uniquement graphique
    var actualScrolPosition =   $('#my-contacts-container').scrollTop();
    var newScrollPosition =     actualScrolPosition + toAdd;

    var animSpeed = 500;
    $('#my-contacts-container').animate({scrollTop: newScrollPosition + "px"}, animSpeed , 'easeInOutQuint');
});

$('#slide-myfollowers').on('click', '.letter', function(e){
    var datagoto =              $(this).attr('data-goto');
    var posRef =                $('#slide-myfollowers').offset().top;
    var posTogo =               $('div[data-alph-index="'+ datagoto +'"]').offset().top;
    var titleBlocHeightToSub =  $('#slide-myfollowers .title-aside-bloc').height();
    var toAdd =                 posTogo - posRef - titleBlocHeightToSub - 10; //-10 pour ajouter un peu de marge, uniquement graphique
    var actualScrolPosition =   $('#my-followers-container').scrollTop();
    var newScrollPosition =     actualScrolPosition + toAdd;

    var animSpeed = 500;
    $('#my-followers-container').animate({scrollTop: newScrollPosition + "px"}, animSpeed , 'easeInOutQuint');
});
/*----------------------------------------------------------------------*\
 * BOCK / UNBLOCK
\*----------------------------------------------------------------------*/
$('.body').on('click', 'button[data-blocked]', function(e) {
    $mainParent = $(this).parents('#my-contacts-container .alph-bloc');
    $elemParent = $(this).parents('#my-contacts-container .community-elem');
    AJAXloader(true, $elemParent.find('loader-container'));

    var elemIndex = $elemParent.index();
    var parentIndex = $mainParent.index();
    var datas = $(this).attr('data-blocked');
    blockContact(datas, elemIndex, parentIndex);
});
/*----------------------------------------------------------------------*\
 * ADD CONTACT FROM RECOMMENDED USERS: SEND ASK / CANCEL ASK
\*----------------------------------------------------------------------*/
$('.body').on('click', 'button[data-add]', function(e) {
    $elemParent = $(this).parents('.contact-container');
    $elemParent.addClass('request-sent');
    AJAXloader(true, $elemParent.find('.loader-container'));
    var dataid = $(this).attr('data-add');
    sendAskForAdd(dataid);
});

$('.body').on('click', 'button[data-cancel-add]', function(e) {
    $elemParent = $(this).parents('.contact-container');
    AJAXloader(true, $elemParent.find('.loader-container'));
    var dataid = $(this).attr('data-cancel-add');
    cancelAdd(dataid);
});
/*----------------------------------------------------------------------*\
 * ADD CONTACT FROM CONTACT PENDING: ACCEPT ASK / DECLINE ASK
\*----------------------------------------------------------------------*/
$('.body').on('click', 'button[data-accept-add]', function(e) {
    var dataid = $(this).attr('data-accept-add');
    acceptAdd(dataid);
});

$('.body').on('click', 'button[class*="decline-add"]', function(e) {
    $elemParent = $(this).parents('.bt-more-container');
    $elemParent.addClass('active-confirm');
});

$('.body').on('click', 'button[data-action="back"]', function(e) {
    $elemParent = $(this).parents('.bt-more-container');
    $elemParent.removeClass('active-confirm');
});

$('.body').on('click', 'button[data-decline-add]', function(e) {
    //collapse bloc
    $elemParent = $(this).parents('.community-elem');
    $elemParent.addClass('active-delete');

    //send infos to delete invitation
    var dataid = $(this).attr('data-decline-add');
    declineAdd(dataid);
});

