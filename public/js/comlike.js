
function showNexts(index, beginAt) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/shownext.php",
        data: {index: index, beginAt: beginAt},
        dataType: 'json'
    }).done(function(msg){
        //lor du rendu du new comment, incrementation graphique du compteur (X/X) dans le showmore
        domElem = $('.timeline-elem').get(index);
        $elem = $(domElem).find('.bt-show-next');
        for (var i = 0; i < msg.length; i++) {
            $(msg[i]).insertBefore($elem);
        }
        var nbTotalComment = $elem.find('p').text().split('/')[1].split(')')[0];
        var nbComDisplayed = $(domElem).find('.post-comments').length;
        // dans (X/Y) incrementation de X
        var toReplaceX = '('+ beginAt;
        var byThisX = '('+ nbComDisplayed;
        var newTextX = $elem.find('p').text().replace(toReplaceX, byThisX);
        $elem.find('p').text(newTextX);
        if($(domElem).find('.post-comments').length == nbTotalComment)
        {
            $elem.addClass('active');
        }
        AJAXloader(false);
    });
}

function shareComment(text, datas, index, newpost) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/comment.php",
        data: {text: text, datas: datas}
    }).done(function(msg){
        if(msg != 'err')
        {
            if(newpost)
            {
                popCommentOnPostJustShare(index, msg);
            }
            else{
                popComment(index, msg);
            }
            emptyPostBloc();
            //increase comment counter
            domElem = $('.timeline-elem').get(index);
            $elemToIncrease = $(domElem).find('.comment-bloc .counter');
            var counter = parseInt($elemToIncrease.text()) + 1;
            $elemToIncrease.text(counter);
        }
        else{
            window.location.href = 'index.php?p=home';
        }
    });
}

function like(datas, rate) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/like.php",
        data: {datas: datas, rate: rate}
    }).done(function(msg){

    });
}


//VARIABLES

//a l'entrée de texte retirer le placeholder
$("div[contenteditable='true']").keyup(function(){
    $(this).addClass('editable-content-active');
}).keydown(function(){
    if($(this).text() == " "){
        //alert('ok');
        $(this).removeClass('editable-content-active');
    }
});

//like / unlike function
$('.timeline-container').on('click', '.like', function(e) {
    var index = $(this).parents('.timeline-elem').index();
    var datas = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');
    var counter;

    if(!$(this).hasClass('focus-on-active'))
    {
        domElem = $('.timeline-elem').get(index);
        $elemToIncrease = $(domElem).find('.like-bloc .counter');
        counter = parseInt($elemToIncrease.text()) + 1;
        $elemToIncrease.text(counter);
        $(this).addClass('focus-on-active');
        like(datas, '+');
        return;
    }
    else{
        domElem = $('.timeline-elem').get(index);
        $elemToIncrease = $(domElem).find('.like-bloc .counter');
        counter = parseInt($elemToIncrease.text()) - 1;
        $elemToIncrease.text(counter);
        $(this).removeClass('focus-on-active');
        like(datas, '-');
        return;
    }
});

//press enter to share comment
$('.timeline-container').on('keypress', '.input-active' ,function(e) {
    //push ENTER
    if(e.which == 13)
    {
        if($(this).is('[placeholder]'))
        {
            //#todo add this test for share input too
            //remplacer le contenu vide du content editable par des ''
            $valinput = $(this).html().replace(/&nbsp;|<br>/g,'');

            //get l'index du timeline elem pour le faire correspondre avec son postid correspondant en session
            var index = $(this).parents('.timeline-elem').index();
            var datas = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');

            //define if the post that your commenting is just shared (= you send comment from a post that u share with the share function without reloading page) or not, default: false
            var newpost = false;
            if($valinput != 0 )
            {
                //test nescessaire a la gestion du cas ou l'ont commente le post que l'on viens de share
                if($(this).parents('.post-options').hasClass('empty-comments'))
                {
                    newpost = true;
                    shareComment($valinput, datas, index, newpost);
                    $(this).blur();
                }
                else{
                    shareComment($valinput, datas, index, newpost);
                    $(this).blur();
                }
            }
        }
        e.stopPropagation();
    }
    return e.which != 13
});
/*----------------------------------------------------------------------*\
 * MOBILE SHARE COMENT ONCLICK "bt share"
\*----------------------------------------------------------------------*/
$('.timeline-container').on('touchstart', '.bt-comment' ,function(e) {
    //#todo add this test for share input too
    //remplacer le contenu vide du content editable par des ''
    $valinput = $('.input-active').html().replace(/&nbsp;|<br>/g,'');

    //get l'index du timeline elem pour le faire correspondre avec son postid correspondant en session
    var index = $(this).parents('.timeline-elem').index();
    var datas = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');

    //define if the post that your commenting is just shared (= you send comment from a post that u share with the share function without reloading page) or not, default: false
    var newpost = false;
    if($valinput != 0 )
    {
        //test nescessaire a la gestion du cas ou l'ont commente le post que l'on viens de share
        if($(this).parents('.post-options').hasClass('empty-comments'))
        {
            newpost = true;
            shareComment($valinput, datas, index, newpost);
            $(this).blur();
        }
        else{
            shareComment($valinput, datas, index, newpost);
            $(this).blur();
        }
    }
});


//show more +5
$('.timeline-container').on('click', '.bt-show-more', function(){
    $(this).addClass('active');
});

//show next 10
$('.timeline-container').on('click', '.bt-show-next', function () {
    AJAXloader(true, '#loader-show-nexts');
    var index = $(this).parents('.timeline-elem').index();

    //on compte le nombre de coms affichés pour savoir a partir duquel demarer
    var beginAt = $(this).parents('.timeline-elem').find('.post-comments').length;
    showNexts(index, beginAt);
});


//give a specific class to input that are selected by user
$('.timeline-container').on('click', '.comment-input' ,function(e) {
    $('.comment-input').removeClass('input-active');
    $(this).addClass('input-active');
});

//remove contextuels menus
//#todo utiliser blur plutot
$(document).on('click', '.body', function () {
    $(this).find('.post-edit-options').removeClass('active');
    $(this).find('.bt-modifs').remove();
});
