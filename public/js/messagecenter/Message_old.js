//VARS
var limitBoxesOpen = 4;
var socket =    '';
var myid =      $('body').attr('data-usr');
var convAssocUser = {};

//au chargement de la page charger les conv ouvertes
$(function()
{
    socket = io.connect('http://192.168.0.12:1337');
    socket.emit('login', {userid : myid});
    dispayConversations();
    updateConversationsChatBox();
});

function dispayConversations()
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/displayConvs.php",
        dataType: "json"
    }).done(function(msg){
        //msg tableau json
        //array(
        //  "groupedConversations" => array(HTMLconversation)
        //  "openConversations"    => array(HTMLconversation)
        // );
        if(msg)
        {
            $.each(msg.openConversations, function(cle, valeur)
            {
                $('.chat-boxes-container').append(valeur);
            });

            $.each(msg.groupedConversations, function(cle, valeur)
            {
                //ici on utilise une fonction car cette action implique un calcul des notifs total sur les convs grouped qui n'est pas fais par le serveur
                moveConvToOpenConvs($(valeur), false, false, true);
            });
        }
    });
}

function searchToAddUser(string, convId, userAdedToExclude)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/searchUserToAdd.php",
        data: {tosearch: string, convId:convId, userAdedToExclude:userAdedToExclude}
    }).done(function(msg){
        //effacer le contenu des resultats
        $('#chatBox-' + convId).find($('.add-user-result-container')).remove();
        //add results
        if(msg)
        {
            $('#chatBox-' + convId).find($('.add-user-results')).append(msg);
            imgSmoothLoading();
        }
    });
}

//update les messages des chat boxs au chargement de la page et toute les 10 sec pour checker si de nouvelles conv on été ouvertes
function updateConversationsChatBox()
{
    $.ajax({
        method: 'POST',
        url: "inc/Notifications/displayNotifs.php",
        data: {typeNotifs: 'messagesChatBoxes'},
        dataType: "json"
    }).done(function(msg){
        if(msg)
        {
            //affiche les messages des convs lor du chargement de la page
            $.each(msg, function(index, objResult){
                //assign vars for update messages loop
                var idConv = objResult.idConv;
                var dateLastMess = objResult.dateLastMess;
                var nbNotifsToAdd = objResult.nbNotifs;

                //display messages in right conv
                $.each(objResult.messages, function(index, messageHtml)
                {
                    $msgContainer = $('#chatBox-' + idConv).find($('.messages-box'));
                    //append elem with transform animation
                    $(messageHtml).appendTo($msgContainer).show('slow').find($('.user-message')).css('transform','scale(1)');
                    //scroll content to bottom automaticly
                    scrollConvToBottom($('#chatBox-' + idConv), 0);
                    //add notifs
                });
                addNotif($('#chatBox-' + idConv), nbNotifsToAdd);
                //lauch loop message update
                imgSmoothLoading();
            });
        }
    });
}

//update les messages d'une chatBox toute les x secondes (executée après updateConversationsChatBox
function updateConversationChatBox(idconv, dateLastMess) {
    $.ajax({
        method: 'POST',
        url: "inc/Notifications/displayNotifs.php",
        data: {typeNotifs: 'messagesChatBoxe', idconv: idconv, dateLastMess: dateLastMess},
        dataType: "json"
    }).done(function (msg) {
        if (msg)
        {
            //display messages
            var objResult = msg[0];
            var idConv =  objResult.idConv;
            var dateLastMess = objResult.dateLastMess;
            $.each(objResult.messages, function (index, messageHtml) {
                $msgContainer = $('#chatBox-' + idConv).find($('.messages-box'));
                //append elem with transform animation
                $(messageHtml).appendTo($msgContainer).show('slow').find($('.user-message')).css('transform', 'scale(1)');
                //scroll content to bottom automaticly
                $msgContainer.animate({scrollTop: $msgContainer[0].scrollHeight}, 0);
                //si l'element est le dernier du tableau alord display les vues
            });
            imgSmoothLoading();
        }
        //tt les 5 sec check si la conv est toujours open, si oui update les messages sinon ne rien faire
        //#todo comprendre prk il n'en update qu'une seule
        /*setTimeout(function(){
            //setInterval pour check si on dois update la conv ou non
            var timer = setInterval(function(){
                var stateConv = getStateConvDisplayed(idConv);
                //la conv est minimized ou ouverte, on actualise les messages
                if(stateConv == 'minimized' || stateConv == 'open')
                {
                    clearInterval(timer);
                    updateConversationChatBox(idConv, dateLastMess);
                }
                //si la conv est grouped alors on met en stand by
                if(stateConv == 'grouped')
                {
                    //ne rien faire, attendre que la conv repasse en open
                }
                //si la conv est fermée alors on desactive l'actualisation
                if(stateConv == 'closed')
                {
                    clearInterval(timer);
                }
            }, 2000);
        }, 2000)*/
    });
}

function getChatBoxe(idconv) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/getConv.php",
        data: {typeConv: 'ChatBox', idconv: idconv}
    }).done(function (msg) {
        restructureChatBoxes($(msg),'openOnCreate');
        updateConversationChatBox(idconv, null)
    });
}

function getStateConvDisplayed(idConv) {
    $conv = $('#chatBox-' + idConv);
    if($conv.length > 0)
    {
        return $conv.attr('data-state');
    }
    else{
        return 'closed';
    }
}

