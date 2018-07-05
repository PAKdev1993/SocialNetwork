var type = '';
var indexToDelete = '';


//*################################################################################################


/*----------------------------------------------------------------------*\
 * DISPLAY EDIT MENU FOR TEAM
 \*----------------------------------------------------------------------*/
$('.body').on('click', '#slide-mycareer .profile-elem .edit-gear', function()
{
    //erase precedent edit-bloc
    $('.edit-options').removeClass('active').html('');

    //get index of elemen to insert after
    $elem = $(this).parents('.profile-elem');
    var index = $elem.index(); //#todo comprendre prk la c'est index - 1 et ds event juste index pour que ca fonctionne

    //get ids for operations
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    type = 'team';

    //send to display edit-mode
    prepareEdit(idelem, type, index);
});
/*----------------------------------------------------------------------*\
 * ASK DELETE TEAM
 \*----------------------------------------------------------------------*/
$('#slide-mycareer').on('click', '.bt-delete', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    indexToDelete = $(this).parents('.profile-elem').index();
    askDelete(idelem, type);
});
/*----------------------------------------------------------------------*\
 * EDIT A TEAM
 \*----------------------------------------------------------------------*/
$('#slide-mycareer').on('click', '.bt-edit', function(){
    //display loader
    $elem = $(this).parents('.profile-aside-container').find('.loader-container');
    AJAXloader(true, $elem);

    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var indexToEdit = $(this).parents('.profile-elem').index();
    displayEditElem(idelem, type, indexToEdit);
});

