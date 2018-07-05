$('.body').on('click', 'div[data-notif-link]', function(){
    var link = $(this).attr('data-notif-link');
    window.location.replace(link);
    return true;
});