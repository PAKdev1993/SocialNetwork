//UPDATE BASIC QUICK INFOS
function updateQuickInfos(dateOfBith, nationnality, language) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateQuickInfos.php",
        data: {dateofbirth: dateOfBith, nationnality: nationnality, language:language}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('#quick-infos-bloc').html('').append(msg);
            AJAXloader(false, '#loader-quickinfos-edit');
        }
        else{
            window.location.reload();
        }
    });
}

//UPDATE ADVANCED QUICK INFOS FOR GAMER PARt
function updateAdvancedQuickInfos(newCurrentTeam, role, game, platform, state) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateAdvancedQuickInfos.php",
        data: {newCurrentTeam: newCurrentTeam, role: role, game:game, platform: platform, state:state}
    }).done(function(msg){
        if(msg != 'err')
        {
            if(msg != '')
            {
                $('#quick-infos-bloc').html('').append(msg);
            }
            AJAXloader(false, '#loader-quickinfos-body');
        }
        else{
            //window.location.reload();
        }
    });
}

//UPDATE ADVANCED QUICK INFOS FOR EMPLOYEE PART
function updateAdvancedQuickInfosEmployee(company, jobtitle, city, country, state) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateAdvancedQuickInfosEmployee.php",
        data: {company: company, jobtitle: jobtitle, city:city, country:country, state:state}
    }).done(function(msg){
        if(msg != 'err')
        {
            if(msg != '')
            {
                $('#quick-infos-bloc').html('').append(msg);
            }
            AJAXloader(false, '#loader-quickinfos-body');
        }
        else{
            window.location.reload();
        }
    });
}

//DISPLAY ADD ELEM BLOC
function displayAddElem(blocname) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/displayEdit.php",
        data: {blocname: blocname}
    }).done(function(msg){
        if(msg != 'err')
        {
            if(blocname == 'qi') //QUICK INFOS
            {
                $bloc = $('#quick-infos-bloc');
                $div = $bloc.find('.bloc-container');
                $(msg).insertAfter($div);
                $bloc.find('.aside-left-bloc').addClass('edit-mode');
                AJAXloader(false, '#loader-quickinfos-body');
            }
            if(blocname == 'mts') //MY TEAMS
            {
                $bloc = $('#slide-mycareer');
                $div = $bloc.find('#profile-career');
                $(msg).insertAfter($div);
                $bloc.find('.aside-left-bloc').addClass('edit-mode');
                AJAXloader(false, '#loader-teams');
            }
            if(blocname == 'mevts') //MY EVENTS
            {
                $bloc = $('#slide-myevents');
                $div = $bloc.find('#profile-events');
                $(msg).insertAfter($div);
                $bloc.find('.aside-left-bloc').addClass('edit-mode');
                AJAXloader(false, '#loader-events');
            }
            if(blocname == 'mga') //MY GAMES
            {
                $bloc = $('#slide-mygames');
                $div = $bloc.find('#profile-games');
                $(msg).insertAfter($div);
                $bloc.find('.aside-left-bloc').addClass('edit-mode');
                AJAXloader(false, '#loader-games');
            }
            if(blocname == 'meq') //MY EQUIPMENT
            {
                $bloc = $('#slide-myequipment');
                $div = $bloc.find('#profile-equipements');
                $(msg).insertAfter($div);
                $bloc.find('.aside-left-bloc').addClass('edit-mode');
                AJAXloader(false, '#loader-equipments');
            }
            if(blocname == 'sum') //MY SUMMARY
            {
                $bloc = $('div[data-elem="gamer-summary-bloc"]');
                $div = $bloc.find('.bloc-container');
                $(msg).insertAfter($div);
                $bloc.find('.aside-right-bloc').addClass('edit-mode');
                AJAXloader(false, 'div[data-elem="loader-summary-body"]');
            }
            if(blocname == 'int') //MY INTERESTS
            {
                $bloc = $('div[data-elem="interest-bloc"]');
                $div = $bloc.find('.bloc-container');
                $(msg).insertAfter($div);
                $bloc.find('.aside-right-bloc').addClass('edit-mode');
                AJAXloader(false, 'div[data-elem="loader-interests-body"]');
            }
            if(blocname == 'mc') //MY COMPANIES
            {
                $bloc = $('#slide-mycareer');
                $div = $bloc.find('#profile-career');
                $(msg).insertAfter($div);
                $bloc.find('.aside-left-bloc').addClass('edit-mode');
                AJAXloader(false, '#loader-company-body');
            }
            if(blocname == 'empe') //MY EMPLOYEE EVENTS
            {
                $bloc = $('#slide-myevents');
                $div = $bloc.find('#profile-employee-events');
                $(msg).insertAfter($div);
                $bloc.find('.aside-left-bloc').addClass('edit-mode');
                AJAXloader(false, '#loader-employee-events-body');
            }
            if(blocname == 'sumemp') //MY EMPLOYEE SUMARY
            {
                $bloc = $('div[data-elem="employee-summary-bloc"]');
                $div = $bloc.find('.bloc-container');
                $(msg).insertAfter($div);
                $bloc.find('.aside-right-bloc').addClass('edit-mode');
                AJAXloader(false, 'div[data-elem="loader-summary-body"]');
            }
            if(blocname == 'live') //LIVE PROFILE GAMER
            {
                $bloc = $('div[data-elem="twitch-bloc"]');
                $div = $bloc.find('.bloc-container');
                $(msg).insertAfter($div);
                $bloc.find('.aside-right-bloc').addClass('edit-mode');
                AJAXloader(false, 'div[data-elem="loader-live-body"]');
            }
        }
        else{
            window.location.reload();
        }
    });
}

