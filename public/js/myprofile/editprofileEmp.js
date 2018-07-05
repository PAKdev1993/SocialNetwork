var type = '';
var indexToDelete = '';


//*################################################################################################
/*----------------------------------------------------------------------*\
 * DISPLAY EDIT MENU FOR COMPANIES
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
    type = 'company';

    //send to display edit-mode
    prepareEdit(idelem, type, index);
});
/*----------------------------------------------------------------------*\
 * ASK DELETE COMPANY
\*----------------------------------------------------------------------*/
$('#slide-mycareer').on('click', '.bt-delete', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    indexToDelete = $(this).parents('.profile-elem').index();
    askDelete(idelem, type);
});
/*----------------------------------------------------------------------*\
 * EDIT A COMPANY
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

$('#slide-mycareer').on('click', '#update-company', function(){
    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var formData = new FormData();

    $companyinput =     $('#edit-company-name');
    $logoinput =        $('#edit-company-logo');
    $cityinput =        $('#edit-company-city');
    $countryinput =     $('#edit-company-country');
    $jobtitleinput =    $('#edit-company-jobtitle');
    $startmonthinput =  $('#edit-company-start-month');
    $startyearinput =   $('#edit-company-start-year');
    $endmonthinput =    $('#edit-company-end-month');
    $endyearinput =     $('#edit-company-end-year');
    var desc =          $.trim($('#edit-company-descript').text());

    if($.trim($companyinput.text()) == '')
    {
        $companyinput.addClass('error-input');
        return false;
    }
    if($.trim($cityinput.text()) == '')
    {
        $cityinput.addClass('error-input');
        return false;
    }
    if($.trim($countryinput.text()) == '')
    {
        $countryinput.addClass('error-input');
        return false;
    }
    if($.trim($jobtitleinput.text()) == '')
    {
        $jobtitleinput.addClass('error-input');
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
        var currentCompany;
        if($('#edit-company-current-activity').is(':checked'))
        {
            currentCompany = 1;
        }
        else{
            currentCompany = 0;
        }
        formData.append('companyid', idelem);
        formData.append('companyname', $companyinput.text());
        formData.append('city', $cityinput.text());
        formData.append('country', $countryinput.text());
        formData.append('jobtitle', $jobtitleinput.text());
        formData.append('startDate', startDate);
        formData.append('endDate', endDate);
        formData.append('currentWork', currentCompany);
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
        updateEmployeeCareer(formData, $.trim($companyinput.text()), $.trim($cityinput.text()), $.trim($countryinput.text()), $.trim($jobtitleinput.text()));
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

/*----------------------------------------------------------------------*\
 * REMOVE ENDDATE WHEN CLICK ON "CURRENT work HERE"
\*----------------------------------------------------------------------*/
//display date end when current is selected for gamer's team
$(document).on('click', 'label[for="edit-company-current-activity"]', function(){
    $('#edit-company-current-activity').change(function() {
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
 * DISPLAY EDIT MENU FOR EVENTS
\*----------------------------------------------------------------------*/
$('.body').on('click', '#slide-myevents .profile-elem .edit-gear', function()
{
    //erase precedent edit-bloc
    $('.edit-options').removeClass('active').html('');

    //get index of elemen to insert after
    $elem = $(this).parents('.profile-elem');
    var index = $elem.index() - 1; //#todo comprendre prk la c'est index - 1 et ds event juste index pour que ca fonctionne

    //get ids for operations
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    type = 'empevent';

    //send to display edit-mode
    prepareEdit(idelem, type, index);
});
/*----------------------------------------------------------------------*\
 * ASK DELETE EMPLOYEE EVENT
\*----------------------------------------------------------------------*/
$('#slide-myevents').on('click', '.bt-delete', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    indexToDelete = $(this).parents('.profile-elem').index() - 1;
    askDelete(idelem, type);
});
/*----------------------------------------------------------------------*\
 * EDIT A EMPLOYEE EVENT
\*----------------------------------------------------------------------*/
$('#slide-myevents').on('click', '.bt-edit', function(){
    //display loader
    $elem = $(this).parents('.profile-aside-container').find('.loader-container');
    AJAXloader(true, $elem);

    //prepare datas
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var indexToEdit = $(this).parents('.profile-elem').index() - 1;
    displayEditElem(idelem, type, indexToEdit);
});

$('#slide-myevents').on('click', '#update-empevent', function(){
    var idelem = $(this).parents('.profile-elem').attr('data-elem');
    var formDataEvent = new FormData();

    $eventnameinput =   $('#edit-empevent-name');
    $logoinput =        $('#edit-empevent-logo');
    $jobtitleinput =    $('#edit-empevent-jobtitle');
    $startdayinput =    $('#edit-empevent-start-day');
    $startmonthinput =  $('#edit-empevent-start-month');
    $startyearinput =   $('#edit-empevent-start-year');
    $enddayinput =      $('#edit-empevent-end-day');
    $endmonthinput =    $('#edit-empevent-end-month');
    $endyearinput =     $('#edit-empevent-end-year');
    $companyinput =     $('#edit-empevent-company');
    var desc =          $.trim($('#edit-empevent-descript').text());

    if($.trim($eventnameinput.text()) == '')
    {
        $eventnameinput.addClass('error-input');
        return false;
    }
    if($.trim($jobtitleinput.text()) == '')
    {
        $jobtitleinput.addClass('error-input');
        return false;
    }
    if($.trim($companyinput.text()) == '')
    {
        $companyinput.addClass('error-input');
        return false;
    }
    if(!checkDate($startdayinput.val(), $startmonthinput.val(), $startyearinput.val()))
    {
        $startmonthinput.parent().addClass('error-input');
        return false;
    }
    if(!checkDate($enddayinput.val(), $endmonthinput.val(), $endyearinput.val()) && !$endmonthinput.parents('.field-container').hasClass('hided-field'))
    {
        $endmonthinput.parent().addClass('error-input');
        return false;
    }
    if(!ckeckAnteriority($startdayinput.val(), $startmonthinput.val(), $startyearinput.val(), $enddayinput.val(), $endmonthinput.val(), $endyearinput.val()) && !$endmonthinput.parents('.field-container').hasClass('hided-field'))
    {
        $endyearinput.parent().addClass('error-input-special');
        return false;
    }
    else{
        //create date
        var startDate = $startdayinput.val() + '-' + $startmonthinput.val() + '-' +  $startyearinput.val();
        var endDate = $enddayinput.val() + '-' + $endmonthinput.val() + '-' + $endyearinput.val();

        //stor data in formData object
        formDataEvent.append('eventid', idelem);
        formDataEvent.append('eventname', $eventnameinput.text());
        formDataEvent.append('jobtitle', $jobtitleinput.text());
        formDataEvent.append('company', $companyinput.text());
        formDataEvent.append('startDate', startDate);
        formDataEvent.append('endDate', endDate);
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
        formDataEvent.append('typeaction','update');
        updateEmployeeEvents(formDataEvent);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});