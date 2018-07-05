function updateSummary(summary) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateSummary.php",
        data: {summary: summary}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('div[data-elem="gamer-summary-bloc"]').html('').append(msg);
            AJAXloader(false, 'div[data-elem="loader-summary-body"]');
        }
        else{
            window.location.reload();
        }
    });
}
function updateCareer(formDataTeam, teamname, game, plateform, role){
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateCareer.php",
        data: formDataTeam,
        processData: false,
        contentType: false
    }).done(function(msg){
        if(msg != 'err')
        {
            //operations nescessaire pour recuperer le nouveau currentWork state de la compani edité afin de mettre a jour les qi correctement
            var pieces = msg.split('//');

            //update career
            $('#slide-mycareer').html('').append(pieces[0]);
            imgSmoothLoading();
            AJAXloader(false, '#loader-teams');

            //update advanced quickinfos BD
            AJAXloader(true, '#loader-quickinfos-body');
            updateAdvancedQuickInfos(teamname, role, game, plateform, pieces[1]); //#todo STANDARDISER VARIABLE NAME: plateform doit etre modifié en platform partout ds les script
        }
        else{
            window.location.reload();
        }
    });
}

function updateEvents(formDataEvents){
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateEvents.php",
        data: formDataEvents,
        processData: false,
        contentType: false
    }).done(function(msg){
        if(msg != 'err')
        {
            $('#slide-myevents').html('').append(msg);
            imgSmoothLoading();
            AJAXloader(false, '#loader-events');
        }
        else{
            window.location.reload();
        }
    });
}

function updateEquipments(formDataEqui){
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateEquipments.php",
        data: formDataEqui,
        processData: false,
        contentType: false
    }).done(function(msg){
        if(msg != 'err')
        {
            $('#slide-myequipment').html('').append(msg);
            imgSmoothLoading();
            AJAXloader(false, '#loader-equipments');
        }
        else{
            window.location.reload();
        }
    });
}

function updateGames(formDataGames){
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateGames.php",
        data: formDataGames,
        processData: false,
        contentType: false
    }).done(function(msg){
        if(msg != 'err')
        {
            $('#slide-mygames').html('').append(msg);
            imgSmoothLoading();
            AJAXloader(false, '#loader-games');
        }
        else{
            window.location.reload();
        }
    });
}

//ici on ne passe pas de form data on separe les params
function updateLive(embededHtml, channelLink, typeAction) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateLive.php",
        data: {embededHtml: embededHtml, channelLink: channelLink, typeAction:typeAction}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('div[data-elem="twitch-bloc"]').html('').append(msg);
        }
        else{
            window.location.reload();
        }
    });
}

