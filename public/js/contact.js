function popupContact(datas){
    $.ajax({
        method: 'POST',
        url: "inc/Contact/prepareContact.php",
        data: {datas:datas}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('.viewer-content').html('').append(msg);
            $('.viewer').addClass('active');
            $('.wrap').addClass('active');
        }
        else{
            window.location.reload();
        }
    });
}

function contact(object, message, action){
    $.ajax({
        method: 'POST',
        url: "inc/Contact/contact.php",
        data: {object:object, message:message, action:action}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('.contact-box').addClass('sent');
        }
        else{
            window.location.reload();
        }
    });
}

/*
 * REPORT ABUSE
 */
$(document).on('click', 'button[data-action="send-report"]', function(e){ //#todo en s'inspirant de ce model on peux dimaiser l'affichage des messages ds une classe
    //Actions parameters
    var action = $(this).attr('data-action');

    //Forms controls
    $object =   $('#report-abuse-object');
    $details =  $('#report-abuse-message');

    if($.trim($object.text()) == '')
    {
        $('.box .error-field').addClass('novisible');
        $('.box .error-object').removeClass('novisible');
        return false;
    }
    if($.trim($details.text()) == '')
    {
        $('.box .error-field').addClass('novisible');
        $('.box .error-details').removeClass('novisible');
        return false;
    }

    //display form
    AJAXloader(true, '#loader-mail-abuse');
    contact($.trim($object.text()), $.trim($details.text()), action);
});

/*
 * CONTACT
 */
$(document).on('click', 'button[data-action="send-contact"]', function(e){ //#todo en s'inspirant de ce model on peux dimaiser l'affichage des messages ds une classe
    //Actions parameters
    var action = $(this).attr('data-action');

    //Forms controls
    $object =   $('#contact-object');
    $details =  $('#contact-message');

    if($.trim($object.text()) == '')
    {
        $('.box .error-field').addClass('novisible');
        $('.box .error-object').removeClass('novisible');
        return false;
    }
    if($.trim($details.text()) == '')
    {
        $('.box .error-field').addClass('novisible');
        $('.box .error-details').removeClass('novisible');
        return false;
    }
    AJAXloader(true, '#loader-mail-contact');

    if(action == "cancel-contact")
    {
        $('.wrap').removeClass('active');
        $('.viewer').removeClass('active');
        $('.viewer-content').html('');
        return false;
    }
    contact($.trim($object.text()), $.trim($details.text()), action);
});
/*
 * CANCEL SEND
 */
$(document).on('click', 'button[data-action="cancel-contact"]', function(e){ //#todo en s'inspirant de ce model on peux dimaiser l'affichage des messages ds une classe
    $('.wrap').removeClass('active');
    $('.viewer').removeClass('active');
    $('.viewer-content').html('');
});
/*
 * OPEN POPUP
 */
//contact
$(document).on('click', '#footer a[data-action]', function(e){ //#todo en s'inspirant de ce model on peux dimaiser l'affichage des messages ds une classe
    var datas = $(this).attr('data-action');
    popupContact(datas);
});

/*
 * CLOSE POPUP AT THE END
 */
$(document).on('click', 'button[data-close="valid-sent"]', function(e){
    $('.viewer').removeClass('active');
    $('.wrap').removeClass('active');
    $('.viewer-content').html('');
});

