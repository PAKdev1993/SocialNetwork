$(function () {
    //fixScreenWidth();
});

$(window).click(function() {
    $('.search-bar-header-results').remove();
});


$(document).on('click', '.searchbar-result', function(e){
    e.stopPropagation();
});

function AJAXloader(bool, loaderContainerDom){
    if(bool == true)
    {
        $(loaderContainerDom).addClass('loader-container-active');
    }
    if(bool == false)
    {
        if(!loaderContainerDom)
        {
            $('.loader-container').removeClass('loader-container-active');
        }
        else{
            $(loaderContainerDom).removeClass('loader-container-active');
        }
    }
}

function AJAXloaderElem(bool, elem){
    if(bool == true)
    {
        elem.addClass('loader-container-active');
    }
    if(bool == false)
    {
        if(!elem)
        {
            $('.loader-container').removeClass('loader-container-active');
        }
        else{
            $(elem).removeClass('loader-container-active');
        }
    }
}

function displayMessagesOut(){
    $('.error').css('height','0px');
    $('.special').css('height','0px');
    $('.success').css('height','0px');
}

function displaySuccess(successArray, locationSuccess /* string */, pxHeightsupp /* int, optionnal */){
    $success = $('.success');
    var height = parseInt($(locationSuccess).find('input').css('height'), 10);
    $bloc_sucess = $(locationSuccess).find($success);
    $bloc_sucess.css('height',height);
    $successhtml = '';
    $.each(successArray, function(i, elem){
        $successhtml = $successhtml + '<li>' + elem + '</li>';
    });
    var nbSuccess = successArray.length;
    $bloc_sucess.find('ul').html($successhtml);
    $bloc_sucess.css('height',nbSuccess*height + "px");

    $(locationSuccess + ' input').css('border','1px solid  rgba(204,204,204,1)');

    //particular cases
    if(locationSuccess == '#signup-bloc'){
        $taillebloc = $bloc_sucess.find('li').length * (parseInt(height,10) + pxHeightsupp) + 'px';
        $bloc_sucess.css('height',$taillebloc);
    }
    if(locationSuccess == '#pass-forgot-form')
    {
        $('#pass-forgot-form .error').css('height','0px');
    }
}

function displayError(errorArray, locationError /* string */, inputArrayToChangeBorder /*array, optionnal*/, pxHeightsupp /*int, optionnal */){
    $.each(inputArrayToChangeBorder, function(i, elem)
    {
        $(elem).css('border','1px solid red');
    });
    $error = $('.error');
    var height = parseInt($(locationError).find('input').css('height'),10);
    $bloc_error = $(locationError).find($error);
    var errorhtml = '';
    $.each(errorArray, function(i, elem)
    {
        errorhtml = errorhtml + '<li>' + elem + '</li>';
    });
    var nbErrors = errorArray.length;
    $bloc_error.find('ul').html(errorhtml);
    $bloc_error.css('height',nbErrors*height + "px");

    //particular cases
    if(locationError == '#signup-bloc')
    {
        $taillebloc = $bloc_error.find('li').length * (parseInt(height,10) + pxHeightsupp) + 'px';
        $bloc_error.css('height',$taillebloc);
    }
    //ici vu que la div error se positionne au meme endroit que la div success, on reduit la taille du success
    if(locationError == '#pass-forgot-form')
    {
        $('#pass-forgot-form .success').css('height','0px');
    }
}

function displaySpecial(specialArray, locationSpecial /* string */, inputArrayToChangeBorder /*array, optionnal*/, pxHeightsupp /*int, optionnal */){
    $.each(inputArrayToChangeBorder, function(i, elem)
    {
        $(elem).css('border','1px solid rgba(204,204,204,1)');
    });
    $special = $('.special');
    $special_parent = $('.error-bloc');
    var height = $(locationSpecial).find('input:first-child').css('height');
    $bloc_special = $(locationSpecial).find($special);
    $bloc_special.css('height',height);
    $specialhtml = '';
    $.each(specialArray, function(i, elem)
    {
        $specialhtml = $specialhtml + '<li>' + elem + '</li>';
    });
    $bloc_special.find('ul').html($specialhtml);
    $bloc_special.css('height',height);
}

function viderChamps(form /* string */){
    inputArray = $(form).find('input[type="text"]');
    $.each(inputArray, function(i, elem){
        $(elem).val('');
    });
}

function displayErrorBulle(locationError){
    $(locationError).addClass('error-input');
}

function displayErrorBulleSpecial(locationError, classBulleSpecial){
    $('.bulle-error').removeClass('.bulle-error-special');
    $(locationError).addClass('error-input-special');
    $(classBulleSpecial).addClass('bulle-error-special');
}