//UPDATE INTERESTS
function updateInterests(interestString) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateInterest.php",
        data: {interestString: interestString}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('div[data-elem="interest-bloc"]').html('').append(msg);
            AJAXloader(false, 'div[data-elem="loader-interests-body"]');
        }
        else{
            window.location.reload();
        }
    });
}

//PREPARE UPLOAD LOGO FOR PROFILE ELEMS
//function prepareUploadPic(formDataLogo, typeLogo) {
function prepareUploadPic(formDataLogo, elem) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/prepareUploadPic.php",
        data: formDataLogo,
        processData: false,
        contentType: false
    }).done(function(msg){
        //close loader input file
        AJAXloader(false);

        //methode utilisé car impossible de recuperer les valeurs en cas de retour JSON
        var pieces = msg.split('-');
        //#todo corriger ce system de message d'erreur trop bourrin
        if(pieces.length < 3)
        {

        }
        if(pieces.length >= 3)
        {
            //suppression du fichier si erreur
            elem.val('');

            //display du message d'erreur
            var classBulleSpecial;
            var errorMsg = pieces[3];
            if(errorMsg == "error upload")
            {
                classBulleSpecial = ".upload-error";
            }
            if(errorMsg == "wrong types")
            {
                classBulleSpecial = ".error-type";
            }
            if(errorMsg == "wrong extension")
            {
                classBulleSpecial = ".ext-error"
            }
            if(errorMsg == "too large")
            {
                classBulleSpecial = ".size-error";
            }
            displayErrorBulleSpecialElem(elem.closest($("div[data-elem='logo-input-container']")), classBulleSpecial);
        }
    });
}

//UPLOAD COVER
function uploadCover(picName) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/changeCoverPic.php",
        data:{picName: picName}
    }).done(function(msg){
        $('#cover-pic-container').html(msg);
        imgSmoothLoading();
        AJAXloader(false, '#loader-cover-pic');
    });
}