function checkNewConvChatBox() {
    $.ajax({
        method: 'POST',
        url: "inc/Notifications/displayNotifs.php",
        data: {typeNotifs: 'messagesChatBoxe', checknew:'checknew'},
        dataType: "json"
    }).done(function (msg) {
        if (msg)
        {
            //display messages
            var objResult = msg[0];
            var idConv =  objResult.idConv;
            var dateLastMess = objResult.dateLastMess;
            $.each(objResult.messages, function (index, messageHtml) {
                $msgContainer = $('#chatBox-' + idConv).find($('.messages-box'));
                //append elem with transform animation
                $(messageHtml).appendTo($msgContainer).show('slow').find($('.user-message')).css('transform', 'scale(1)');
                //scroll content to bottom automaticly
                $msgContainer.animate({scrollTop: $msgContainer[0].scrollHeight}, 0);
                //si l'element est le dernier du tableau alord display les vues
            });
            imgSmoothLoading();
            //tt les 5 sec reiterer cette fonction avec les nouveaux paramètres
        }
        //setTimeout(function(){ updateConversationChatBox(idConv, dateLastMess); }, 5000);
    });
}

//Add users to conv
function prepareAddUserToConv(idConv, idUsersToAdd, emptyConv)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/prepareAddUserToConv.php",
        data: {idConv: idConv, idUsersToAdd:idUsersToAdd},
        dataType: "json"
    }).done(function(msg){
        //msg tableau json
        //array([state][ 'convExist': la conv modifiée existe deja Et est affichée | 'newConvCreated': on a crée une nouvelle conv qui se trouve ds [conv] | 'todoAddUser' : la conv]
        //      [conv]
        if(msg != 'err')
        {
            //si la box d'ou on addUser est une emptyBox
            if(emptyConv)
            {
                restructureChatBoxes($('#chatBox-' + idConv), "close");
            }

            var idconv = msg.convid; //ici convid est l'id de la conv qui resulte de la fonction prepareAdduser, il peux etre le meme que l'id passé en paramètre (cas de convExist) ou etre different (cas de newConvCreated)
            if(msg.state == 'convExist')
            {
                //Afficher la conv
                var convHtml = $.parseHTML(msg.convHtml);
                restructureChatBoxes(convHtml, "open");
                //get state pour tester si l'on dois load les messages ou non
                var convState = $(msg.convHtml).attr('data-state');
                //si la conv n'est pas deja affichée on lunch l'actualisation des messages
                if(convState != 'open' && convState != 'minimized')
                {
                    //load conv messages, on load les derniers messages donc le parametre dateLastMess est a null
                    updateConversationChatBox(idconv, null);
                }
            }
            if(msg.state == 'newConvCreated')
            {
                //changer le data-state pour indiquer que la oonv est nouvelle
                //$($.parseHTML(msg.convHtml)).attr('data-state','newConvOnAdd');

                //Afficher la conversation nouvelement crée
                restructureChatBoxes($.parseHTML(msg.convHtml), "open", idConv);

                //load conv messages, on load les derniers messages donc le parametre dateLastMess est a null
                updateConversationChatBox(idconv, null);

                //ajouter les users a la conversation
                addUsersToConv(msg.convid, msg.toAdd);
            }
            if(msg.state == 'todoAddUser')
            {
                //modifier la conv existante
                $('#chatBox-' + idConv).find($(".messages-box")).append('<p data-elem="message-action">'+ msg.text +'</p>');

                //ajouter users to conv
                addUsersToConv(msg.convid, msg.toAdd);
            }

            //effacer le contenu des blocs
            removeSearchResultFromChatBox(idConv);
        }
    });
}

function addUsersToConv(idConv, idUsersToAdd)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/addUserToConv.php",
        data: {idConv: idConv, idUsersToAdd:idUsersToAdd},
        dataType: "json"
    }).done(function(msg) {
        if(msg != 'err')
        {
            var arrayId = idUsersToAdd.split(",");
            //si la conv est activée on affiche la conv chez les user nouvellements ajoutés
            if(msg.convActivated)
            {
                $.each(arrayId, function (index, iduser) {
                    socket.emit('newConvToDisplay', {
                        convid  : idConv,
                        userid  : iduser
                    });
                });
            }
            $('#chatBox-' + idConv).find($('.chat-box-title p')).html(msg.titleConv);
        }
        else{

        }
    });
}

//creer une chat box si une conver se crée vers nous
//php: passage de la chatBox en mode open
function openChatBoxOnUpdate(idconv)
{
    //creer une chaine a partir du tableau json json.messages
    $.ajax({
        method: 'POST',
        url: "inc/Notifications/displayNotifs.php",
        data: {typeNotifs: 'newConv', idconv:idconv}
    }).done(function(msg){
        if(msg != 'err')
        {
            //appel de la methode restructure chat boxes avec l'html
            $(msg).attr('data-create','just-created');
            restructureChatBoxes(msg, 'open', false);
        }
    });
}