function displayErrorBulleSpecialElem(elem, classBulleSpecial){
    $('.bulle-error').removeClass('.bulle-error-special');
    elem.addClass('error-input-special');
    $(classBulleSpecial).addClass('bulle-error-special');
}

function removeErrorBulleSpecial() {
    $('.bulle-error').removeClass('.bulle-error-special');
}

//photo-bloc container dimensions 1 photo case
function resiezOnePhotoBloc(elem){
    var height = elem.find('.post-pic img').height() + 10;
    //elem.css('min-height',height); <-- BEFORE
    if(height > 200){
        height = 200 + 10;
    }
    elem.css('height',height); //<-- TEST

    //retablissement du padding gauche
    elem.find('.post-pic-container').css('padding-right','5px');
}

$(function($){
    $("div[contenteditable='true']").focusout(function () {
        var element = $(this);
        if (!element.text().replace(" ", "").length) {
            element.empty();
        }
    });
});

function emptyShareBloc(){
    $('#share-input').html('');                         //vider le champ texte
    $('.img-share-bloc').removeClass('active');         //diminuer la taille du champ d'ajout
    $('.img-share-bloc .img-share-container').remove(); //retirer tout les appercu d'image
    $('.share-option-button').removeClass('active');    //remmettre le bouton d'ajout de photo a l'etat d'origine
    $('#share-videos').fadeIn();                        //remmttre le boutton d'ajout de videos
    $('#preview-content').html('');
    imgsToPost = [];                                    //important: vider le tableau d'imgToPost
    //#todo ameliorer ce fonctionnement
    toogleShare = 0;
}

function emptyPostBloc(){
    $('.editable-content').html('');
}

//pour faire apparaitre le commenbt avec un effet de surbrillance
function popComment(index, msg) {
    //affichage du nouveau commentaire
    domElem = $('.timeline-elem').get(index);
    $elem = $(domElem).find('.your-comment');
    $(domElem).find('.comments-wrap').removeClass('just-commented');
    $(msg).insertAfter($elem);
    $(domElem).find('.comments-wrap').addClass('just-commented');

    //increase le nombre de comment affiché, pour que la fonction "show more" increase correctement le compteur de com affiché si la page n'est pas rechercgée
    //Dans (Show more ... (X/Y) incrementer X
    $elem = $(domElem).find('.bt-show-next');
    var nbComDisplayed = $(domElem).find('.post-comments').length;
    var oldValue = nbComDisplayed -1;
    var toReplace = '('+ oldValue;
    var byThis = '('+ nbComDisplayed;
    var newText = $elem.find('p').text().replace(toReplace, byThis);
    $elem.find('p').text(newText);
    // dans (X/Y) incrementation du Y
    var oldValueY = $elem.attr('data-nb');
    var toReplaceY = oldValueY + ')';
    var byThisY = (parseInt(oldValueY) + 1) + ')';
    var newTextY = $elem.find('p').text().replace(toReplaceY, byThisY);
    $elem.find('p').text(newTextY);
    $elem.attr('data-nb', parseInt(oldValueY) + 1);
}

function popCommentOnPostJustShare(index, msg) {
    domElem = $('.timeline-elem').get(index);
    $(domElem).find('.post-options').removeClass('empty-comments');
    $elem = $(domElem).find('.your-comment');
    $(msg).insertAfter($elem);
}

if($('.aside').find('.nav-profile-container').length > 0) //#todo BOURRIN, corriger ca
{
    var navMenoOffsetBottom = $('.aside').find('.nav-profile-container').offset().top + $('.aside .nav-profile-container').height();
}

//define des height bloc to hide
var heightBlocToHide = $('.invite-your-friends').height();

$(window).scroll(function() {
    //resize header
    $('.header').addClass('active-scroll');
    if($(window).scrollTop() <= 0)
    {
        $('.header').removeClass('active-scroll');
    }

    //display nav items profile mobile
    if($(window).scrollTop() >= navMenoOffsetBottom){
        $('#ProfileGamer').find('#nav-profile-mobile').addClass('nav-profile-active');
        $('#ProfileEmployee').find('#nav-profile-employee-mobile').addClass('nav-profile-active');
    }
    if($(window).scrollTop() < navMenoOffsetBottom){
        $('#ProfileGamer').find('#nav-profile-mobile').removeClass('nav-profile-active');
        $('#ProfileEmployee').find('#nav-profile-employee-mobile').removeClass('nav-profile-active');
    }

    //fix right side on top on home page
    if($('body').attr('id') == 'Home')
    {
        //si on scroll a une hauteur supperieur a la taille des bloc a masquer (pour le moment c'est le bloc invite uniquement)
        if($(window).scrollTop() >= heightBlocToHide)
        {
            //on delete les bloc de l'affichage
            $('.invite-your-friends').addClass('hided');

            //on fixe le right-side
            $('.aside-right').addClass('fixed');
        }
       else{
            //on reafiche le bloc
            $('.invite-your-friends').removeClass('hided');

            //on defixe le right-side
            $('.aside-right').removeClass('fixed');
        }
    }
});

