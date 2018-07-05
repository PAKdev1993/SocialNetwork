/*----------------------------------------------------------------------*\
 * LAUNCH SEARCH AUTOMATICLY                                            *
\*----------------------------------------------------------------------*/
$('input[name="keyword-searchbar"]').ready(function(){
    var value = $.trim($('input[name="keyword-searchbar"]').val());
    if(value.length < 40)
    {
        searchFromSearchPage($.trim($('input[name="keyword-searchbar"]').val()));
    }
    return false;
});