function uploadProfilePic(picName) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/changeProfilePic.php",
        data:{picName: picName}
    }).done(function(msg){
        $('#profile-pic-container').html(msg);
        imgSmoothLoading();
        AJAXloader(false, '#loader-profile-pic');
    });
}
//PREPARE PROFILE PIC UPLOAD
function prepareProfilePicture(formData, type) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/prepareUploadPic.php",
        data: formData,
        processData: false,
        contentType: false
    }).done(function(msg){
        //methode utilisé car impossible de recuperer les valeurs en cas de retour JSON
        var pieces = msg.split('-');
        //#todo corriger ce system de message d'erreur trop bourrin
        if(pieces.length < 3)
        {
            if(type == 'cover')
            {
                uploadCover(pieces[1]);
            }
            if(type == 'profile')
            {
                uploadProfilePic(pieces[1]);
            }
        }
        if(pieces.length >= 3)
        {
            var locationError;
            if(type == 'cover')
            {
                AJAXloader(false, '#loader-cover-pic');
                locationError = $('#edit-cover-picture-container');
            }
            if(type == 'profile')
            {
                AJAXloader(false, '#loader-profile-pic');
                locationError = $('#edit-ava-container');
            }
            var errorMsg = pieces[3];
            if(errorMsg == "error upload")
            {
                classBulleSpecial = ".upload-error";
            }
            if(errorMsg == "wrong types")
            {
                classBulleSpecial = ".error-type";
            }
            if(errorMsg == "wrong extension")
            {
                classBulleSpecial = ".ext-error"
            }
            if(errorMsg == "too large")
            {
                classBulleSpecial = ".size-error";
            }
            displayErrorBulleSpecial(locationError, classBulleSpecial);
        }
    });
}

//DISPLAY AVAILABLE EDIT ICONS
function prepareEdit(idelem, type, index){
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/prepareEditElem.php",
        data: {idelem: idelem, type: type}
    }).done(function(msg){
        if(msg != 'err')
        {
            if(type == 'team')
            {
                domElem = $('#slide-mycareer .profile-elem').get(index);
                $elem = $(domElem);
                $elem.find('.edit-options').html(msg);
                $('.edit-options').addClass('active');
            }
            if(type == 'event')
            {
                domElem = $('#slide-myevents .profile-elem').get(index);
                $elem = $(domElem);
                $elem.find('.edit-options').html(msg);
                $('.edit-options').addClass('active');
            }
            if(type == 'game')
            {
                domElem = $('#slide-mygames .profile-elem').get(index);
                $elem = $(domElem);
                $elem.find('.edit-options').html(msg);
                $('.edit-options').addClass('active');
            }
            if(type == 'equipment')
            {
                domElem = $('#slide-myequipment .profile-elem').get(index);
                $elem = $(domElem);
                $elem.find('.edit-options').html(msg);
                $('.edit-options').addClass('active');
            }
            if(type == 'company')
            {
                domElem = $('#slide-mycareer .profile-elem').get(index);
                $elem = $(domElem);
                $elem.find('.edit-options').html(msg);
                $('.edit-options').addClass('active');
            }
            if(type == 'empevent')
            {
                domElem = $('#slide-myevents .profile-elem').get(index);
                $elem = $(domElem);
                $elem.find('.edit-options').html(msg);
                $('.edit-options').addClass('active');
            }
        }
        else{
            window.location.reload();
        }
    })
}