/*----------------------------------------------------------------------*\
 * FORMS ESPORT CAREER
\*----------------------------------------------------------------------*/
//career update first time
$('.body').on('click', '#update-newteam', function(){
    var formData = new FormData();

    $teaminput =        $('#add-team-teamname');
    $logoinput =        $('#add-team-logoteam');
    $gameinput =        $('#add-team-game');
    $plateforminput =   $('#add-team-plateform');
    $roleinput =        $('#add-team-role');
    $startmonthinput =  $('#addteam-start-month');
    $startyearinput =   $('#addteam-start-year');
    $endmonthinput =    $('#addteam-end-month');
    $endyearinput =     $('#addteam-end-year');
    var desc =          $.trim($('#add-team-decript').text());

    if($.trim($teaminput.text()) == '')
    {
        $teaminput.addClass('error-input');
        return false;
    }
    if($.trim($gameinput.text()) == '')
    {
        $gameinput.addClass('error-input');
        return false;
    }
    if($.trim($plateforminput.text()) == '')
    {
        $plateforminput.addClass('error-input');
        return false;
    }
    if($.trim($roleinput.text()) == '')
    {
        $roleinput.addClass('error-input');
        return false;
    }
    if(!checkDate('01', $startmonthinput.val(), $startyearinput.val()))
    {
        $startyearinput.parent().addClass('error-input');
        return false;
    }
    if(!checkDate('01', $endmonthinput.val(), $endyearinput.val()) && !$endmonthinput.parents('.field-container').hasClass('hided-field'))
    {
        $endyearinput.parent().addClass('error-input');
        return false;
    }
    if(!ckeckAnteriority('01', $startmonthinput.val(), $startyearinput.val(), '01', $endmonthinput.val(), $endyearinput.val()) && !$endmonthinput.parents('.field-container').hasClass('hided-field'))
    {
        $endmonthinput.parent().addClass('error-input-special');
        return false;
    }
    else{
        //create date
        var startDate = '01' + '-' + $startmonthinput.val() + '-' +  $startyearinput.val();
        var endDate = '01' + '-' + $endmonthinput.val() + '-' + $endyearinput.val();

        //parametrer currentTeam
        var currentTeam;
        if($('#addteam-current-activity').is(':checked'))
        {
            currentTeam = 1;
        }
        else{
            currentTeam = 0;
        }
        formData.append('teamname', $teaminput.text());
        formData.append('game', $gameinput.text());
        formData.append('plateform', $plateforminput.text());
        formData.append('role', $roleinput.text());
        formData.append('startDate', startDate);
        formData.append('endDate', endDate);
        formData.append('currentTeam', currentTeam);
        formData.append('desc', desc);

        //parametter la photo
        if($logoinput.val() != '')
        {
            $file = $logoinput[0].files[0];
            formData.append('logo', $file);
        }
        else{
            formData.append('logo', 'default');
        }
        //definie type action
        formData.append('typeaction','create');
        updateCareer(formData, $.trim($teaminput.text()), $.trim($gameinput.text()), $.trim($plateforminput.text()), $.trim($roleinput.text()));
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

//#todo generaliser ca, faire comme avec delete profile elem, mettre un type
//display edit mode
$('.body').on('click', '#edit-mycareer .edit-gear', function()
{
    displayAddElem('mts');
    AJAXloader(true, '#loader-teams');
});
/*----------------------------------------------------------------------*\
 * REMOVE ENDDATE WHEN CLICK ON "CURRENT PLAY HERE"
\*----------------------------------------------------------------------*/
//display date end when current is selected for gamer's team
$(document).on('click', 'label[for="addteam-current-activity"]', function(){
    $('#addteam-current-activity').change(function() {
        if($(this).is(":checked"))
        {
            $('#addteam-end-month').parents('.field-container').addClass('hided-field');
        }
        else{
            $('#addteam-end-month').parents('.field-container').removeClass('hided-field');
        }
    });
});
/*----------------------------------------------------------------------*\
 * FORMS EVENTS
\*----------------------------------------------------------------------*/
//event update first time
$('.body').on('click', '#update-newevent', function(){
    var formDataEvent = new FormData();
    
    $nameinput =        $('#add-event-name');
    $logoinput =        $('#add-event-logoevent');
    $gameinput =        $('#add-event-game');
    $plateforminput =   $('#add-event-platform');
    $roleinput =        $('#add-event-role');
    $startdayinput =    $('#add-event-start-day');
    $startmonthinput =  $('#add-event-start-month');
    $startyearinput =   $('#add-event-start-year');
    $enddayinput =      $('#add-event-end-day');
    $endmonthinput =    $('#add-event-end-month');
    $endyearinput =     $('#add-event-end-year');
    $teaminput =        $('#add-event-team');
    $rankinput =        $('#add-event-rank');
    var desc =          $.trim($('#add-event-descript').text());

    if($.trim($nameinput.text()) == '')
    {
        $nameinput.addClass('error-input');
        return false;
    }
    if($.trim($gameinput.text()) == '')
    {
        $gameinput.addClass('error-input');
        return false;
    }
    if($.trim($plateforminput.text()) == '')
    {
        $plateforminput.addClass('error-input');
        return false;
    }
    if($.trim($teaminput.text()) == '')
    {
        $teaminput.addClass('error-input');
        return false;
    }
    if($.trim($roleinput.text()) == '')
    {
        $roleinput.addClass('error-input');
        return false;
    }
    if(!checkDate($startdayinput.val(), $startmonthinput.val(), $startyearinput.val()))
    {
        $startyearinput.parent().addClass('error-input');
        return false;
    }
    if(!checkDate($enddayinput.val(), $endmonthinput.val(), $endyearinput.val()))
    {
        $endmonthinput.parent().addClass('error-input');
        return false;
    }
    if(!ckeckAnteriority($startdayinput.val(), $startmonthinput.val(), $startyearinput.val(), $enddayinput.val(), $endmonthinput.val(), $endyearinput.val()))
    {
        $endyearinput.parent().addClass('error-input-special');
        return false;
    }
    else{
        //create date
        var startDate = $startdayinput.val() + '-' + $startmonthinput.val() + '-' +  $startyearinput.val();
        var endDate = $enddayinput.val() + '-' + $endmonthinput.val() + '-' + $endyearinput.val();

        //stor data in formData object
        formDataEvent.append('eventname', $nameinput.text());
        formDataEvent.append('game', $gameinput.text());
        formDataEvent.append('platform', $plateforminput.text());
        formDataEvent.append('role', $roleinput.text());
        formDataEvent.append('startDate', startDate);
        formDataEvent.append('endDate', endDate);
        formDataEvent.append('team', $teaminput.text());
        formDataEvent.append('rank', $rankinput.text());
        formDataEvent.append('desc', desc);

        //parametter la photo
        if($logoinput.val() != '')
        {
            $file = $logoinput[0].files[0];
            formDataEvent.append('logo', $file);
        }
        else{
            formDataEvent.append('logo', 'default');
        }
        //definie type action
        formDataEvent.append('typeaction','create');
        updateEvents(formDataEvent);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

//display event edit mode
$('.body').on('click', '#edit-myevents .edit-gear', function()
{
    displayAddElem('mevts');
    AJAXloader(true, '#loader-events');
});
/*----------------------------------------------------------------------*\
 * FORMS GAMES
\*----------------------------------------------------------------------*/
//career update first time
$('.body').on('click', '#update-newgame-ft', function(){
    var formDataGame = new FormData();

    $nameinput =        $('#add-game-name');
    $logoinput =        $('#add-game-logo');
    $gameacountinput =  $('#add-game-gameaccount');
    $plateforminput =   $('#add-game-platform');

    if($.trim($nameinput.text()) == '')
    {
        $nameinput.addClass('error-input');
        return false;
    }
    if($.trim($gameacountinput.text()) == '')
    {
        $gameacountinput.addClass('error-input');
        return false;
    }
    if($plateforminput.val() == '')
    {
        $plateforminput.parent().addClass('error-input');
        return false;
    }
    if($logoinput.val() == '')
    {
        $logoinput.parent().addClass('error-input');
    }
    else{
        //store datas in formDat object
        formDataGame.append('gamename', $nameinput.text());
        formDataGame.append('gameaccount', $gameacountinput.text());
        formDataGame.append('platform', $plateforminput.val());
        $file = $logoinput[0].files[0];
        formDataGame.append('logo', $file);

        //definie type action
        formDataGame.append('typeaction','create');
        updateGames(formDataGame);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

//display mygames edit mode
$('.body').on('click', '#edit-mygames .edit-gear', function()
{
    displayAddElem('mga');
    AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
});
/*----------------------------------------------------------------------*\
 * FORMS EQUIPMENTS
\*----------------------------------------------------------------------*/
//career update first time
$('.body').on('click', '#update-newgear-ft', function(){
    var formDataEqui = new FormData();

    $brandinput =           $('#add-equipment-equipmentbrand');
    $typeinput =            $('#addequipment-typegear');
    $referencetinput =      $('#add-equipment-reference');
    $configlinkinput =      $('#add-equipment-config');

    if($typeinput.val() == '')
    {
        $typeinput.parent().addClass('error-input');
        return false;
    }
    if(!$brandinput.parent().hasClass('hided-field'))
    {
        if($.trim($brandinput.text()) == '')
        {
            $brandinput.addClass('error-input');
            return false;
        }
    }
    if(!$referencetinput.parent().hasClass('hided-field'))
    {
        if($referencetinput.text() == '')
        {
            $referencetinput.addClass('error-input');
            return false;
        }
    }
    if(!$configlinkinput.parent().hasClass('hided-field') && $configlinkinput.text() == '')
    {
        $configlinkinput.addClass('error-input');
        return false;
    }
    else{
        //store datas in formData object
        formDataEqui.append('typegear', $typeinput.val());
        formDataEqui.append('brand', $brandinput.text());
        formDataEqui.append('model', $referencetinput.text());
        formDataEqui.append('configlink', $configlinkinput.text());

        //definie type action
        formDataEqui.append('typeaction','create');
        updateEquipments(formDataEqui);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

$('.body').on('change', '#addequipment-typegear', function(event){
    if($(this).val() == 'cfg')
    {
        $('#add-equipment-equipmentbrand').parent().addClass('hided-field');
        $('#add-equipment-reference').parent().addClass('hided-field');
        $('#add-equipment-config').parent().removeClass('hided-field');
    }
    else{
        $('#add-equipment-equipmentbrand').parent().removeClass('hided-field');
        $('#add-equipment-reference').parent().removeClass('hided-field');
        $('#add-equipment-config').parent().addClass('hided-field');
    }
});

//display myequipments edit mode
$('.body').on('click', '#edit-myequipments .edit-gear', function()
{
    displayAddElem('meq');
    AJAXloader(true, '#loader-equipments');
});
/*----------------------------------------------------------------------*\
 * FORMS GAMER SUMMARY
\*----------------------------------------------------------------------*/
//update new summary
$('.body').on('click', 'button[data-action="update-gamer-summary"]', function()
{
    $summaryinput = $(this).parent().parent().find('div[data-elem="add-summary-input"]');

    if($.trim($summaryinput.text()) == '')
    {
        $summaryinput.addClass('error-input');
        return false;
    }
    else{
        updateSummary($.trim($summaryinput.text()));
        AJAXloader(true, 'div[data-elem="loader-summary-body"]');
    }
});

//display summary edit mode
$('.body').on('click', 'div[data-action="edit-summary"] .edit-gear', function()
{
    AJAXloader(true, 'div[data-elem="loader-summary-body"]');
    displayAddElem('sum');
});

/*----------------------------------------------------------------------*\
 * FORM LIVE TWITCH
\*----------------------------------------------------------------------*/
//update new live first time
$('.aside-right').on('click', 'button[data-action="update-live"]', function(){
    $inputLive = $(this).closest('#twitch-bloc').find($('#input-live-embeded'));
    var embededHtml = $.trim($inputLive.text());
    var typeAction = 'create';

    if(embededHtml == '')
    {
        $inputLive.addClass('error-input');
        return false;
    }
    if(embededHtml.indexOf('<iframe src="https://player.twitch.tv/?channel=') == -1)
    {
        $inputLive.addClass('error-input');
        return false;
    }
    else{
        //parse html of embededHtml to get link channel
        var html = $.parseHTML(embededHtml);
        var tmp = $(html).attr('src');
        var pieces = tmp.split('channel=');
        var channelLink = 'https://www.twitch.tv/' + pieces[1];

        //delete link
        pieces = embededHtml.split('<a');
        var embed = pieces[0];
        
        if(!embed.endsWith("</iframe>"))
        {
            $inputLive.addClass('error-input');
            return false;
        }
        updateLive(embed, channelLink, typeAction);
        AJAXloaderElem(true, $('#loader-live-body'));
    }
});

//display edit mode live
$('.aside-right').on('click', 'div[data-action="edit-live"] .edit-gear', function(){
    displayAddElem('live');
});