//créé une chatBoxe lor du clique sur le logo message
function createChatBoxe()
{
    //creer une chaine a partir du tableau json json.messages
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/createConv.php",
        data: {},
        dataType: "json"
    }).done(function(msg){
        //action a realiser: RAPPEL: state est l'etat de la conversation de l'user qu'on contact (celui dont le profil est affiché) avant qu'on ne tente de le contacter
        if(msg.state == 'minimized')
        {
            restructureChatBoxes($('#chatBox-' + msg.id), 'open');
            return true;
        }
        if(msg.state == 'grouped')
        {
            restructureChatBoxes($('#chatBox-' + msg.id), 'open');
            return true;
        }
        if(msg.state == 'opened')
        {
            restructureChatBoxes($('#chatBox-' + msg.id), 'open');
            return true;
        }
        if(msg.state == 'deleted')
        {
            $chatBox = $($.parseHTML(msg.conv));
            $chatBox.attr('data-create','just-created');
            restructureChatBoxes($chatBox,'open');
            addUsersToConv(msg.id, msg.user);
            return false;
        }
        if(msg.state == 'created')
        {
            $chatBox = $($.parseHTML(msg.conv));
            $chatBox.attr('data-create','just-created');
            restructureChatBoxes($chatBox,'open');
            addUsersToConv(msg.id, msg.user);
            return false;
            //LOAD MESSAGES
            //si message: creer la convers
            //sinon ne rien faire
            //appel de la methode restructure chat boxes avec l'html
        }
    });
}

function createEmptyChatBox() {
    $.post("inc/MessageCenter/newConvEmpty.php", function(msg){
        //recuperation des variables
        var pieces =    msg.split('//');
        var idConv =    pieces[1];
        var state =     pieces[0];

        //action a realiser: RAPPEL: state est l'etat de la conversation de l'user qu'on contact (celui dont le profil est affiché) avant qu'on ne tente de le contacter
        if(state == 'minimized')
        {
            restructureChatBoxes($('#chatBox-' + idConv), 'open');
            return true;
        }
        if(state == 'grouped')
        {
            restructureChatBoxes($('#chatBox-' + idConv), 'open');
            return true;
        }
        if(state == 'opened')
        {
            restructureChatBoxes($('#chatBox-' + idConv), 'open');
            return true;
        }
        //state = non open, donc msg contient l'HTML la conversation a ajouter
        else
        {
            $chatBox = $($.parseHTML(msg));
            //$chatBox.attr('data-create','just-created');
            restructureChatBoxes($chatBox,'open');
            return true;
        }
    });
}

//Cette fonction est appelée lors: du changement d'etat d'une chatBox, de la creation de la nouvelle chatBox
//convHtml: html de la chat box sur laquelle on agit
//todoo: l'action a effectuer
function restructureChatBoxes(convHtml, todo, idConvInsertBefore)
{
    //si il y a une nouvelle chatBox a ajouter (cas de create chat box, ajout d'une nopuvelle conv)
    if(convHtml)
    {
        //init parameters
        var tmp = $(convHtml).attr('id');
        var pieces = tmp.split('-');
        var convId = pieces[1];

        var dataState = $(convHtml).attr('data-state');
        var dataCreate = $(convHtml).attr('data-create');

        //conv displayed
        $conv = $('#chatBox-' + convId);

        //cas du leave d'une conv grouped
        if(todo == 'leave')
        {
            $(convHtml).remove();
            moveFromGroupedToConv();
            return true;
        }
        //cas du delete conversation
        if(todo == 'delete')
        {
            $(convHtml).remove();
            moveFromGroupedToConv();
            changeChatState(convId, todo);
            return true;
        }

        //cas de la creation d'une conv lorsqu'un user vous contact
        /*/if(dataCreate == 'just-created')
        {
            if(todo == 'open')
            {
                //ici il n'y a aucun change state, car il a deja été efféctué lorsque l'user vous a contacté
                moveConvToOpenConvs($(convHtml), false, false);
            }
        }*/
        //si la conv etait minimized
        if (dataState == 'minimized')
        {
            if (todo == "open")
            {
                $conv.attr('data-state','open');
                changeChatState(convId, todo);
                focusInConv($conv);
                scrollConvToBottom($conv, 0);
                return true;
            }
            if (todo == "close")
            {
                $conv.remove();
                moveConvToOpenConvs($conv, false, true, false);
                changeChatState(convId, todo);
                return true;
            }
        }
        //si la conv fesait partie des conv grouped
        if (dataState == 'grouped')
        {
            if (todo == 'open')
            {
                $conv.attr('data-state','open');
                moveFromGroupedToConv($conv);
                changeChatState(convId, todo);
                focusInConv($conv);
                scrollConvToBottom($conv, 0);
                return true;
            }
            if (todo == 'close')
            {
                $conv.remove();
                changeChatState(convId, todo);
                return true;
            }
        }
        //si la conv etait open
        if (dataState == 'open')
        {
            if (todo == 'minimized')
            {
                $conv.attr('data-state', 'minimized');
                focusOutConv($conv);
                changeChatState(convId, todo);
                scrollConvToBottom($conv, 0);
                return true;
            }
            if (todo == "close")
            {
                $conv.remove();
                moveFromGroupedToConv();
                changeChatState(convId, todo);
                return true;
            }
            if(todo == 'open')
            {
                focusInConv($conv);
                scrollConvToBottom($conv, 0);
            }
        }
        //si la conv etait closed
        if (dataState == 'closed')
        {
            if(todo == 'open')
            {
                $(convHtml).attr('data-state','open');
                moveConvToOpenConvs($(convHtml), false, true, false);
                changeChatState(convId, todo);
                focusInConv($conv);
                return true;
            }
            if(todo == 'openOnCreate')
            {
                $(convHtml).attr('data-state','open');
                $(convHtml).attr('data-create','just-created');
                moveConvToOpenConvs($(convHtml), false, true, false);
                changeChatState(convId, 'open');
                focusInConv($conv);
                return true;
            }
        }
        //cas de la creation d'une nouvelle conv après addUser
        if (dataState == 'newConvOnAdd')
        {
            if(todo == 'open')
            {
                moveConvToOpenConvs($(convHtml), idConvInsertBefore, true, false);
                changeChatState(convId, 'open');
                focusInConv($conv);
                return true;
            }
        }
    }
}