//DISPLAY EDIT FORM POUR ELEM
function displayEditElem(idelem, type, indexToEdit) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/displayEditElem.php",
        data: {idelem: idelem, type: type}
    }).done(function(msg){
        if(msg != 'err')
        {
            if(type == 'team')
            {
                //add le bloc edit
                domElem = $('#slide-mycareer').find('.profile-elem').get(indexToEdit);
            }
            if(type == 'event')
            {
                //add le bloc edit
                domElem = $('#slide-myevents').find('.profile-elem').get(indexToEdit);
            }
            if(type == 'game')
            {
                //add le bloc edit
                domElem = $('#slide-mygames').find('.profile-elem').get(indexToEdit);
            }
            if(type == 'equipment')
            {
                //add le bloc edit
                domElem = $('#slide-myequipment').find('.profile-elem').get(indexToEdit);
            }
            if(type == 'company')
            {
                //add le bloc edit
                domElem = $('#slide-mycareer').find('.profile-elem').get(indexToEdit);
            }
            if(type == 'empevent')
            {
                //add le bloc edit
                domElem = $('#slide-myevents').find('.profile-elem').get(indexToEdit);
                $elemToInsertAfter = $(domElem).find('.profile-aside-container');
            }

            //inserer le bloc d'edition
            $elemToInsertAfter = $(domElem).find('.profile-aside-container');
            $(msg).insertAfter($elemToInsertAfter);
            AJAXloader(false);

            //ouvrir le bloc
            setTimeout(
                function () {
                    $elemToAddEditMode = $(domElem);
                    $elemToAddEditMode.addClass('edit-mode');
                }, 200);
        }
        else{
            window.location.reload();
        }
    })
}

//DISPLAY ASK DELETE BOX
function askDelete(idelem, type) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/prepareDeleteProfileElem.php",
        data: {idelem:idelem, type:type}
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

//DELETE PROFILE ELEM
function deleteProfileElem(type, indexToDelete) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/deleteProfileElem.php",
        data: {type:type}
    }).done(function(msg){
        if(msg != 'err') {
            //remove viewer
            $('.viewer').removeClass('active');
            $('.viewer-content').html('');

            if(type == 'team')
            {
                //delete element from display
                domElem = $('#slide-mycareer .profile-elem').get(indexToDelete);
                $elem = $(domElem);
                $(domElem).addClass('deleted');

                //update quickinfos
                var pieces = msg.split('//');

                //MAJ previous team
                if(pieces[2] != '')
                {
                    $('#qi-previousteam').find('.quickinfo-text').text(pieces[2]);
                }
                else {
                    $('#qi-previousteam').remove();
                }

                //delete curent team
                if(pieces[1] == 'current')
                {
                    $('#qi-role').remove();
                    $('#qi-currentteam').remove();
                }

                //plus de team du tout
                if(pieces[1] == 'all')
                {
                    $('#qi-role').remove();
                    $('#qi-currentteam').remove();
                    $('#slide-mycareer').html(pieces[3]);
                }

                //vider le contenu html de l'element a supprimer
                setTimeout(
                    function () {
                        //$(domElem).remove(); //#todo suppression de la div cause un bug au niveau du show-next comments, trouver comment resoudre
                        $(domElem).remove();
                    }, 700);
            }
            if(type == 'event')
            {
                //delete element from display
                domElem = $('#slide-myevents .profile-elem').get(indexToDelete);
                $elem = $(domElem);
                $(domElem).addClass('deleted');

                //display new event form
                var pieces = msg.split('//');

                if(pieces[1] == 'all')
                {
                    $('#slide-myevents').html(pieces[3]);
                }

                //vider le contenu html de l'element a supprimer
                setTimeout(
                    function () {
                        //$(domElem).remove(); //#todo suppression de la div cause un bug au niveau du show-next comments, trouver comment resoudre
                        $(domElem).remove();
                    }, 700);
            }
            if(type == 'game')
            {
                //delete element from display
                domElem = $('#slide-mygames .profile-elem').get(indexToDelete);
                $elem = $(domElem);
                $(domElem).addClass('deleted');

                //display new event form
                var pieces = msg.split('//');

                if(pieces[1] == 'all')
                {
                    $('#slide-mygames').html(pieces[3]);
                }

                //vider le contenu html de l'element a supprimer
                setTimeout(
                    function () {
                        //$(domElem).remove(); //#todo suppression de la div cause un bug au niveau du show-next comments, trouver comment resoudre
                        $(domElem).remove();
                    }, 700);
            }
            if(type == 'equipment')
            {
                //delete element from display
                domElem = $('#slide-myequipment .profile-elem').get(indexToDelete);
                $elem = $(domElem);
                $(domElem).addClass('deleted');

                //display new event form
                var pieces = msg.split('//');

                if(pieces[1] == 'all')
                {
                    $('#slide-myequipment').html(pieces[3]);
                }

                //vider le contenu html de l'element a supprimer
                setTimeout(
                    function () {
                        //$(domElem).remove(); //#todo suppression de la div cause un bug au niveau du show-next comments, trouver comment resoudre
                        $(domElem).remove();
                    }, 700);
            }
            if(type == 'company')
            {
                //delete element from display
                domElem = $('#slide-mycareer .profile-elem').get(indexToDelete);
                $elem = $(domElem);
                $(domElem).addClass('deleted');

                //update quickinfos
                var pieces = msg.split('//');

                //delete curent team
                if(pieces[1] == 'current')
                {
                    $('#qi-currentcomp').remove();
                    $('#qi-location').remove();
                    $('#qi-jobtitle').remove();
                }

                //plus de company du tout
                if(pieces[1] == 'all')
                {
                    $('#qi-currentcomp').remove();
                    $('#qi-location').remove();
                    $('#qi-jobtitle').remove();
                    $('#slide-mycareer').html(pieces[3]);
                }

                //vider le contenu html de l'element a supprimer
                setTimeout(
                    function () {
                        //$(domElem).remove(); //#todo suppression de la div cause un bug au niveau du show-next comments, trouver comment resoudre
                        $(domElem).remove();
                    }, 700);
            }
            if(type == 'empevent')
            {
                //delete element from display
                domElem = $('#slide-myevents .profile-elem').get(indexToDelete);
                $elem = $(domElem);
                $(domElem).addClass('deleted');

                //display result
                $('#slide-myevents').html(msg);

                //vider le contenu html de l'element a supprimer
                setTimeout(
                    function () {
                        //$(domElem).remove(); //#todo suppression de la div cause un bug au niveau du show-next comments, trouver comment resoudre
                        $(domElem).remove();
                    }, 700);
            }
            $('.wrap').removeClass('active');
        }
        else{
            window.location.reload();
        }
    });
}

