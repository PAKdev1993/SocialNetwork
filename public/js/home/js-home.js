function inviteSomeone(email) {
    $.ajax({
        method: 'POST',
        url: "inc/Invitation/InviteSomeone.php",
        data: {email: email}
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

function showNextsPosts() {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/shownextsposts.php"
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

//a l'entrée de texte retirer le placeholder
$("div[contenteditable='true']").keyup(function(){
   $(this).addClass('editable-content-active');
}).keydown(function(){
    if($(this).text() == " "){
        $(this).removeClass('editable-content-active');
    }
});

/*----------------------------------------------------------------------*\
 * INVITATION MODULE
\*----------------------------------------------------------------------*/
//add email input
var clicks = 0;
var htmlToAdd = '<div class="mail-bloc">' +
                    '<input class="input" type="text" name="mails-toinvite" placeholder="Enter an email to send the invitation">' +
                '</div>';

$('#add-email').click(function(){
    if(clicks <= 10)
    {
        $('.mails-container').append(htmlToAdd);
        clicks++;
    }
});

//send invitations
$('#invitation-container').on('click', "button[data-action='invite']", function(){
    var regexEmail = "^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$";

    $("input[name='mails-toinvite']").each(function()
    {
        if (!$(this).val().match(regexEmail) || $(this).val() == '') {
            $(this).parent().find('');
            $('#invitation-container .error-field').addClass('novisible');
            $('.error-email').removeClass('novisible');
            $(this).addClass('error-input');
            return false;
        }
        else {
            inviteSomeone($(this).val());
            viderChamps('#invitation-container');
        }
    })
});
//error remove #todo code en double c'st sur, le meme genre de chose est utilisé pour le test du share bloc
$('#invitation-container').on('click', '.error-input', function(){
    $(this).removeClass('error-input');
    $('.error-email').addClass('novisible')
});

/*----------------------------------------------------------------------*\
 * SHOW MORE ON HOME TIMELINE
\*----------------------------------------------------------------------*/
$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() == $(document).height()) {
        showNextsPosts();
    }
});
/*----------------------------------------------------------------------*\
 * DISPLAY EMOTICONS
\*----------------------------------------------------------------------*/
    /*var definition = {smile:{title:"Smile",codes:[":)",":=)",":-)"]},"sad-smile":{title:"Sad Smile",codes:[":(",":=(",":-("]},"big-smile":{title:"Big Smile",codes:[":D",":=D",":-D",":d",":=d",":-d"]},cool:{title:"Cool",codes:["8)","8=)","8-)","B)","B=)","B-)","(cool)"]},wink:{title:"Wink",codes:[":o",":=o",":-o",":O",":=O",":-O"]},crying:{title:"Crying",codes:[";(",";-(",";=("]},sweating:{title:"Sweating",codes:["(sweat)","(:|"]},speechless:{title:"Speechless",codes:[":|",":=|",":-|"]},kiss:{title:"Kiss",codes:[":*",":=*",":-*"]},"tongue-out":{title:"Tongue Out",codes:[":P",":=P",":-P",":p",":=p",":-p"]},blush:{title:"Blush",codes:["(blush)",":$",":-$",":=$",':">']},wondering:{title:"Wondering",codes:[":^)"]},sleepy:{title:"Sleepy",codes:["|-)","I-)","I=)","(snooze)"]},dull:{title:"Dull",codes:["|(","|-(","|=("]},"in-love":{title:"In love",codes:["(inlove)"]},"evil-grin":{title:"Evil grin",codes:["]:)",">:)","(grin)"]},talking:{title:"Talking",codes:["(talk)"]},yawn:{title:"Yawn",codes:["(yawn)","|-()"]},puke:{title:"Puke",codes:["(puke)",":&",":-&",":=&"]},"doh!":{title:"Doh!",codes:["(doh)"]},angry:{title:"Angry",codes:[":@",":-@",":=@","x(","x-(","x=(","X(","X-(","X=("]},"it-wasnt-me":{title:"It wasn't me",codes:["(wasntme)"]},party:{title:"Party!!!",codes:["(party)"]},worried:{title:"Worried",codes:[":S",":-S",":=S",":s",":-s",":=s"]},mmm:{title:"Mmm...",codes:["(mm)"]},nerd:{title:"Nerd",codes:["8-|","B-|","8|","B|","8=|","B=|","(nerd)"]},"lips-sealed":{title:"Lips Sealed",codes:[":x",":-x",":X",":-X",":#",":-#",":=x",":=X",":=#"]},hi:{title:"Hi",codes:["(hi)"]},call:{title:"Call",codes:["(call)"]},devil:{title:"Devil",codes:["(devil)"]},angel:{title:"Angel",codes:["(angel)"]},envy:{title:"Envy",codes:["(envy)"]},wait:{title:"Wait",codes:["(wait)"]},bear:{title:"Bear",codes:["(bear)","(hug)"]},"make-up":{title:"Make-up",codes:["(makeup)","(kate)"]},"covered-laugh":{title:"Covered Laugh",codes:["(giggle)","(chuckle)"]},"clapping-hands":{title:"Clapping Hands",codes:["(clap)"]},thinking:{title:"Thinking",codes:["(think)",":?",":-?",":=?"]},bow:{title:"Bow",codes:["(bow)"]},rofl:{title:"Rolling on the floor laughing",codes:["(rofl)"]},whew:{title:"Whew",codes:["(whew)"]},happy:{title:"Happy",codes:["(happy)"]},smirking:{title:"Smirking",codes:["(smirk)"]},nodding:{title:"Nodding",codes:["(nod)"]},shaking:{title:"Shaking",codes:["(shake)"]},punch:{title:"Punch",codes:["(punch)"]},emo:{title:"Emo",codes:["(emo)"]},yes:{title:"Yes",codes:["(y)","(Y)","(ok)"]},no:{title:"No",codes:["(n)","(N)"]},handshake:{title:"Shaking Hands",codes:["(handshake)"]},skype:{title:"Skype",codes:["(skype)","(ss)"]},heart:{title:"Heart",codes:["(h)","<3","(H)","(l)","(L)"]},"broken-heart":{title:"Broken heart",codes:["(u)","(U)"]},mail:{title:"Mail",codes:["(e)","(m)"]},flower:{title:"Flower",codes:["(f)","(F)"]},rain:{title:"Rain",codes:["(rain)","(london)","(st)"]},sun:{title:"Sun",codes:["(sun)"]},time:{title:"Time",codes:["(o)","(O)","(time)"]},music:{title:"Music",codes:["(music)"]},movie:{title:"Movie",codes:["(~)","(film)","(movie)"]},phone:{title:"Phone",codes:["(mp)","(ph)"]},coffee:{title:"Coffee",codes:["(coffee)"]},pizza:{title:"Pizza",codes:["(pizza)","(pi)"]},cash:{title:"Cash",codes:["(cash)","(mo)","($)"]},muscle:{title:"Muscle",codes:["(muscle)","(flex)"]},cake:{title:"Cake",codes:["(^)","(cake)"]},beer:{title:"Beer",codes:["(beer)"]},drink:{title:"Drink",codes:["(d)","(D)"]},dance:{title:"Dance",codes:["(dance)","\\o/","\\:D/","\\:d/"]},ninja:{title:"Ninja",codes:["(ninja)"]},star:{title:"Star",codes:["(*)"]},mooning:{title:"Mooning",codes:["(mooning)"]},finger:{title:"Finger",codes:["(finger)"]},bandit:{title:"Bandit",codes:["(bandit)"]},drunk:{title:"Drunk",codes:["(drunk)"]},smoking:{title:"Smoking",codes:["(smoking)","(smoke)","(ci)"]},toivo:{title:"Toivo",codes:["(toivo)"]},rock:{title:"Rock",codes:["(rock)"]},headbang:{title:"Headbang",codes:["(headbang)","(banghead)"]},bug:{title:"Bug",codes:["(bug)"]},fubar:{title:"Fubar",codes:["(fubar)"]},poolparty:{title:"Poolparty",codes:["(poolparty)"]},swearing:{title:"Swearing",codes:["(swear)"]},tmi:{title:"TMI",codes:["(tmi)"]},heidy:{title:"Heidy",codes:["(heidy)"]},myspace:{title:"MySpace",codes:["(MySpace)"]},malthe:{title:"Malthe",codes:["(malthe)"]},tauri:{title:"Tauri",codes:["(tauri)"]},priidu:{title:"Priidu",codes:["(priidu)"]}};

    $.emoticons.define(definition);

    //$('#overview').html($.emoticons.toString())
    $('div[contenteditable="true"]').on('keypress', function() {
        var $text = $(this),
            $in = $(this);

        setTimeout(function() {
            $text.html($.emoticons.replace($in.val()));
        }, 100);
    });*/