function addNotif(elemConv, nbNotif) {
    nbNotif = parseInt(nbNotif);
    if(nbNotif != 0)
    {
        var dataState = elemConv.attr('data-state');

        var textOldNbNotifs = elemConv.find($('p[data-elem="notif-cntr"]')).text();
        var oldNbNotifs = 0;
        
        //si il y a des notifs, on met a jour le oldNbNotifs
        if(textOldNbNotifs != '')
        {
            oldNbNotifs = parseInt($(elemConv).find($('p[data-elem="notif-cntr"]')).text());
        }
        var newNbNotif = oldNbNotifs + nbNotif;

        //diffrents cas
        if(dataState == 'open')
        {
            //si la conv n'est pas focus
            if(!elemConv.find($('div[data-elem="write-box-chatbox"]')).is(':focus'))
            {
                //ajouter les notifs
                elemConv.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);

                //faire clignoter
                elemConv.attr('data-notif','notified');
            }
            //si la conv est focus
            else{

            }
        }
        if(dataState == 'minimized')
        {
            //ajouter les notifs
            elemConv.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);
        }
        if(dataState == 'grouped')
        {
            //ajouter ls notifs a la conv
            elemConv.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);

            //on met a jour le nb notif grouped si nescessaire
            addSubNotifsGrouped(elemConv, 'add');
        }
    }
}