//UPDATE NOTIFY MY NETWORK
function notifyMyNetwork(state) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateNotifyMyNetwork.php",
        data: {state:state}
    }).done(function(msg){
        if(msg != 'err')
        {
            //
        }
        else{
            window.location.reload();
        }
    });
}

//SHOW PROFILE NEXTS POSTS
function showProfileNextsPosts() {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/showProfilenextsposts.php"
    }).done(function(msg){
        if(msg != 'err')
        {
            $('.timeline-container').append(msg);
        }
        else{
            window.location.reload();
        }
    });
}

/*----------------------------------------------------------------------*\
 * REMOVE EDIT OPTIONS
\*----------------------------------------------------------------------*/
$(document).on('click','.body', function(){
    $('.edit-options').removeClass('active').html('');
});
/*----------------------------------------------------------------------*\
 * VALID DELETE PROFILE ELEM
\*----------------------------------------------------------------------*/
$(document).on('click', '.valid-delete.bt-y', function(e){
    deleteProfileElem(type, indexToDelete);
});

$(document).on('click', '.valid-delete.bt-n', function(e){
    $('.viewer').removeClass('active');
    $('.wrap').removeClass('active');
});
/*----------------------------------------------------------------------*\
 * REMOVE ERROR MESSAGES
\*----------------------------------------------------------------------*/
$(document).on('click', '.body', function(){
    $('.error-input-special').removeClass('error-input-special');
});
/*----------------------------------------------------------------------*\
 * HASH EVENTS
\*----------------------------------------------------------------------*/
$(window).on('hashchange',function(e){
    var hash = document.location.hash;
    $('a[href="'+ hash + '"]').parent().trigger('click');
});

