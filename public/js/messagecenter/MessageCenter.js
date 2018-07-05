$(function () {
    slideFromLeftItems($('.discussions-container .slidedLeft'));
});

function dispayLastDiscussion() {
    //remove discussion
    $('div[data-elem="discussion-content"]').html('');

    //active loader
    activeLoaderDiscussion();

    //on get la dernière discussion
    var convidToLoad = $('.discussions-container').find('div[data-elem="ap-discussion"]').eq(1).attr('data-conv');
    getConv(convidToLoad, 'discussion', false, '');
}

function searchConv(string)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/searchMessageCenter.php",
        data: {tosearch: string}
    }).done(function(msg){
        //effacer le contenu des resultats
        $('div[data-elem="search-results"]').html('');

        //add results
        if(msg)
        {
            $('div[data-elem="search-results"]').append(msg);
            imgSmoothLoading();
        }
    });
}

function loadEmptyDiscussion() {
    var idEmptyConv = $('.discussions-container').find('div[data-elem="ap-discussion"]').eq(1).attr('data-conv');
    getConv(idEmptyConv, 'discussion', false, '');
}

function loadDiscussion(idconv, convHtml) {
    //check notif
    consultConvNotif(idconv);
    consultApConv(idconv);

    //off loader
    offLoaderDiscussion();

    //display body
    $('div[data-elem="discussion-container"]').html(convHtml);
    imgSmoothLoading();

    //display mesages
    updateDiscussion(idconv);
}

function addApercuDiscussion(idconv, apHtml) {
    if($('.discussion[data-conv="'+ idconv +'"]').length == 0)
    {
        $(apHtml).insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
    }
    else{
        $('.discussions-container').find('.discussion[data-conv="'+ idconv +'"]').remove();
        $(apHtml).insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
    }
    slideFromLeftItems($(apHtml));
}

function updateDiscussion(idconv){
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/displayMess.php",
        data: {idconv: idconv},
        dataType: "json"
    }).done(function(msg){
        //effacer le contenu des resultats
        $('div[data-elem="search-results"]').html('');

        //add results
        if(msg.messages)
        {
            $conv = $('div[data-elem="discussion-container"]');
            $msgContainer = $('div[data-elem="messages-container"]');
            //update convs
            $.each(msg.messages, function (index, messageHtml)
            {
                //si le message est le premier et qu'il n'est pas possible de definir l'auteur du précédent mess
                if(index == 0)
                {
                    addMessageInFront($msgContainer, messageHtml, false);
                }
                //si le message n'est pas le premier et qu'il est possible de definir l'auteur du precédent
                else
                {
                    addMessageInFront($msgContainer, messageHtml, $(msg.messages[index - 1]));
                }
            });
            scrollConvToBottom($conv, 0);
            imgSmoothLoading();
        }
    });
}

function deleteDiscussion(idconv) {
    removeApDiscussion(idconv);
    dispayLastDiscussion();
}

function removeApDiscussion(idconv) {
    $('.discussion[data-conv="'+ idconv +'"]').remove();
}

function activeLoaderDiscussion() {
    $('div[data-elem="loader-discussion-part"]').addClass('loader-container-active');
}

function offLoaderDiscussion() {
    $('div[data-elem="loader-discussion-part"]').removeClass('loader-container-active');
}

//align pic verticaly
$(document).on('load','.twopic img', function (e) {
    centerImgHorizontaly($(e));
});

//display search results
$(document).on("keyup","input[name='search-conversation']", function(){
    //extract stringSearch
    var tosearch = $(this).val();

    //masquer la list des convs
    $('.discussions-container').addClass('hided');

    //afficher la list ds results
    $('div[data-elem="search-results"]').addClass('active');

    //search user
    if(tosearch == '')
    {
        //afficher de nouveau la liste des results
        $('.discussions-container').removeClass('hided');

        //masquer la list des convs
        $('div[data-elem="search-results"]').html('').removeClass('active');
        return true;
    }
    else{
        if(tosearch.length <= 25)
        {
            //lunch research
            searchConv(tosearch);
            return true;
        }
    }
});

//display conv after click on research results
$('#MessageCenter').on('click touch touchstart tap', 'div[data-elem="ap-discussion"]', function (){
    // do something
    var convidToOpen = $(this).attr('data-conv');

    //reset input
    $("input[name='search-conversation']").val('');

    //enlever les search results
    $('div[data-elem="search-results"]').html('').removeClass('active');

    //retablir la list des convs
    $('.discussions-container').removeClass('hided');

    //si la conv courant n'est pas celle séléctionnée
    //#todo comprendre prk ca marche pas ca omg
    var idConvLoaded = $('div[data-elem="discussion-content"]').attr('data-conv');
    if(idConvLoaded != convidToOpen)
    {
        //active loader
        $('div[data-elem="discussion-container"]').html('');
        activeLoaderDiscussion();

        //get conv
        getConv(convidToOpen, 'discussion', false, '');
    }

    //consult notif
    var args = [];
    args['convid'] = convidToOpen;
    actionNotif(args, 'consultNotif');

    //alert('ok');

    if(isMobile)
    {
        //alert('ok');
        $('#message-center-top').addClass('slided');
    }
    return false;
});

//display old messages when scrool top
$(document).on('mouseenter', 'div[data-elem="messages-container"]', function(){
    $(this).scroll(function(e)
    {
        var scrollTop = $(this).scrollTop();
        //var topDistance = $(this).offset().top;
        if(scrollTop < 50)
        {
            if(!$(this).hasClass('loading'))
            {
                var convId = $(this).closest($('div[data-elem="discussion-content"]')).attr('data-conv');
                var nbMessagesLoaded = $(this).find('div[data-elem="message"]').length;
                $(this).addClass('loading');
                loadPrevMessages(convId, nbMessagesLoaded, 'discussion');
                return false;
            }
        }
    });
    $(this).mouseleave(function() {
        $(this).unbind();
    });
});

//display edit options on mobile
$('.body').on('click', 'div[data-elem="bt-container"]', function(){
    if($(this).hasClass('active'))
    {
        $(this).removeClass('active');
        return;
    }
    else{
        $(this).addClass('active');
        return;
    }

});