//retourn false si problemee avec la date
function checkDate(day, month, year)
{
    //alert(year); yyyy
    var month30 = ['02', '04', '06', '09', '11'];
    return !((day == 'dd' || month == 'mm' || year == 'yyyy') || ($.inArray(month, month30) != '-1' && day == '31') || (day == '30' && month == "02" || day == '31' && month == "02"));
}

function ckeckAnteriority(day1, month1, year1, day2, month2, year2)
{
    var startday    = parseInt(day1);
    var startmonth  = parseInt(month1);
    var startyear   = parseInt(year1);
    var endday      = parseInt(day2);
    var endmonth    = parseInt(month2);
    var endyear     = parseInt(year2);

    if(startyear > endyear)
    {
        return false;
    }
    if(startyear == endyear)
    {
        if(startmonth > endmonth)
        {
            return false
        }
        if(startmonth == endmonth)
        {
            if(startday > endday)
            {
                return false
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }
    else{
        return true;
    }
}

window.onpopstate = function(event) {
    //alert('ok');
    //console.log("location: " + document.location + ", state: " + JSON.stringify(event.state));
};

function smoothGaleryApear(){
    $('.active-post-viewer img').one("load", function() {
        $(this).css('opacity',1);
    }).each(function() {
        if(this.complete) $(this).load();
    });
}
function imgSmoothLoading(){ //#todo CONTOURNEMENT DE PROBLEME, avant on mettait .one sir $('img'): pb: les images reloadé dinamiquement ne se rechargeais pas

    //OPACITY TO 1 WHE IMG IS LOADED
    $("img").on("load", function() {
        $(this).css('opacity',1);
    }).each(function() {
        if(this.complete) $(this).load();
    });
};

$(function centerCoverVerticaly(){
    //CENTER COVER IN CONTAINER
    $("#cover-pic-container img").on("load", function() {
        var coverContainerHeight = $('#cover-pic-container').height();
        var imgheight = $(this).height();
        var topToAdd = (coverContainerHeight - imgheight) / 2;
        $(this).css('top',topToAdd);
    });
});

$(function () {
    imgSmoothLoading(); //#todo corriger ca
});

function getURLParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
};

//choix de la langue
$('.lang-selector').click(function(){
    var array = $(this).attr('id').split('-');
    var lang = array[1];
    $.post('inc/admin/langChange.php', {lang: lang}).complete(
        function(){
            window.location.reload();
        })
});

function centerImgVerticaly(imgElem){
    var hparent = $(imgElem).parent().height();
    var himg = $(imgElem).height();
    var mt = (himg - hparent)/2;
    $(imgElem).css('margin-top', -mt);
}

function centerImgHorizontaly(imgElem){
    var lparent = $(imgElem).parent().width();
    var limg = $(imgElem).width();
    var ml = (limg - lparent)/2;
    $(imgElem).css('margin-left', -ml);
}

function placeCaretAtEnd(el) {
    el.focus();
    if (typeof window.getSelection != "undefined"
        && typeof document.createRange != "undefined") {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }
}

function delayDisplayMess(jqueryElem, jqueryMessContainer, nbIteration) {
    jqueryElem.delay(100 * nbIteration).queue(function() {
        $(this).appendTo(jqueryMessContainer).addClass('nopic').show('slow').find($('.user-message')).css('transform', 'scale(1)');
    })
}

function slideFromLeftItems(menuItems) {
    menuItems.delay(500).each(function(i) {
        $(this).delay(75 * i).queue(function() {
            $(this).removeClass('slidedLeft');
        })
    });
}

//#todo remplacer cette fonction par du du code css ecrit directement sur l'image lor de sa sauvegarde dans le preview
$(function(){
    $('.image-preview').each(function(){
        centerImgVerticaly($(this).find('img'));
    })
});

function getdateFromSqlDate(dateSql, formatDateToReturn)
{
    if(formatDateToReturn == "H:m")
    {
        var date = new Date(dateSql);
        var hours = date.getHours();
        var minutes = date.getMinutes();
        if(hours.length == 1)
        {
            hours = '+' + hours;
        }
        if(minutes.length == 1)
        {
            minutes = '+' + minutes;
        }

        return hours + ':' + minutes;
    }

}

//Fix right side to top when scroll
function isMobile()
{
// device detection
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)))
    {
        return true;
    }
    else{
        return false;
    }
}

function fixScreenWidth(){
    var width = $('.body').width();
    $('.body').css('max-width', width).css('min-width', width);
    alert(width);
}