$('#slide-mycareer').on('click', '#update-team', function(){
    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');

    var formData = new FormData();

    $teaminput =        $('#edit-team-teamname');
    $logoinput =        $('#edit-team-logoteam');
    $gameinput =        $('#edit-team-game');
    $plateforminput =   $('#edit-team-platform');
    $roleinput =        $('#edit-team-role');
    $startmonthinput =  $('#edit-team-start-month');
    $startyearinput =   $('#edit-team-start-year');
    $endmonthinput =    $('#edit-team-end-month');
    $endyearinput =     $('#edit-team-end-year');
    var desc =          $.trim($('#edit-team-decript').text());

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
        if($('#edit-team-current-activity').is(':checked'))
        {
            currentTeam = 1;
        }
        else{
            currentTeam = 0;
        }
        formData.append('teamid', idelem);
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

        //definition de l'action
        formData.append('typeaction', 'update');
        updateCareer(formData, $.trim($teaminput.text()), $.trim($gameinput.text()), $.trim($plateforminput.text()), $.trim($roleinput.text()));
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

/*----------------------------------------------------------------------*\
 * REMOVE ENDDATE WHEN CLICK ON "CURRENT work HERE"
\*----------------------------------------------------------------------*/
//display date end when current is selected for gamer's team
$(document).on('click', 'label[for="edit-team-current-activity"]', function(){
    $('#edit-team-current-activity').change(function() {
        if($(this).is(":checked"))
        {
            $('#enddate-field').addClass('hided-field');
        }
        else{
            $('#enddate-field').removeClass('hided-field');
        }
    });
});

//*################################################################################################

/*----------------------------------------------------------------------*\
 * DISPLAY EDIT MENU FOR EVENT
\*----------------------------------------------------------------------*/
$('.body').on('click', '#slide-myevents .profile-elem .edit-gear', function()
{
    //erase precedent edit-bloc
    $('.edit-options').removeClass('active').html('');

    //get index of elemen to insert after
    $elem = $(this).parents('.profile-elem');
    var index = $elem.index();

    //get ids for operations
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    type = 'event';

    //send to display edit-mode
    prepareEdit(idelem, type, index);
});
/*----------------------------------------------------------------------*\
 * ASK DELETE EVENT
\*----------------------------------------------------------------------*/
$('#slide-myevents').on('click', '.bt-delete', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    indexToDelete = $(this).parents('.profile-elem').index();
    askDelete(idelem, type);
});
/*----------------------------------------------------------------------*\
 * EDIT EVENT
\*----------------------------------------------------------------------*/
$('#slide-myevents').on('click', '.bt-edit', function(){
    //display loader
    $elem = $(this).parents('.profile-aside-container').find('.loader-container');
    AJAXloader(true, $elem);

    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var indexToEdit = $(this).parents('.profile-elem').index();
    displayEditElem(idelem, type, indexToEdit);
});

$('#slide-myevents').on('click', '#update-event', function(){
    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var formData = new FormData();

    $eventinput =       $('#edit-event-name');
    $logoinput =        $('#edit-event-logoevent');
    $gameinput =        $('#edit-event-game');
    $plateforminput =   $('#edit-event-platform');
    $teaminput =        $('#edit-event-team');
    $roleinput =        $('#edit-event-role');
    $rankinput =        $('#edit-event-rank');
    $startdayinput =    $('#edit-event-start-day');
    $startmonthinput =  $('#edit-event-start-month');
    $startyearinput =   $('#edit-event-start-year');
    $enddayinput =    $('#edit-event-end-day');
    $endmonthinput =    $('#edit-event-end-month');
    $endyearinput =     $('#edit-event-end-year');
    var desc =          $.trim($('#edit-event-descript').text());

    if($.trim($eventinput.text()) == '')
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
    if($.trim($rankinput.text()) == '')
    {
        $rankinput.addClass('error-input');
        return false;
    }
    if(!checkDate($startdayinput.val(), $startmonthinput.val(), $startyearinput.val()))
    {
        $startyearinput.parent().addClass('error-input');
        return false;
    }
    if(!checkDate($enddayinput.val(), $endmonthinput.val(), $endyearinput.val()) && !$endmonthinput.parents('.field-container').hasClass('hided-field'))
    {
        $endyearinput.parent().addClass('error-input');
        return false;
    }
    if(!ckeckAnteriority($startdayinput.val(), $startmonthinput.val(), $startyearinput.val(), $enddayinput.val(), $endmonthinput.val(), $endyearinput.val()) && !$endmonthinput.parents('.field-container').hasClass('hided-field'))
    {
        $endmonthinput.parent().addClass('error-input-special');
        return false;
    }
    else{
        //create date
        var startDate = $startdayinput.val() + '-' + $startmonthinput.val() + '-' +  $startyearinput.val();
        var endDate = $enddayinput.val() + '-' + $endmonthinput.val() + '-' + $endyearinput.val();

        formData.append('eventid', idelem);
        formData.append('eventname', $eventinput.text());
        formData.append('game', $gameinput.text());
        formData.append('platform', $plateforminput.text());
        formData.append('role', $roleinput.text());
        formData.append('startDate', startDate);
        formData.append('endDate', endDate);
        formData.append('team', $teaminput.text());
        formData.append('rank', $rankinput.text());
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

        //definition de l'action
        formData.append('typeaction', 'update');
        updateEvents(formData);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

//*################################################################################################


/*----------------------------------------------------------------------*\
 * DISPLAY EDIT MENU FOR GAME
\*----------------------------------------------------------------------*/
$('.body').on('click', '#slide-mygames .profile-elem .edit-gear', function()
{
    //erase precedent edit-bloc
    $('.edit-options').removeClass('active').html('');

    //get index of elemen to insert after
    $elem = $(this).parents('.profile-elem');
    var index = $elem.index(); //#todo comprendre prk la c'est index - 1 et ds event juste index pour que ca fonctionne

    //get ids for operations
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    type = 'game';

    //send to display edit-mode
    prepareEdit(idelem, type, index);
});
/*----------------------------------------------------------------------*\
 * ASK DELETE GAME
\*----------------------------------------------------------------------*/
$('#slide-mygames').on('click', '.bt-delete', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    indexToDelete = $(this).parents('.profile-elem').index();
    askDelete(idelem, type);
});
/*----------------------------------------------------------------------*\
 * EDIT A GAME
\*----------------------------------------------------------------------*/
$('#slide-mygames').on('click', '.bt-edit', function(){
    //display loader
    $elem = $(this).parents('.profile-aside-container').find('.loader-container');
    AJAXloader(true, $elem);

    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var indexToEdit = $(this).parents('.profile-elem').index();
    displayEditElem(idelem, type, indexToEdit);
});

$('#slide-mygames').on('click', '#update-game', function(){
    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var formDataGame = new FormData();

    $gameinput =        $('#edit-game-name');
    $logoinput =        $('#edit-game-logo');
    $accountinput =     $('#edit-game-gameaccount');
    $plateforminput =   $('#edit-game-platform');

    if($.trim($gameinput.text()) == '')
    {
        $gameinput.addClass('error-input');
        return false;
    }
    if($.trim($accountinput.text()) == '')
    {
        $accountinput.addClass('error-input');
        return false;
    }
    else{
        //store datas in formDat object
        formDataGame.append('gameid', idelem);
        formDataGame.append('gamename', $gameinput.text());
        formDataGame.append('gameaccount', $accountinput.text());
        formDataGame.append('platform', $plateforminput.val());

        //parametter la photo
        if($logoinput.val() != '')
        {
            $file = $logoinput[0].files[0];
            formDataGame.append('logo', $file);
        }
        else{
            formDataGame.append('logo', 'default');
        }

        //definition de l'action
        formDataGame.append('typeaction', 'update');
        updateGames(formDataGame);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

//*################################################################################################


/*----------------------------------------------------------------------*\
 * DISPLAY EDIT MENU FOR EQUIPMENTS
\*----------------------------------------------------------------------*/
$('.body').on('click', '#slide-myequipment .profile-elem .edit-gear', function()
{
    //erase precedent edit-bloc
    $('.edit-options').removeClass('active').html('');

    //get index of elemen to insert after
    $elem = $(this).parents('.profile-elem');
    var index = $elem.index(); //#todo comprendre prk la c'est index - 1 et ds event juste index pour que ca fonctionne

    //get ids for operations
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    type = 'equipment';

    //send to display edit-mode
    prepareEdit(idelem, type, index);
});
/*----------------------------------------------------------------------*\
 * ASK DELETE EQUIPMENTS
\*----------------------------------------------------------------------*/
$('#slide-myequipment').on('click', '.bt-delete', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    indexToDelete = $(this).parents('.profile-elem').index();
    askDelete(idelem, type);
});
/*----------------------------------------------------------------------*\
 * EDIT AN EQUIPMENT
\*----------------------------------------------------------------------*/
$('#slide-myequipment').on('click', '.bt-edit', function(){
    //display loader
    $elem = $(this).parents('.profile-aside-container').find('.loader-container');
    AJAXloader(true, $elem);

    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var indexToEdit = $(this).parents('.profile-elem').index();
    displayEditElem(idelem, type, indexToEdit);
});

$('#slide-myequipment').on('click', '#update-equipment', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var formDataEqui = new FormData();

    $brandinput =           $('#edit-equipment-brand');
    $typeinput =            $('#edit-equipment-type');
    $referencetinput =      $('#edit-equipment-reference');
    $configlinkinput =      $('#edit-equipment-config');

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
        formDataEqui.append('equipmentid', idelem);
        formDataEqui.append('typegear', $typeinput.val());
        formDataEqui.append('brand', $brandinput.text());
        formDataEqui.append('model', $referencetinput.text());
        formDataEqui.append('configlink', $configlinkinput.text());

        //definie type action
        formDataEqui.append('typeaction','update');
        updateEquipments(formDataEqui);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

$('.body').on('change', '#edit-equipment-type', function(event){
    if($(this).val() == 'cfg')
    {
        $('#edit-equipment-brand').parent().addClass('hided-field');
        $('#edit-equipment-reference').parent().addClass('hided-field');
        $('#edit-equipment-config').parent().removeClass('hided-field');
    }
    else{
        $('#edit-equipment-brand').parent().removeClass('hided-field');
        $('#edit-equipment-reference').parent().removeClass('hided-field');
        $('#edit-equipment-config').parent().addClass('hided-field');
    }
});