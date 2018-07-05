function consultNotif(idNotif, link) {
    $.ajax({
        method: 'POST',
        url: "inc/Notifications/consultNotif.php",
        data:{id: idNotif}
    }).done(function(msg){
        if(msg != 'err')
        {
            var url = window.location.href;
            //test si le liens de la notification est contenu dans l'url courante, s'il l'est on reload la page
            if (url.indexOf(link) >= 0)
            {
                window.location.reload();
                return true;
            }
            window.location.replace(link);
            return true;
        }
        else{
            window.location.reload();
        }
    });
}

$('.header').on('click', 'div[data-notif-id]', function(){
    var idNotif = $(this).attr('data-notif-id');
    var link = $(this).attr('data-notif-link');
    consultNotif(idNotif, link);
});

//addclass au li pour afficher le menu
$('.user-menu-items li').mouseenter(function(e){
    $(this).addClass('active');
});
$('.user-menu-items li').mouseleave(function(e){
    $(this).removeClass('active');
});

/*----------------------------------------------------------------------*\
 * TOGGLE MENU MOBILE
\*----------------------------------------------------------------------*/
var toggle = 0;
$(document).on('click', '#bt-menu', function(){
    if(toggle == 0){
        $('.navbar-toggle').addClass('active-navbar');
        toggle++;
        return false;
    }
    else{
        $('.navbar-toggle').removeClass('active-navbar');
        toggle--;
        return false;
    }
});