function addSubNotifsGrouped(elemConv, action) {
    var textNbNotifToAddSub = elemConv.find($('p[data-elem="notif-cntr"]')).text();

    // on soustrait le nb de notifs de la conv au nb total de notif grouped
    //get le nb notif to add
    var nbNotifToAddSub = 0;
    if(textNbNotifToAddSub  != '')
    {
        nbNotifToAddSub = parseInt(elemConv.find($('p[data-elem="notif-cntr"]')).text());
    }

    //get le nb notifs grouped total
    //var definitions
    var textNbNotifGrouped = $('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text();
    var nbNotifGrouped = 0;
    var newNbNotifGrouped = 0;
    //si le nb de notif grouped total n'est pas nul
    if(textNbNotifGrouped != '')
    {
        nbNotifGrouped = parseInt($('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text());

        //en foncition de l'action
        if(action == 'sub')
        {
            newNbNotifGrouped = nbNotifGrouped - nbNotifToAddSub;
        }
        if(action == 'add')
        {
            newNbNotifGrouped = nbNotifGrouped + nbNotifToAddSub;
        }
    }
    //controles sur le nb de notif grouped total trouvé
    //si le nb de notif grouped total est nul, on vide le champ
    if(newNbNotifGrouped == 0)
    {
        $('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text('');
    }
    //sinon on met a jour le champ
    else{
        $('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text(newNbNotifGrouped);
    }
}

function resetNotifs(elemJquery) {
    elemJquery.find($('p[data-elem="notif-cntr"]')).text('');
}

function focusInConv(elemJquery) {
    elemJquery.trigger('click');
    resetNotifs(elemJquery);
}

function focusOutConv(elemJquery) {
    elemJquery.removeClass('focus');
    elemJquery.find($('div[data-elem="write-box-chatbox"]')).focusout();

}

function scrollConvToBottom(elemJquery, time) {
    $msgContainer = $(elemJquery).find($('.messages-box'));
    $msgContainer.animate({scrollTop: $msgContainer.prop('scrollHeight')}, time);
}
//fonction pour deplacer les conversations de grouped a curreznt ou inversement en fonction du paramètre elem
function moveFromGroupedToConv(elemJquery){
    var tmp;    //contiendra l'id de la conv dont on change l'etat
    var pieces; //contiendra le tableau avec en position 1 l'id de la conv
    var convId; //contient pieces[1]

    //cas ou il y a un element a deplacer de grouped vers current convs
    if(elemJquery)
    {
        //on deplace la dernière conv ouverte des current vers les conv grouped
        $elemToMoveFromConvToGrouped = $('.chat-boxes-container').find($('.chat-box')).first();
        $elemToMoveFromConvToGrouped.attr('data-state','grouped');
        $('.chat-boxes-grouped-container').append($elemToMoveFromConvToGrouped);
        //on met a jour le nb notif grouped si nescessaire
        addSubNotifsGrouped(elemJquery, 'add');

        //on place la conv grouped selectionnée ds les conv courantes
        $('.chat-boxes-container').append(elemJquery);
        elemJquery.attr('data-state','open').attr('data-open','just-opened');
        //on met a jour le nb notif grouped si nescessaire
        addSubNotifsGrouped(elemJquery, 'sub');

        //scroll le content en bas
        scrollConvToBottom(elemJquery, 0);

        //on change l'etat de la conv deplacées ds grouped
        tmp = $elemToMoveFromConvToGrouped.attr('id');
        pieces = tmp.split('-');
        convId = pieces[1];
        changeChatState(convId, 'grouped');

        //on change l'etat de la conv deplacée vers curent chat boxes
        tmp = elemJquery.attr('id');
        pieces = tmp.split('-');
        convId = pieces[1];
        changeChatState(convId, 'open');
        return true;
    }
    //cas ou on doit juste deplacer une conv grouped vers current conv (cas ou une conv open est delete)
    else{
        if($('.chat-boxes-grouped-container').find('.chat-box').length >= 1)
        {
            $elemToMoveFromGroupedToConv = $('.chat-boxes-grouped-container').find('.chat-box').last();
            $('.chat-boxes-container').append($elemToMoveFromGroupedToConv);
            $elemToMoveFromGroupedToConv.attr('data-state','open');
            //change conv state
            tmp = $elemToMoveFromGroupedToConv.attr('id');
            pieces = tmp.split('-');
            convId = pieces[1];
            changeChatState(convId, 'open');
            return true;
        }
    }
}

//ajouter une conv aux conv open, deplacer une conv open vers une conv grouped si le nb de conv ouverte est deja maximum
function moveConvToGroupedConv(elemJquery){
    var tmp;    //contiendra l'id de la conv dont on change l'etat
    var pieces; //contiendra le tableau avec en position 1 l'id de la conv
    var convId; //contient pieces[1]

    //cas ou il y a un element a deplacer de grouped vers current convs
    if(elemJquery)
    {
        //on deplace la dernière conv ouverte des current vers les conv grouped
        $elemToMoveFromConvToGrouped = $('.chat-boxes-container').find($('.chat-box')).first();
        $elemToMoveFromConvToGrouped.attr('data-state','grouped');
        $('.chat-boxes-grouped-container').append($elemToMoveFromConvToGrouped);
        //on met a jour le nb notif grouped si nescessaire
        addSubNotifsGrouped(elemJquery, 'add');

        //on place la conv grouped selectionnée ds les conv courantes
        $('.chat-boxes-container').append(elemJquery);
        elemJquery.attr('data-state','open').attr('data-open','just-opened');
        //on met a jour le nb notif grouped si nescessaire
        addSubNotifsGrouped(elemJquery, 'sub');

        //scroll le content en bas
        scrollConvToBottom(elemJquery, 0);

        //on change l'etat de la conv deplacées ds grouped
        tmp = $elemToMoveFromConvToGrouped.attr('id');
        pieces = tmp.split('-');
        convId = pieces[1];
        changeChatState(convId, 'grouped');

        //on change l'etat de la conv deplacée vers curent chat boxes
        tmp = elemJquery.attr('id');
        pieces = tmp.split('-');
        convId = pieces[1];
        changeChatState(convId, 'open');
        return true;
    }
    //cas ou on doit juste deplacer une conv grouped vers current conv (cas ou une conv open est delete)
    else{
        if($('.chat-boxes-grouped-container').find('.chat-box').length >= 1)
        {
            $elemToMoveFromGroupedToConv = $('.chat-boxes-grouped-container').find('.chat-box').last();
            $('.chat-boxes-container').append($elemToMoveFromGroupedToConv);
            $elemToMoveFromGroupedToConv.attr('data-state','open');
            //change conv state
            tmp = $elemToMoveFromGroupedToConv.attr('id');
            pieces = tmp.split('-');
            convId = pieces[1];
            changeChatState(convId, 'open');
            return true;
        }
    }
}

function moveConvToOpenConvs(elemJquery, idConvInsertBefore, changeState, justAdd){
    //cas ou on ajoute d'une conv grouped au conv ouvertes: on doit deplacer une conv vers les grouped si nescessaire
    //si c'est une simple action d'ajout
    if(justAdd)
    {
        //on move la conv de grouped vers les conv open
        $('.chat-boxes-container').append(elemJquery);
        //on met a jour le nb notif grouped si nescessaire
        addSubNotifsGrouped(elemJquery, 'sub');
    }
    //si l'action d'ajout implique des controles
    else{
        //on test si le nb de fenetres ouvertes est maximum avant de faire tt action
        //s'il l'est alors l'action implique de deplacer une conv ouverte vers les grouped
        if($('.chat-boxes-container').find($('.chat-box')).length >= limitBoxesOpen)
        {
            $elemToMoveToGrouped = $('.chat-boxes-container').find($('.chat-box')).last();
            var tmp =       $elemToMoveToGrouped.attr('id');
            var pieces =    tmp.split('-');
            var convId =    pieces[1];

            //move conv to grouped
            $('.chat-boxes-grouped-container').append($elemToMoveToGrouped);
            $elemToMoveToGrouped.attr('data-state','grouped');
            //on met a jour le nb notif grouped si nescessaire
            addSubNotifsGrouped($elemToMoveToGrouped, 'add');

            //move elemJquery to current chatBox container
            if(idConvInsertBefore)
            {
                elemJquery.insertBefore($('#chatBox-' + idConvInsertBefore));
            }
            else{
                $('.chat-boxes-container').append(elemJquery);
            }
            //on met a jour le nb notif grouped si nescessaire
            addSubNotifsGrouped(elemJquery, 'sub');

            //change conversations state
            if(changeState)
            {
                changeChatState(convId, 'grouped');
            }
        }
        // le nb de fenetres ouvertes n'est maximum, inutile de bouger unbe conv ouverte vers les grouped après l'action
        else{
            //on met a jour le nb notif grouped si nescessaire
            addSubNotifsGrouped(elemJquery, 'sub');
            //on move la conv de grouped vers les conv open
            $('.chat-boxes-container').append(elemJquery);
        }
    }
}

//fonction pour envoyer le message a la conversation
function sendMessage(texte, idconversation){
    //creer une chaine a partir du tableau json json.messages
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/sendMessage.php",
        data: {idconv: idconversation, texte: texte}
    }).done(function(msg){
        if(msg)
        {
            //displayMessage in conversation
        }
    });
}

//cette fonction sert a changer l'etat de la conversation
function changeChatState(chatId, state){
    var stateInt;
    if(state == 'close')
    {
        stateInt = 0;
    }
    if(state == 'open')
    {
        stateInt = 1;
    }
    if(state == 'minimized')
    {
        stateInt = 2;
    }
    if(state == 'grouped')
    {
        stateInt = 3;
    }
    if(state == 'delete')
    {
        stateInt = 4;
    }
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/changeStateChatbox.php",
        data: {idchat: chatId, action: stateInt}
    }).done(function(msg){
        if(msg == 'err')
        {
            //window.location.reload();
        }
    });
}

function displayUserAdedToConv(iduser, userNickname, jqueryChatBox){
    var htmlToAdd = '<div class="user-added-to-convers"><div class="user-nickname">"'+ userNickname +'"</div><div class="cross-cancel-user-add" data-action="cancel-user-add"><span>x</span></div></div>';
    jqueryChatBox.find($('div[data-elem="user-added-container"]')).append(htmlToAdd);
    $elem = jqueryChatBox.find($('div[data-elem="searh-user"]'));
    //placeCaretAtEnd($elem);
}

function prepareEditConv(idConv) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/prepareEditConv.php",
        data: {idconv: idConv}
    }).done(function (msg) {
        if (msg != 'err')
        {
            $('#chatBox-' + idConv).find('.edit-options').html(msg).addClass('active');
        }
    });
}

