function updateEmployeeCareer(formData, companyname, city, country, jobtitle)
{
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateEmployeeCareer.php",
        data: formData,
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
            AJAXloader(false, '#loader-company');

            //test pour les modification des QuickInfos
            //update advanced quickinfos
            AJAXloader(true, '#loader-quickinfos-body');
            updateAdvancedQuickInfosEmployee(companyname, jobtitle, city, country, pieces[1]);
        }
        else{
            window.location.reload();
        }
    });
}

function updateEmployeeEvents(formDataEvents)
{
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateEmployeeEvent.php",
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

function updateEmployeeSummary(summary) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/updateEmployeeSummary.php",
        data: {summary: summary}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('div[data-elem="employee-summary-bloc"]').html('').append(msg);
            AJAXloader(false, 'div[data-elem="loader-summary-body"]');
        }
        else{
            window.location.reload();
        }
    });
}

/*----------------------------------------------------------------------*\
 * FORMS EMPLOYEE CAREER
\*----------------------------------------------------------------------*/
//career update first time
$('.body').on('click', '#update-newcompany', function(){
    var formData = new FormData();

    $companyinput =     $('#add-company-companyname');
    $logoinput =        $('#add-company-companylogo');
    $cityinput =        $('#add-company-companycity');
    $countryinput =     $('#add-company-companycountry');
    $jobtitleinput =    $('#add-company-companyjobtitle');
    $startmonthinput =  $('#add-company-start-month');
    $startyearinput =   $('#add-company-start-year');
    $endmonthinput =    $('#add-company-end-month');
    $endyearinput =     $('#add-company-end-year');
    $currentworkinput = $('#add-company-current-activity');
    var desc =          $.trim($('#add-company-decript').text());

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
        var endDate = '01' + '-' + $startmonthinput.val() + '-' + $startyearinput.val();

        //parametrer currentCompany
        var currentCompany;
        if($currentworkinput.is(':checked'))
        {
            currentCompany = 1;
        }
        else{
            currentCompany = 0;
        }
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
        //definie type action
        formData.append('typeaction','create');
        updateEmployeeCareer(formData, $.trim($companyinput.text()), $.trim($cityinput.text()), $.trim($countryinput.text()), $.trim($jobtitleinput.text()));
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

//display mycompanies edit mode
$('.body').on('click', '#edit-mycompanies .edit-gear', function()
{
    displayAddElem('mc');
    AJAXloader(true, '#loader-company-body');
});
/*----------------------------------------------------------------------*\
 * REMOVE ENDDATE WHEN CLICK ON "CURRENT work HERE"
\*----------------------------------------------------------------------*/
//display date end when current is selected for gamer's team
$(document).on('click', 'label[for="add-company-current-activity"]', function(){
    $('#add-company-current-activity').change(function() {
        if($(this).is(":checked"))
        {
            $('#enddate-field').addClass('hided-field');
        }
        else{
            $('#enddate-field').removeClass('hided-field');
        }
    });
});
/*----------------------------------------------------------------------*\
 * FORMS EMPLOYEE EVENTS
\*----------------------------------------------------------------------*/
//career update first time
$('.body').on('click', '#update-newemployeeevent', function(){
    var formDataEvent = new FormData();
    
    $eventnameinput =   $('#add-employee-event-name');
    $logoinput =        $('#add-employee-eventlogo');
    $jobtitleinput =    $('#add-employee-event-jobtitle');
    $startdayinput =    $('#add-employee-event-start-day');
    $startmonthinput =  $('#add-employee-event-start-month');
    $startyearinput =   $('#add-employee-event-start-year');
    $enddayinput =      $('#add-employee-event-end-day');
    $endmonthinput =    $('#add-employee-event-end-month');
    $endyearinput =     $('#add-employee-event-end-year');
    $companyinput =     $('#add-employee-event-company');
    var desc =          $.trim($('#add-employee-event-descript').text());

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
        formDataEvent.append('typeaction','create');
        updateEmployeeEvents(formDataEvent);
        AJAXloaderElem(true, $(this).closest($('.update-button-container')).find('div[data-elem="loader-bt"]'));
    }
});

//display event edit mode
$('.body').on('click', '#edit-myemployeeevents .edit-gear', function()
{
    displayAddElem('empe');
    AJAXloader(true, '#loader-employee-events-body');
});
/*----------------------------------------------------------------------*\
 * FORMS EMPLOYEE SUMMARY
\*----------------------------------------------------------------------*/
//quick informations update first time (première update <=> create)
$('.body').on('click', 'button[data-action="update-employee-summary"]', function()
{
    $summaryinput = $(this).parent().parent().find('div[data-elem="add-summary-input"]');

    if($.trim($summaryinput.text()) == '')
    {
        $summaryinput.addClass('error-input');
        return false;
    }
    else{
        updateEmployeeSummary($.trim($summaryinput.text()));
        AJAXloader(true, 'div[data-elem="loader-summary-body"]');
    }
});

//display summary edit mode
$('.body').on('click', 'div[data-action="edit-summary"] .edit-gear', function()
{
    AJAXloader(true, 'div[data-elem="loader-summary-body"]');
    displayAddElem('sumemp');
});
/*----------------------------------------------------------------------*\
 * REMOVE ERROR MESSAGES
\*----------------------------------------------------------------------*/
$(document).on('click', '.field-container', function(){
    $(this).find('.error-input').removeClass('error-input');
    $(this).find('.error-input-special').removeClass('error-input-special');
});

/*----------------------------------------------------------------------*\
 * REMOVE ENDDATE WHEN CLICK ON "CURRENT WORK HERE"
\*----------------------------------------------------------------------*/
$(document).on('click', 'label[for="add-company-current-activity"]', function(){
    $('#add-company-current-activity').change(function() {
        if($(this).is(":checked"))
        {
            $('#add-company-end-month').parents('.field-container').addClass('hided-field');
        }
        else{
            $('#add-company-end-month').parents('.field-container').removeClass('hided-field');
        }
    });
});