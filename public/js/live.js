//Put live on
function putLiveOnOff(action) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/turnLiveOnOff.php",
        data:{action: action}
    }).done(function(msg){

    });
}

//Post live
function postLive(text) {
    $.ajax({
        method: 'POST',
        url: "inc/Myprofile/postLive.php",
        data:{text: text}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('button[data-action="post-live"]').addClass('active');
            $('div[data-elem="post-live-input"]').html('');
            setTimeout(function(){ $('button[data-action="post-live"]').removeClass('active'); }, 3000);
        }
        else{
            window.location.reload();
        }
    });
}

$(document).on('click', 'div[data-action="put-live-on"]', function(){
    $parent = $(this).closest($('.bloc-container')).attr('data-state','online');
    $parent.find($('.block-status')).removeClass('offline').addClass('online');
    var action = "on";
    putLiveOnOff(action);
});

$(document).on('click', 'button[data-action="stop-live"]', function(){
    $parent = $(this).closest($('.bloc-container')).attr('data-state','offline');
    $parent.find($('.block-status')).removeClass('online').addClass('offline');
    var action = "off";
    putLiveOnOff(action);
});

//POST LIVE
$('.aside-right').on('click', 'button[data-action="post-live"]', function(){
    $inputPostLive = $(this).closest('#twitch-bloc').find($('#input-post-live'));
    var text = $.trim($inputPostLive.text());

    if(text == '')
    {
        $inputPostLive.addClass('error-input');
        return false;
    }
    else{
        var textPosted = $inputPostLive.html().replace(/<(?!\/?a(?=>|\s.*>))\/?.*?>/gi, '');
        postLive(textPosted);
    }
});

//Control active twitch embeded
//$.getScript("http://player.twitch.tv/js/embed/v1.js", function(){

    //alert("Script loaded but not necessarily executed.");

//});