function removeSearchResultFromChatBox(idConv){
    $elem = $('#chatBox-' + idConv).find($('.add-user-container'));
    $elem.removeClass('active').find($('div[data-elem="searh-user"]')).html('');
    $elem.find($('div[data-elem="user-added-container"]')).html('')
}

function displayUsersToDelete(idConv) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/displayDelUserConv.php",
        data: {idconv: idConv}
    }).done(function (msg) {
        if (msg != 'err')
        {
            $('#chatBox-' + idConv).find('.user-delete-container').html(msg).addClass('active');
            imgSmoothLoading();
        }
        else{

        }
    });
}

function prepareDeleteUserFromConv(idconv, idusers) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/prepareDelUserFromConv.php",
        data: {idconv: idconv, idusers: idusers}
    }).done(function (title) {
        if (title != 'err')
        {
            $('#chatBox-' + idconv).find($('.chat-box-title p')).html(title);
            $('#chatBox-' + idconv).find($('.user-delete-container')).removeClass('active');
            setTimeout(function(){
                $('#chatBox-' + idconv).find($('.user-delete-container')).html('');
            },1000);
            imgSmoothLoading();
            deleteUsersFromConv(idconv, idusers);
        }
        else{

        }
    });
}

function deleteUsersFromConv(idconv, idusers) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/deleteUsersFromConv.php",
        data: {idconv: idconv, idusers: idusers}
    }).done(function (msg) {
        if (msg != 'err')
        {
            //
        }
        else{

        }
    });
}

function leaveConv(idConv) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/leaveConv.php",
        data: {idconv: idConv}
    }).done(function (msg) {
        if (msg != 'err')
        {
            restructureChatBoxes($('#chatBox-' + idConv), 'leave');
        }
        else{

        }
    });
}

function renameConv(idConv, newName) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/renameConv.php",
        data: {idconv: idConv, newName: newName}
    }).done(function (msg) {
        if (msg != 'err')
        {
            //
        }
        else{

        }
    });
}

function deleteConv(idConv) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/deleteConv.php",
        data: {idconv: idConv, newName: newName}
    }).done(function (msg) {
        if (msg != 'err')
        {
            //
        }
        else{

        }
    });
}

function writeMessage(iduser, idconv, message, typeMess) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/displayMessage.php",
        data: {iduser: iduser, idconv: idconv, message: message, typeMess:typeMess}
    }).done(function (msg) {
        if (msg != 'err')
        {
            //enreg mess in DB
            //enregMessage(idconv, message, msg);

            //add message with scle animation
            $msgContainer = $('#chatBox-' + idconv).find($('.messages-box'));
            $(msg).appendTo($msgContainer).show('slow').find($('.user-message')).css('transform','scale(1)');
            scrollConvToBottom($('#chatBox-' + idconv), 500);

            //images display
            imgSmoothLoading();

            //add notifs
            addNotif($('#chatBox-' + idconv), 1);

            //focus in conv to update consultedAt
            //focusInConv($('#chatBox-' + idconv));
        }
        else{
            window.location.reload();
        }
    });
}

//cette fonction enregistre le mesage ds la conv et active la conv pour tout les participants si elle n'est pas encore activée
//retourne l'id de la conv activée s'il y en a
function enregMessage(idconv, message) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/enregMessage.php",
        data: {idconv: idconv, message: message},
        dataType: "json"
    }).done(function (msg) {
        if (msg != 'err')
        {
            //si reponse alors la conv n'etait pas activée
            $.each(msg.users, function (index, iduser){
                socket.emit('newConvToDisplay', {
                    convid      : idconv,
                    touserid    : iduser,
                    fromuserid  : myid
                });
            });
        }
        else{
            //window.location.reload();
        }
    });
}

