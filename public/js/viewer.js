function displayPostImage(postid, slfirstimg) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/preview.php",
        data: {data: postid, dataimg:slfirstimg}
    }).done(function(msg){
        if(msg != 'err')
        {
            $mainParent = $('.wrap');
            $mainParent.find('.viewer-content').html(msg);
            $mainParent.addClass('active');
            $mainParent.find('.viewer').addClass('active-post-viewer');
        }
        else{
            window.location.href = 'index.php?p=home';
        }
    });
}

function displayAlbumImage(datedir, weid, indexelem, imgsl) {
    $.ajax({
        method: 'POST',
        url: "inc/Album/preview.php",
        data: {datadate: datedir, dataid:weid, dataindex:indexelem, dataimgid: imgsl}
    }).done(function(msg){
        if(msg != 'err')
        {
            $mainParent = $('.wrap');
            $mainParent.find('.viewer-content').html(msg);
            $mainParent.addClass('active');
            $mainParent.find('.viewer').addClass('active-post-viewer');
        }
        else{
            window.location.reload();
        }
    });
}
/*----------------------------------------------------------------------*\
 * DISPLAY VIEWER FOR TIMELINE
\*----------------------------------------------------------------------*/
//#todo fonction a grandement ameliorer
var gegin = 0;
$('.timeline-container').on('click', '.bt-preview', function(e) {
    var postid = $(this).attr('data-p');
    var slfirstimg = $(this).attr('data-elem');
    var topOffset = $(document).scrollTop();
    //$('.main').css('top', topOffset + 'px');
    displayPostImage(postid, slfirstimg);
});

$('.timeline-container').on('click', '.trash-container', function(e) {
    e.stopPropagation();
});
/*----------------------------------------------------------------------*\
 * REMOVE VIEWER
\*----------------------------------------------------------------------*/
$(document).on('click', '.active-post-viewer', function(){
    $(this).parents('.wrap').removeClass('active');
    $('.wrap .viewer').removeClass('active-post-viewer');
    $('.wrap .viewer-content').html('');
});

$(document).on('click', '.viewer-content', function(e){
   e.stopPropagation();
});
//mobile
$(document).on('touchstart', '.bt-close', function(e){
    $(this).parents('.wrap').removeClass('active');
    setTimeout(function(){$('.wrap .viewer').removeClass('active-post-viewer');}, 200); //#todo BOURRIN trouver une solution plus elegante
    //empeche les element derrière l'appercu d'etre cliqué lor de la fermeture de ce dernier
    $('.wrap .viewer-content').html('');
    e.stopPropagation();
});
/*----------------------------------------------------------------------*\
 * SLIDE MOVE LEFT/RIGHT
\*----------------------------------------------------------------------*/
$(document).on('click', '.bt-left', function(e){
    //preparation au slide
    $mainParent =       $('#viewer-pic');
    $slideActive =      $('.slide.active');
    $slidesContainer =  $('.slides-container');

    var indexSlide = $slideActive.attr('data-index');
    var previousIndex = parseInt(indexSlide) - 1;

    //test de l'existence du slide suivant
    if(previousIndex >= 0)
    {
        $mainParent.find('.bt-right').removeClass('hided');
        $slideActive.removeClass('active');
        $('[data-index="'+ previousIndex + '"]').addClass('active');
        var margeleft = - previousIndex * 100 + '%';
        $slidesContainer.css('margin-left', margeleft);
    }
    else{
        $mainParent.find('.bt-left').addClass('hided');
    }
});

$(document).on('click', '.bt-right', function(e){
    //preparation au slide
    $mainParent =       $('#viewer-pic');
    $slideActive =      $('.slide.active');
    $slidesContainer =  $('.slides-container');

    var indexSlide = $slideActive.attr('data-index');
    var nextIndex = parseInt(indexSlide) + 1;
    var maxValue = $mainParent.find('.slide').length;

    //test de l'existence du slide suivant
    //#todo effacer la fleche de gauche lorsque l'on est sur la denière diapo
    if(nextIndex < maxValue)
    {
        $mainParent.find('.bt-left').removeClass('hided');
        $slideActive.removeClass('active');
        $('[data-index="'+ nextIndex + '"]').addClass('active');
        var margeleft = - nextIndex * 100 + '%';
        $slidesContainer.css('margin-left', margeleft);
    }
    else{
        $mainParent.find('.bt-right').addClass('hided');
    }
});
/*----------------------------------------------------------------------*\
 * DISPLAY VIEWER FOR TIMELINE
 \*----------------------------------------------------------------------*/
$('#galery').on('click', '.bt-preview', function(e) {
    var datedir     = $(this).attr('data-d');
    var weid        = $(this).attr('data-weid');
    var indexelem   = $(this).attr('data-elem');
    var imgsl       = $(this).attr('data-elem');
    //$('.main').css('top', topOffset + 'px');
    displayAlbumImage(datedir, weid, indexelem, imgsl);
});