function searchFromHeader(strinToSearch)
{
    $.ajax({
        method: 'POST',
        url: "inc/Search/searchFromHeader.php",
        data: {strinToSearch: strinToSearch}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('.search-bar-header-results').remove();
            $(msg).insertAfter($('.search-bar-header form'));
            imgSmoothLoading();
        }
        else{
            window.location.reload();
        }
    });
}

function searchFromSearchPage(strinToSearch)
{
    $.ajax({
        method: 'POST',
        url: "inc/Search/searchFromSearchPage.php",
        data: {strinToSearch: strinToSearch}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('.search-results-container').remove();
            $(msg).insertAfter($('#slide-searchresults .search-bar'));
            imgSmoothLoading();
        }
        else{
            window.location.reload();
        }
    });
}

/*----------------------------------------------------------------------*\
 * KEY UP / DOWN HEADER SEARCH BAR                                      *
\*----------------------------------------------------------------------*/
$("input[name='keyword_searchbar_header']").keyup(function(e){
    if($(this).val() == '')
    {
        $('.search-bar-header-results').remove();
    }
    else{
        var value = $.trim($(this).val());
        if(value.length <= 25)
        {
            searchFromHeader(value);
        }
    }
});
$("input[name='keyword_searchbar_header_mobile']").keyup(function(e){
    if($(this).val() == '')
    {
        $('.search-bar-header-results').remove();
    }
    else{
        var value = $.trim($(this).val());
        if(value.length <= 25)
        {
            searchFromHeader(value);
        }
    }
});

/*----------------------------------------------------------------------*\
 * SEND SEARCHSTRING WHEN CLICK ON SHOW MORE / SEARCH                   *
\*----------------------------------------------------------------------*/
$(document).on('click', 'a[data-action="show-more-results"]', function(){
    var value = $(this).closest($('div[data-elem="search-bar-container"]')).find('input[type="text"]').val();
    var oldhref =   $(this).attr('href');
    var newhref =   oldhref + '&tosearch=' + value;
    $(this).attr('href', newhref);
    return true;
});

$(function(){
    //get value sent
    var valueHeader =        $('input[name="keyword_searchbar_header"]').val();
    var valueHeaderMobile =  $('input[name="keyword_searchbar_header_mobile"]').val();
    var value;
    if(valueHeader === undefined)
    {
        value = valueHeader;
    }
    if(valueHeaderMobile === undefined)
    {
        value = valueHeader;
    }
    if(valueHeaderMobile == valueHeader)
    {
        value = valueHeader;
    }
    value = $.trim(value);
    //test on value
    if(value.length > 0 && value.length <= 25)
    {
        searchFromSearchPage(value);
    }
});
/*----------------------------------------------------------------------*\
 * KEY UP / DOWN SEARCH BAR                                             *
\*----------------------------------------------------------------------*/
$('input[name="keyword-searchbar"]').keyup(function(e){
    if($(this).val() == '')
    {
        $('.search-results-container').remove();
    }
    else{
        var value = $.trim($(this).val());
        if(value.length <= 25)
        {
            searchFromSearchPage(value);
        }
    }
});