function openConv(iduser, convid){
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/openConv.php",
        data: {iduser: iduser, convid: convid},
        dataType: "json"
    }).done(function (msg) {
        if (msg != 'err') {

        }
        else {
            //window.location.reload();
        }
    });
}

function readConv(idconv){
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/updateReaded.php",
        data: {idConv: idconv}
    }).done(function(msg) {
        if(msg != 'err')
        {
            //
        }
        else{

        }
    });
}

function displayNbNotifsChatBoxes(){

}

function userIsConnected(iduser)
{
    //send message event
    socket.emit('isConnected', {
        userid  : myid
    });
}

//create chat box when click on message user logo
$(document).on("click", "a[data-elem='msg-user']", function(){
    createChatBoxe();
});

//create empty chat bow when click on new message
$('a[data-action="new-msg"]').click(function(){
    createEmptyChatBox();
});

//#todo factoriser avec des regew
$(document).on('click', "div[data-action='close-chatbox']", function(){
    $elem = $(this).closest($('.chat-box'));
    restructureChatBoxes($elem, 'close');
});

$(document).on('click', "div[data-action='minimize-box']", function(){
    $elem = $(this).closest($('.chat-box'));
    focusOutConv($elem);
    restructureChatBoxes($elem, 'minimized');
});

//change state convers: minimized -> open, when click on header
$(document).on('click', "div[data-state='minimized'] .header-chat-box", function(){
    $elem = $(this).closest($('.chat-box'));
    restructureChatBoxes($elem, 'open');
});

//change state convers: grouped -> open, when click on header
$(document).on('click', "div[data-state='grouped'] .header-chat-box", function () {
    $elem = $(this).closest($('.chat-box'));
    restructureChatBoxes($elem, 'open');
});

//open adduser input
$(document).on('click', "div[data-action='add-user-to-convers']", function () {
    $(this).closest($('.chat-box')).find($('.active')).removeClass('active');
    $(this).closest($('.chat-box')).find($('.add-user-container')).addClass('active');
});

//Add user to conv function
$(document).on("keyup","div[data-elem='searh-user']", function(){
    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convId = pieces[1];
    //extract stringSearch from contenteditable
    var textUserAlredayAdded = $(this).find($('.user-added-to-convers')).text();
    var tosearch = $(this).text().replace(textUserAlredayAdded, "");
    //get user en cours d'ajout pour ne plus les affihcer ds la recherche
    var userAdedToExclude = arrayIdAdded.join(',');
    //serach user
    if(tosearch == '')
    {
        $elem.find($('.add-user-result-container')).remove();
        return true;
    }
    else{
        if(tosearch.length <= 25)
        {
            //lunch research
            searchToAddUser(tosearch, convId, userAdedToExclude);
            return true;
        }
    }
});

//remove add friend input when click on writebox
/*$(document).on('focusin', '.write-box-container div[contenteditable="true"]', function(){
 $(this).closest($('.chat-box')).find($('.add-user-container')).removeClass('active');
 });*/

//delete just created state of conv and manage focus in conv to updatereadConv
$(document).on('click', '.chat-box', function(e){
    //put focus in write box if nescessary
    var validFocus = true;
    var validAddClass = true;

    //remove blinking state (just-created)
    $(this).removeAttr('data-create').removeAttr('data-open').removeAttr('data-notif');

    if($(e.target).attr('contenteditable') == 'true')
    {
        validFocus = false;
    }

    //si l'action est minimize, on n'ajoute pas la classe focus, laquelle est delete lor de l'action minimize box
    if($(e.target).parents($('.chat-bt')).attr('data-action') == 'minimize-box')
    {
        validAddClass = false;
    }

    if($(this).hasClass('focus'))
    {
        validAddClass = false;
    }

    if(validFocus)
    {
        //si la chat box n'est pas deja focus on focus //#todo fonctionne mal : clignotement, comprendre prk
        if(!$(this).find($('div[data-elem="write-box-chatbox"]')).is(':focus'))
        {
            $(this).find($('div[data-elem="write-box-chatbox"]')).focus();
        }
        //readConv
        var tmp = $(this).attr('id');
        var pieces = tmp.split('-');
        var idConv = pieces[1];
        readConv(idConv);

    }
    if(validAddClass)
    {
        $('.chat-box').removeClass('focus');
        $(this).addClass('focus');
    }
});

$(document).on('blur', '.chat-box', function(){
    $(this).closest($('.chat-box')).removeClass('focus');
});

//Add users to convers
var arrayIdAdded = [];
$(document).on('click', 'div[data-action="add-user-to-conv"]', function(){
    $chatBox = $(this).closest($('.chat-box'));

    //get parameters
    var idUser = $(this).attr('data-id');
    var userNickname = $(this).find($('.users-ids h4')).text();

    //effacer les resultats
    $chatBox.find($('.add-user-result-container')).remove();

    //effacer le champ input
    $chatBox.find($('div[data-elem="searh-user"]')).text('');

    //add userid to array of added user to conv (sert aux controle pour la recherche d'user a ajouter)
    arrayIdAdded.push(idUser);

    //prepare add
    displayUserAdedToConv(idUser,userNickname, $chatBox);
});