//#todo mettre en clik peut etre
$('.body').on('click', '.item-nav-profile', function(){
    $('.nav-profile-container').find('li.active').removeClass('active');
    var togo = $(this).find('a').attr('href');
    $('a[href="'+ togo + '"]').parent().addClass('active');
    //slide en fonction de l'elment cliqué
    var index = $(this).index();
    var newMarge = '-' + 100*index + '%';
    $(this).parents('#myprofile-bottom').find('.slider-profile-chapters').css('margin-left',newMarge);
});

$(document).ready(function() {
    var hash = document.location.hash;
    $('a[href="'+ hash + '"]').parent().trigger('click');
});
/*----------------------------------------------------------------------*\
 * CLOSE EDITS MODES
\*----------------------------------------------------------------------*/
//close edit mode
$('.body').on('click', '.edit-ico-container', function()
{
    $(this).parent('.bloc').removeClass('edit-mode');
    $('.bloc-edit-container').remove();

});
/*----------------------------------------------------------------------*\
 * FORMS QUICK INFOS
\*----------------------------------------------------------------------*/
//quick info update
$('.body').on('click', '#update-quickinfos', function()
{
    //#todo remplacer les valeurs comme line 197
    $form = $(this).parents('.bloc-edit-container');
    var dd =            $('#birth-day').val();
    var mm =            $('#birth-month').val();
    var yyyy =          $('#birth-year').val();
    var nationnality =  $('#nationnality').val();
    var language =      $('#language').text();

    if(!checkDate(dd, mm, yyyy))
    {
        $('#birth-year').parent().addClass('error-input');
        return false;
    }
    if(nationnality == '')
    {
        $('#nationnality').parent().addClass('error-input');
        return false;
    }
    if($.trim(language) == '')
    {
        $('#language').addClass('error-input');
        return false;
    }
    else{
        var dateOfBith = dd + '-' + mm + '-' + yyyy;
        updateQuickInfos(dateOfBith, nationnality, language);
        AJAXloader(true, '#loader-quickinfos-edit');
    }
});
/*----------------------------------------------------------------------*\
 * QUICK INFOS
\*----------------------------------------------------------------------*/
//display quickinfos edit mode
$('.body').on('click', '#edit-quickinfos .edit-gear', function()
{
    AJAXloader(true, '#loader-quickinfos-body');
    displayAddElem('qi');
});