//valid add user to conv
$(document).on('click', 'button[data-action="valid-add-user"]', function() {
    $chatBox = $(this).closest($('.chat-box'));
    //si le tableau d'user a add est vide
    if (arrayIdAdded.length == 0)
    {
        $elem = $chatBox.find($('.add-user-container'));
        $elem.removeClass('active').find($('div[data-elem="searh-user"]')).html('');
        $elem.find($('div[data-elem="user-added-container"]')).html('');
        return false;
    }
    else{
        var emptyConv = false;
        var pieces = $chatBox.attr('id').split('-');
        var idConv = pieces[1];

        //definir si la conv d'ou on clique est une conv new ou pas
        if($chatBox.attr('empty-conv') == 'true')
        {
            emptyConv = true;
        }

        //implode userid array
        var strIdAded = arrayIdAdded.join(',');
        prepareAddUserToConv(idConv, strIdAded, emptyConv);

        //vider le tableau d'ids
        arrayIdAdded = [];
    }
});

//display options chatboxes
$(document).on('click', 'div[data-action="chatBox-options"]', function(){
    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convId = pieces[1];
    prepareEditConv(convId);
});

//remove contextuels menus
//#todo utiliser blur plutot
$(document).on('click', 'body', function (e) {
    $('.chat-box').find('.edit-options').removeClass('active').html('');
});

//delete user from conv
$(document).on('click', 'button[data-action="del-users-from-conv"]', function () {
    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convId = pieces[1];
    displayUsersToDelete(convId);
});

//fill array userid to delete
var arrayUserToDelete = [];
$(document).on('click', 'div[data-action="del-user-from-conv"]', function () {
    if($(this).hasClass('active'))
    {
        var idUserToRemove = $(this).attr('data-id');
        arrayUserToDelete = $.grep(arrayUserToDelete, function(value) {
            return value != idUserToRemove;
        });
        $(this).removeClass('active');
        return true;
    }
    else{
        var idUser = $(this).attr('data-id');
        arrayUserToDelete.push(idUser);
        $(this).addClass('active');
        return true;
    }
});

//valid user delete
$(document).on('click', 'button[data-action="valid-delete-user-conv"]', function (){
    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convId = pieces[1];
    prepareDeleteUserFromConv(convId, arrayUserToDelete.join(','));
    arrayUserToDelete = [];
});

//leave conv
$(document).on('click', 'button[data-action="leave-conv"]', function () {
    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convId = pieces[1];
    leaveConv(convId);
});

//display rename conv
$(document).on('click', 'button[data-action="rename-conv"]', function () {
    $elem = $(this).closest($('.chat-box'));
    var nameConv = $elem.find($('.chat-box-title p')).text();
    $elem.find($('div[data-elem="rename-conv-input"]')).html(nameConv);
    $elem.find($('.nameconv-container')).addClass('active');
});

//rename conv
$(document).on('click', 'button[data-action="valid-name-conv"]', function () {
    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convId = pieces[1];

    var newName = $elem.find($('div[data-elem="rename-conv-input"]')).text();

    if($.trim(newName) == '')
    {
        $elem.find($('.nameconv-container')).removeClass('active');
        return false;
    }

    //appliquer la modif instatanement
    $elem.find($('.chat-box-title p')).html(newName);

    //erase field content
    $elem.find($('div[data-elem="rename-conv-input"]')).html('');

    //cacher le champ
    $elem.find($('.nameconv-container')).removeClass('active');
    renameConv(convId, $.trim(newName));
});

//delete conv
$(document).on('click', 'button[data-action="delete-conv"]', function () {
    $elem = $(this).closest($('.chat-box'));
    restructureChatBoxes($elem, 'delete');

    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convId = pieces[1];
    deleteConv(convId);
});

//send message when click on ENTER
$(document).on('keypress', 'div[data-elem="write-box-chatbox"]', function(e) {
    //push ENTER
    if(e.which == 13)
    {
        if($(this).is('[placeholder]'))
        {
            //#todo add this test for share input too
            //remplacer le contenu vide du content editable par des ''
            //$valinput = $(this).html().replace(/&nbsp;|<br>/g,'');
            $valinput = $(this).html().replace(/<\/?(?!img)(?!br)(?!a)[a-z]+(?=[\s>])(?:[^>=]|=(?:'[^']*'|"[^"]*"|[^'"\s]*))*\s?\/?>/gi, '');

            //define if the post that your commenting is just shared (= you send comment from a post that u share with the share function without reloading page) or not, default: false
            if($valinput != 0 )
            {
                //get convid
                $elem = $(this).closest($('.chat-box'));
                var tmp = $elem.attr('id');
                var pieces = tmp.split('-');
                var convId = pieces[1];

                //display message and write in db
                //writeMessage(convId, $valinput, 'chatBox');
                enregMessage(convId, $valinput);
                $(this).html('');

                //send message event
                socket.emit('newMessage', {
                    message : $valinput,
                    convid  : convId,
                    userid  : myid
                });
            }
        }
        e.stopPropagation();
    }
    return e.which != 13;
});

//node.js part
$(function () {
    socket.on('newMessage', function (messageDetails){
        //add test eventuels
        writeMessage(messageDetails.userid, messageDetails.convid, messageDetails.message, 'chatBox');
    });

    socket.on('newConvToDisplay', function (ObjResult){
        //si oui on get chatBox
        getChatBoxe(ObjResult.convid);
    });

    socket.on('newConvToCreate', function (ObjResult){
        //si oui on get chatBox
        openConv(ObjResult.touserid, ObjResult.convid);
    });
});