//close edit mode
$('.body').on('click', '.close-edit', function()
{
    $(this).parents('.edit-mode').removeClass('edit-mode');
    $(this).parents('.aside-left-bloc').find('.bloc-edit-container').remove();
});
/*----------------------------------------------------------------------*\
 * FORMS INTERESTS
\*----------------------------------------------------------------------*/
//quick informations update first time (première update <=> create)
$('.body').on('click', 'button[data-action="update-interests"]', function()
{
    $interestcont = $(this).closest($('div[data-elem="interest-bloc"]')).find('.interest-container');
    var nbInterests = $interestcont.length;
    if(nbInterests > 6)
    {
        nbInterests = 6;
    }
    var interestString = '';
    for(var i = 0; i < nbInterests; i++)
    {
        if(i == nbInterests - 1)
        {
            interestString = interestString + $.trim($interestcont.eq(i).find('.input').text());
        }
        else{
            interestString = interestString + $.trim($interestcont.eq(i).find('.input').text()) + '/';
        }
    }
    if(interestString == '/////')
    {
        $interestcont.find('.input').addClass('error-input');
        return false;
    }
    else{
        updateInterests(interestString);
        AJAXloader(true, 'div[data-elem="loader-interests]"');
    }
});
//display interests edit mode
$('.body').on('click', 'div[data-action="edit-interests"] .edit-gear', function()
{
    AJAXloader(true, 'div[data-elem="loader-interests-body"]');
    displayAddElem('int');
});
/*----------------------------------------------------------------------*\
 * REMOVE ERROR MESSAGES
\*----------------------------------------------------------------------*/
$(document).on('click', '.field-container', function(){
    $(this).find('.error-input').removeClass('error-input');
    $(this).find('.error-input-special').removeClass('error-input-special');
});
/*----------------------------------------------------------------------*\
 * CHANGE COVER
\*----------------------------------------------------------------------*/
$('.body').on('change','#change-cover-picture', function(event){
    var formData = new FormData();
    AJAXloader(true, '#loader-cover-pic');
    files = $(this)[0].files[0];
    formData.append('cover-pic', files);
    prepareProfilePicture(formData, 'cover');
});
/*----------------------------------------------------------------------*\
 * CHANGE PROFILE PIC
\*----------------------------------------------------------------------*/
$('.body').on('change','#change-profile-picture', function(event){
    var formData = new FormData();
    AJAXloader(true, '#loader-profile-pic');
    files = $(this)[0].files[0];
    formData.append('profile-pic', files);
    prepareProfilePicture(formData, 'profile');
});
/*----------------------------------------------------------------------*\
 * NOTIFY MY NETWORK
\*----------------------------------------------------------------------*/
$('#notify-my-network-bloc').on('click','#toggle-notif-container', function(event){
    if(!$(this).hasClass('active-notify'))
    {
        $(this).addClass('active-notify');
        notifyMyNetwork(1);
        return;
    }
    else{
        $(this).removeClass('active-notify');
        notifyMyNetwork(0);
        return;
    }
});
/*----------------------------------------------------------------------*\
 * DISPLAY NEXT PIC TIMELINE
\*----------------------------------------------------------------------*/
//display images when click on the white shadow division
$('.timeline-container').on('click','.display-next-pic', function(e){
    //recupère la taille d'une des deux div contenat les photos
    var height = $(this).parent().find('.post-pic-container').height();

    //test si l'autre div n'est pas plus grande
    $(this).parent().find('.post-pic-container').each(function(index){
        if($(this).height() > height)
        {
            height = $(this).height();
        }
    });
    //ajout de 5 px pour simuler une marge en bas
    height = height + 10;
    $(this).parent().css('max-height',height).css('height',height);
    $(this).addClass('display-next-pic-active');
});
/*----------------------------------------------------------------------*\
 * GOTO POST NOTIFIED
\*----------------------------------------------------------------------*/
$(function(){
    if(getURLParameter('gotopost'))
    {
        var idelemgoto =    getURLParameter('gotopost');
        $elemSelected =     $('div[data-goto="'+ idelemgoto +'"]');
        var hauteurELem =   $elemSelected.offset().top - 120;
        var animSpeed = 1000;
        $('html, body').animate({scrollTop: hauteurELem + "px"}, animSpeed , 'easeOutCubic');
        $elemSelected.addClass('selected');
    }
});
/*----------------------------------------------------------------------*\
 * SHOW MORE ON PROFILE TIMELINE
\*----------------------------------------------------------------------*/
$('#slide-mytimeline').on('click', 'a[data-action="show-more-profile"]', function() {
    showProfileNextsPosts();
});
/*----------------------------------------------------------------------*\
 * SELECT PROFILE LINK
\*----------------------------------------------------------------------*/
$('.body').on('click touchstart', 'input[data-action="select-text"]',function(e){
    $(this).select();
});
/*----------------------------------------------------------------------*\
 * STICK NAV TO TOP ON MOBILE
\*----------------------------------------------------------------------*/
$(window).scroll(function() {
    //resize header
    $('.header').addClass('active-scroll');
    if($(window).scrollTop() <= 0)
    {
        $('.header').removeClass('active-scroll')
    }
});
/*----------------------------------------------------------------------*\
 * PREPARE UPLOAD PIC                                                   *
\*----------------------------------------------------------------------*/
//prepare upload image -> upload reel dans inc/updateElem
$('.body').on('change', 'input[data-elem="logo-input"]', function(event){
    var formDataLogo = new FormData();
    AJAXloaderElem(true, $(this).closest('div[data-elem="logo-input-container"]'));
    $files = $(this)[0].files[0];
    formDataLogo.append('logo', $files);
    prepareUploadPic(formDataLogo, $(this));
});