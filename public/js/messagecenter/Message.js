//VARS
var limitBoxesOpen = 4;
var socket =    '';
var myid =      $('body').attr('data-usr');
var convAssocUser = {};
var convNameAssoc = [];
var pageName = $('body').attr('id');
var isMobile = isMobile();

//au chargement de la page charger les conv ouvertes
$(function()
{
    socket = io.connect('http://192.168.0.16:1337');
    socket.emit('login', {userid : myid});
    if(pageName == 'MessageCenter')
    {
        dispayLastDiscussion();
    }
    else{
        dispayConversations();
    }
    
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
                //move conv to open convs
                moveConvToOpenConvs($(valeur), false, false, true);
                //get convid
                var tmp = $(valeur).attr('id');
                var pieces = tmp.split('-');
                var convId = pieces[1];
                //load last messages
                updateConversationChatBox(convId, null, false)
            });

            $.each(msg.groupedConversations, function(cle, valeur)
            {
                moveConvToGroupedConv($(valeur), false);
                //get convid
                var tmp = $(valeur).attr('id');
                var pieces = tmp.split('-');
                var convId = pieces[1];
                //load last messages
                updateConversationChatBox(convId, null, false)
            });
        }
    });
}

function searchToAddUser(string, convId, userAdedToExclude, from)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/searchMessagerie.php",
        data: {tosearch: string, convId:convId, userAdedToExclude:userAdedToExclude, from:from}
    }).done(function(msg){
        //add results
        if(msg)
        {
            if(from == "MessageCenter")
            {
                $elem = $('div[data-elem="discussion-content"]');
            }
            else{
                $elem = $('#chatBox-' + convId);
            }
            //effacer le contenu des resultats
            $elem.find($('.add-user-result-container')).remove();
            $elem.find($('.add-user-results')).append(msg);
            imgSmoothLoading();
        }
    });
}

//update les messages d'une chatBox, n'est executé qu'une fois pour chaque convs, lor de leur chargement
function updateConversationChatBox(idconv, dateLastMess, generateNotifConv) {
    $.ajax({
        method: 'POST',
        url: "inc/Notifications/displayNotifs.php",
        data: {typeNotifs: 'messagesChatBoxe', idconv: idconv, dateLastMess: dateLastMess},
        dataType: "json"
    }).done(function (msg) {
        if (msg)
        {
            //get vars to display
            var idConv = msg.idConv;
            var dateLastMess = msg.dateLastMess;
            var nbNotifsToAdd = msg.nbNotifs;
            var lastMessageAuthorId = $(msg.messages[msg.messages.length - 1]).attr('data-u');
            $conv = $('#chatBox-' + idconv);
            $msgContainer = $conv.find($('.messages-box'));

            //update convs
            $.each(msg.messages, function (index, messageHtml)
            {
                //si le message est le premier et qu'il n'est pas possible de definir l'auteur du précédent mess
                if(index == 0)
                {
                    addMessageInFront($msgContainer, messageHtml, false);
                }
                //si le message n'est pas le premier et qu'il est possible de definir l'auteur du precédent
                else
                {
                    addMessageInFront($msgContainer, messageHtml, $(msg.messages[index - 1]));
                }
            });

            //scroll content to bottom automaticly
            scrollConvToBottom($conv, 0);

            //add notif to chatBox
            addNotif($('#chatBox-' + idConv), nbNotifsToAdd);

            //addNotif to conv notifs
            if(generateNotifConv)
            {
                getConvNotif(idConv, lastMessageAuthorId);
            }

            //ici on ajoute pas les notifs au convNotifs car la fonction se charge juste après le chargement de la page php, les notifs conv sont donc deja toute chargée par php et a jour donc
            imgSmoothLoading();
        }
    });
}

//functions to display Elem Message
function addMessageInFront(messageContainer, messageHtml, previousMessage){
    //si le message n'est pas le premier et qu'il est possible de definir l'auteur du precédent
    if(previousMessage)
    {
        var idPrevMessAuth =    previousMessage.attr('data-u');
        var idCurMessAuth =     $(messageHtml).attr('data-u');
        //si l'id auth du message précédent est le meme
        if(idPrevMessAuth == idCurMessAuth)
        {
            //append elem with transform animation, and nopic class
            $(messageHtml).appendTo(messageContainer).addClass('nopic').show('slow').find($('.user-message')).css('transform', 'scale(1)');
        }
        //si le message précédent est d'un auteur différent, rajouter une marge top, changer la classe qui affiche les infos
        else{
            //append elem with transform animation, and firstpic class
            $(messageHtml).appendTo(messageContainer).addClass('firstpic').show('slow').find('.message-content').find($('.user-message')).css('transform', 'scale(1)');
        }
    }
    //si le message est le premier du tableau et qu'il n'est pas possible de definir l'auteur du précédent mess
    else{
        $(messageHtml).appendTo(messageContainer).addClass('firstpic').show('slow').find($('.user-message')).css('transform', 'scale(1)');
    }
}

function addMessageInBack(messageContainer, messageHtml, prevMessage){
    //prev message est le precedent message ds l'ordre chronologique, donc l'emeent qui le suis ds le tableau
    //ici prec et next sont par rapport a la la date du message courant: next, plus récent, prev: plus ancien
    $nextMessage =          messageContainer.find($('div[data-elem="message"]')).first(); //next message est le plus ancien message chargé de la conv avant chargement ds nouveaux messages
    var idNextMessAuth =    $nextMessage.attr('data-u');
    var idCurMessAuth =     $(messageHtml).attr('data-u');

    if(prevMessage)
    {
        var idPrevMessAuth =    prevMessage.attr('data-u');
        var displayPic = true;
        //si l'id auth du message precedent est le meme
        if(idPrevMessAuth == idCurMessAuth)
        {
            //on ajoute nopic au courant
            displayPic = false;
        }

        //si le suivant a un auteur different
        if (idNextMessAuth == idCurMessAuth)
        {
            $nextMessage.removeClass('firstpic').addClass('nopic');
        }
        else{
            $nextMessage.removeClass('nopic').addClass('firstpic');
        }

        //prepend message
        if(displayPic)
        {
            $(messageHtml).prependTo(messageContainer).addClass('firstpic');
        }
        else{
            $(messageHtml).prependTo(messageContainer).addClass('nopic');
        }
    }
    else
    {
        if (idNextMessAuth == idCurMessAuth)
        {
            $nextMessage.removeClass('firstpic').addClass('nopic');
        }
        else{
            $nextMessage.removeClass('nopic').addClass('firstpic');
        }

        //add message with firstpic
        $(messageHtml).removeClass('nopic').addClass('firstpic').prependTo($messageBox);
    }
}

//get une conversation chatBoxe dont l'etat est a fermé, donc non affichée a l'ecran
//ici to do represente l'actin a realiser: deux possibles:
// 'openOncreate': open avecun clignotement piour indiquer que la conv s'est ouverte sur une action d'un autre user (ex activation, ajout de l'user courant a la conv)
// 'open': l'user courant veux juste ouvrir la conv
function getConv(idconv, typeResult, generateConvNotif, todo) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/getConv.php",
        data: {typeResult: typeResult, idconv: idconv, generateConvNotif:generateConvNotif},
        dataType: "json"
    }).done(function (msg)
    {
        //if conv ask come from message center
        if(typeResult == 'discussion')
        {
            loadDiscussion(idconv, msg.convHtml);
        }

        //if conv ask come from after activated conv node event /
        if(typeResult == 'notif/apDiscu')
        {
            //si la current conv est affichée on ne fait rien
            //sinon
            if($('div[data-elem="discussion-content"]').attr('data-aconv') != idconv)
            {
                //on charge la notif
                addConvNotifHtml($(msg.notifHtml));
                //MAJ de l'apercu discussion et placement au top de la liste
                $('.discussion[data-aconv="'+ idconv +'"]').remove();
                $(msg.apDiscuHtml).insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
            }
        }

        //if conv ask come from an update conv with notif value, and the ap conv is not loaded
        if(typeResult == 'apercuDiscutions')
        {
            //MAJ de l'apercu discussion et placement au top de la liste
            $('.discussion[data-aconv="'+ idconv +'"]').remove();
            $(msg.apDiscuHtml).insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
        }

        //if conv ask concerning a chatBox
        if(typeResult == 'chatBox')
        {
            restructureChatBoxes($(msg.convHtml), todo);
            updateConversationChatBox(idconv, null, generateConvNotif);
        }
    });
}

//Add users to conv
function prepareAddUserToConv(idConv, idUsersToAdd, emptyConv, from)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/prepareAddUserToConv.php",
        data: {idConv: idConv, idUsersToAdd:idUsersToAdd, from:from},
        dataType: "json"
    }).done(function(msg){
        //msg tableau json
        //array([state][ 'convExist': la conv modifiée existe deja Et est affichée | 'newConvCreated': on a crée une nouvelle conv qui se trouve ds [conv] | 'todoAddUser' : la conv]
        //      [conv]
        if(msg != 'err')
        {
            //define is conv is loaded in message center or not
            if(from != "MessageCenter")
            {
                //si la box d'ou on addUser est une emptyBox
                if(emptyConv)
                {
                    restructureChatBoxes($('#chatBox-' + idConv), "close");
                }
            }
            
            var idconv = msg.convid; //ici convid est l'id de la conv qui resulte de la fonction prepareAdduser, il peux etre le meme que l'id passé en paramètre (cas de convExist) ou etre different (cas de newConvCreated)
            if(msg.state == 'convExist')
            {
                if(from == "MessageCenter")
                {
                    loadDiscussion(msg.convid, msg.convHtml);
                }
                else{
                    //Afficher la conv
                    var convHtml = $.parseHTML(msg.convHtml);
                    restructureChatBoxes(convHtml, "open");
                    //get state pour tester si l'on dois load les messages ou non
                    var convState = $(msg.convHtml).attr('data-state');
                    //si la conv n'est pas deja affichée on lunch l'actualisation des messages
                    if(convState != 'open' && convState != 'minimized')
                    {
                        //load conv messages, on load les derniers messages donc le parametre dateLastMess est a null
                        updateConversationChatBox(idconv, null, false);
                    }
                }

            }
            if(msg.state == 'newConvCreated')
            {
                if(from == "MessageCenter")
                {
                    loadDiscussion(msg.convid, msg.convHtml);
                }
                else{
                    //changer le data-state pour indiquer que la oonv est nouvelle
                    //$($.parseHTML(msg.convHtml)).attr('data-state','newConvOnAdd');

                    //Afficher la conversation nouvelement crée
                    restructureChatBoxes($.parseHTML(msg.convHtml), "open", idConv);

                    //load conv messages, on load les derniers messages donc le parametre dateLastMess est a null
                    updateConversationChatBox(idconv, null, false);
                }
                //ajouter les users a la conversation
                addUsersToConv(msg.convid, msg.toAdd, from);
            }
            if(msg.state == 'todoAddUser')
            {
                if(from == "MessageCenter")
                {
                    //modifier la conv existante
                    $('div[data-elem="discussion-content"]').find('div[data-elem="messages-container"]').append('<p data-elem="message-action">'+ msg.text +'</p>');;
                }
                else{
                    //modifier la conv existante
                    $('#chatBox-' + idConv).find($(".messages-box")).append('<p data-elem="message-action">'+ msg.text +'</p>');
                }
                //ajouter users to conv
                addUsersToConv(msg.convid, msg.toAdd, from);
            }

            //effacer le contenu des blocs
            removeSearchResultFromChatBox(idConv);
        }
    });
}

function addUsersToConv(idConv, idUsersToAdd, from)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/addUserToConv.php",
        data: {idConv: idConv, idUsersToAdd:idUsersToAdd, from:from},
        dataType: "json"
    }).done(function(msg) {
        if(msg != 'err')
        {
            var arrayId = idUsersToAdd.split(",");
            //si la conv est activée on affiche la conv chez les user nouvellements ajoutés
            if(msg.convActivated)
            {
                $.each(arrayId, function (index, iduser) {
                    socket.emit('newConvToDisplayOnAddUser', {
                        convid  : idConv,
                        userid  : iduser
                    });
                });
            }

            //change conv name
            if(pageName == "MessageCenter")
            {
                $elem = $('div[data-elem="discussion-content"]');
                //load apercu conv
                addApercuDiscussion(idConv, msg.apConvHtml);
                //add title and pic user to conv
                $elem.find($('p[data-elem="conv-title"]')).html(msg.titleConv);
                $elem.find($('.conv-pic-container')).html(msg.picUsers);
            }
            else{
                $elem = $('#chatBox-' + idConv);
                $elem.find($('.chat-box-title p')).html(msg.titleConv);
            }
            imgSmoothLoading();
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
        if(msg.state == 'closed')
        {
            $chatBox = $($.parseHTML(msg.conv));
            restructureChatBoxes($chatBox, 'open');
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
    //si il y a une nouvelle chatBox a ajouter (cas de create chat box, ajout d'une nouvelle conv)
    if(convHtml)
    {
        //init parameters
        var tmp = $(convHtml).attr('id');
        var pieces = tmp.split('-');
        var convId = pieces[1];

        var dataState = $(convHtml).attr('data-state');
        var dataCreate = $(convHtml).attr('data-create');
        var dataEmpty = $(convHtml).attr('empty-conv');

        //conv displayed
        $conv = $('#chatBox-' + convId);

        //cas du kick d'une conv
        if(todo == 'leaveAfterKick')
        {
            $(convHtml).remove();
            moveFromGroupedToConv();
            removeNotifConv(convId);
        }

        //cas du leave d'une conv de groupe
        if(todo == 'leave')
        {
            $(convHtml).remove();
            moveFromGroupedToConv();
            removeNotifConv(convId);
            return true;
        }
        //cas du delete d'une conversation de groupe
        if(todo == 'delete')
        {
            $(convHtml).remove();
            moveFromGroupedToConv();
            changeChatState(convId, todo);
            removeNotifConv(convId);
            return true;
        }
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
                moveFromGroupedToConv();
                changeChatState(convId, todo);
                resetCheckedConvNotif(convId);
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
                resetCheckedConvNotif(convId);
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
                resetCheckedConvNotif(convId);
                return true;
            }
            if(todo == 'open')
            {
                focusInConv($conv);
                scrollConvToBottom($conv, 0);
            }
            if(todo == 'openAfterSelected')
            {
                focusInConv($(convHtml));
                return true;
            }
        }
        //si la conv etait closed
        if (dataState == 'closed')
        {
            if(todo == 'open')
            {
                $(convHtml).attr('data-state','open');
                updateConversationChatBox(convId, null, false);
                moveConvToOpenConvs($(convHtml), false, true, false);
                changeChatState(convId, todo);
                focusInConv($(convHtml));
                return true;
            }
            if(todo == 'openOnCreate')
            {
                $(convHtml).attr('data-state','open');
                $(convHtml).attr('data-create','just-created');
                moveConvToOpenConvs($(convHtml), false, true, false);
                changeChatState(convId, 'open');
                //focusInConv($(convHtml));
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
                focusInConv($(convHtml));
                return true;
            }
        }
    }
}

//add la notif a une conv chatBox
function addNotif(elemConv, nbNotif){
    nbNotif = parseInt(nbNotif);
    if(nbNotif != 0)
    {
        var dataState = elemConv.attr('data-state');

        var textOldNbNotifs = elemConv.find($('p[data-elem="notif-cntr"]')).text();
        var oldNbNotifs = 0;

        //si il y a des notifs, on met a jour le oldNbNotifs
        if (textOldNbNotifs != '') {
            oldNbNotifs = parseInt($(elemConv).find($('p[data-elem="notif-cntr"]')).text());
        }
        var newNbNotif = oldNbNotifs + nbNotif;


        //diffrents cas
        if (dataState == 'open') {
            //si la conv n'est pas focus
            if (!elemConv.find($('div[data-elem="write-box-chatbox"]')).is(':focus')) {
                //ajouter les notifs a la chatBoxe
                elemConv.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);

                //faire clignoter
                elemConv.attr('data-notif', 'notified');
            }
            //si la conv est focus ne rien faire
        }
        if (dataState == 'minimized') {
            //ajouter les notifs
            elemConv.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);
        }
        if (dataState == 'grouped') {
            //ajouter ls notifs a la conv
            elemConv.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);

            //on met a jour le nb notif grouped si nescessaire
            addSubNotifsGrouped(elemConv, 'add', nbNotif);
        }
    }
}

//met a jour le nb de notifs total grouped
function addSubNotifsGrouped(elemConv, action, toAdd) {
    //assigne depuis toAdd ou determine le nb de notifs a ajouter
    var nbNotifToAddSub = 0;
    if(toAdd)
    {
        nbNotifToAddSub = toAdd;
    }
    else{
        var textNbNotifToAddSub = elemConv.find($('p[data-elem="notif-cntr"]')).text();
        if(textNbNotifToAddSub  != '')
        {
            nbNotifToAddSub = parseInt(elemConv.find($('p[data-elem="notif-cntr"]')).text());
        }
    }
    //si le nb de notifs a ajouter est superieur a 0 alors on effecture les calculs
    if(nbNotifToAddSub > 0)
    {
        //get le text nb notifs grouped total pour controles
        var textNbNotifGrouped = $('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text();
        //var definitions
        var newNbNotifGrouped = 0;
        //si text nbnotif grouped total est vide, alor on l'initialise a 0
        if(textNbNotifGrouped == '')
        {
            textNbNotifGrouped = 0;
        }
        var nbNotifGrouped = parseInt(textNbNotifGrouped);
        //en fonction de l'action
        if(action == 'sub')
        {
            newNbNotifGrouped = nbNotifGrouped - nbNotifToAddSub;
            //si le nb de notif grouped total est nul, on vide le champ
            if(newNbNotifGrouped == 0)
            {
                $('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text('');
            }
            else{
                $('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text(newNbNotifGrouped);
            }
        }
        if(action == 'add')
        {
            newNbNotifGrouped = nbNotifGrouped + nbNotifToAddSub;
            $('.chat-box-grouped-bt').find($('p[data-elem="nbNotifGrouped"]')).text(newNbNotifGrouped);
        }
    }
}

//cette fonction sert a mettre a jour les infos de la notif conv en cas de nouveau message
//elle ajoute au compteur des notifs conv chaque notif message de la conversation
//si la conv concernée est open et séléctionnée alors
function updateConvNotifWithValue(convId, messageContent, messageDate, authNick, nbNotifToAdd, resetChecked) {
    //vars
    nbNotifToAdd = parseInt(nbNotifToAdd);
    //var date = getdateFromSqlDate(messageDate, 'H:m');
    //add notif to conv under menu
    var oldNbNotif = 0;
    $elemConvToNotif = $('div[data-chatNotif="'+ convId +'"]');

    //add notif to conv under menu
    if($elemConvToNotif.find($('p[data-elem="notif-cntr"]')).text() != '')
    {
        oldNbNotif = parseInt($elemConvToNotif.find($('p[data-elem="notif-cntr"]')).text());
    }
    var newNbNotif = oldNbNotif + nbNotifToAdd;
    if(newNbNotif > 0)
    {
        $elemConvToNotif.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);
    }

    //change notif infos
    $elemConvToNotif.find($('p[data-elem="date-lst-mess"]')).text(messageDate);
    $elemConvToNotif.find($('p[data-elem="message"]')).text(messageContent);
    //#todo put authNick somewhere
    //$elemConvToNotif.find($('p[data-elem="u-pic"]')).html(imageAuth);

    //add notifs to under menu
    //si le paramètre reset checked est a true alors on retire l'etat checked de la notif
    if(resetChecked)
    {
        $elemConvToNotif.attr('data-checked','false');
        updateNbNotifConfTot(nbNotifToAdd, 'add');
    }
}

//cette fonction ajoute la notif html aux convNotifs et recalcul le nb maximal de notif um
//cette fonction s'execute lorsque un message d'une conv closed est recéptionné par le client, lorsque la notiçfCOnv n'existe pas
function addConvNotifHtml(convNotifJquery) {
    //delete "empty conv notif" message
    $('#user-item-message').find($('div[data-elem="empty-notif"]')).remove();
    //add notif to conv under menu
    $('#user-item-message').find($('.item-container')).append(convNotifJquery);
    //update le nb de notif total um
    var nbNotif = parseInt(convNotifJquery.find($('p[data-elem="notif-cntr"]')).text());
    updateNbNotifConfTot(nbNotif, 'add');
}

//cette fonction sert a update la notif conv et mettre a jour le nbmax de notifs um
//cette fonction est utilisée lor de la reception d'un message d'une conv closed, lorsque la notifConv existe deja
function updateConvNotifWithHtml(convId, newConvNotifHtml){
    //decheck la conNotif et remplace son html par celui mis a jour
    $('div[data-chatNotif="'+ convId +'"]').attr('data-checked','false').html(newConvNotifHtml);
    //cette fonction etant appelé a chaque update de la convNotif par la reception d'un message donc le nbNotif a ajouter au nbTotal de notif tjr egal a 1
    //update le nb de notif total um
    var nbNotif = 1;
    updateNbNotifConfTot(nbNotif, 'add');
}

//cette fonction update l'ap conv et le met au top de la liste, si l'ap conv n'existe pas on la get
function updateApConv(convId, messageContent, messageDate, authNick, nbNotifToAdd, resetChecked) {
    $apDiscussion = $('.discussion[data-conv="'+ convId +'"]');
    var apConvIsLoaded = true;
    //define if apercu conv is loaded on the sideBar
    if($apDiscussion.length < 1)
    {
        apConvIsLoaded = false;
    }

    //function off ap conv state (loaded or not)
    if(apConvIsLoaded)
    {
        //MAJ & PUT ON TOP
        //add notif to conv under menu
        if($apDiscussion.find($('p[data-elem="notif-cntr"]')).text() != '')
        {
            var oldNbNotif = parseInt($apDiscussion.find($('p[data-elem="notif-cntr"]')).text());
        }
        var newNbNotif = oldNbNotif + nbNotifToAdd;
        if(newNbNotif > 0)
        {
            $apDiscussion.find($('p[data-elem="notif-cntr"]')).text(newNbNotif);
        }

        //change notif infos
        $apDiscussion.find($('p[data-elem="date-lst-mess"]')).text(messageDate);
        $apDiscussion.find($('p[data-elem="message"]')).text(messageContent);

        //put conv on top
        $apDiscussion.insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));

        //si le paramètre reset checked est a true alors on retire l'etat checked de la notif
        if(resetChecked)
        {
            $apDiscussion.attr('data-checked','false');
        }
    }
    else{
        //get conv app, put on top
        getConv(convId, 'apercuDiscutions', false, '');
    }
}

function updateApConvWithHtml(convid, newApConvJquery) {
    $apDiscussion = $('.discussion[data-conv="'+ convid +'"]');
    //if conv is loaded, check ap conv
    if($('div[data-elem="discussion-content"]').attr('data-conv') == convid)
    {
        if($apDiscussion.length > 0)
        {
            //check l'ap conv afin que les notifs ne soient plus comptabilisée
            $apDiscussion.attr('data-checked','true');
            //put ap conv at top
            $apDiscussion.insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
            //update appconv
            $apDiscussion.html(newApConvJquery.html())
        }
        else{
            newApConvJquery.insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
        }
        consultConvNotif(convid);
    }
    //else uncheck apConv
    else{
        if($apDiscussion.length > 0)
        {
            //put ap conv at top
            $apDiscussion.insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
            //update appconv
            $apDiscussion.html(newApConvJquery.html())
        }
        else{
            newApConvJquery.insertAfter($('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0));
        }
    }
}

//cette fonction met la notif a l'etat consulté
//met a jour le nb total de notifs en y soustrayant le nb de notif de la conv que l'on consult
function consultConvNotif(idconv){
    $convNotif = $('div[data-chatNotif="'+ idconv +'"]');
    //on check isi la chatNotif existe pour eviter tt erreur de calcul !important
    if($convNotif.length > 0)
    {
        //si la conversation est checkée cela veux dire qu'elle deja été consulté et on ne met pas a jour le nb de notifs total
        if($convNotif.attr('data-checked') != 'checked')
        {
            $convNotif.attr('data-checked','checked');

            //remove notifs to under menu
            var nbNotifToSub = 0;
            if($convNotif.find($('p[data-elem="notif-cntr"]')).text() != '')
            {
                nbNotifToSub = parseInt($convNotif.find($('p[data-elem="notif-cntr"]')).text());
            }

            updateNbNotifConfTot(nbNotifToSub, 'sub');

            //on remet a 0 le nb de notif de la notifConv
            $convNotif.find($('p[data-elem="notif-cntr"]')).text('');
        }
    }
}

function consultApConv(convid){
    $apConv = $('.discussion[data-conv="'+ convid +'"]');
    //on check isi la chatNotif existe pour eviter tt erreur de calcul !important
    if($apConv.length > 0)
    {
        //si la conversation est checkée cela veux dire qu'elle deja été consulté et on ne met pas a jour le nb de notifs total
        if($apConv.attr('data-checked') != 'checked')
        {
            //put con on chacked state (to avoid notif to be displayed)
            $apConv.attr('data-checked','checked');

            //on remet a 0 le nb de notif de la notifConv
            $apConv.find($('p[data-elem="notif-cntr"]')).text('');

            //put notif on checked to display quoto logo
            $apConv.find($('.nb-notifs')).attr('data-checked','true');
        }
    }
}

//cette fonction reset l'etat checked de la conv et remet a jour le nb de convNotif total
//s'utilise lor de la fermeture de conv chtBoxes, ds le cas ou la conv aurai son equivalent en convNotif
//si la conv que l'on reset est deja checkée alors on ne met pas a jour le nb de notifs total
function resetCheckedConvNotif(idconv) {
    $elemNotifConv = $('div[data-chatNotif="'+ idconv +'"]');
    $elemNotifConv.attr('data-checked','false');
    var nbNotifToAdd = 0;
    if($elemNotifConv.find($('p[data-elem="notif-cntr"]')).text() != '')
    {
        nbNotifToAdd = parseInt($elemNotifConv.find($('p[data-elem="notif-cntr"]')).text());
    }
    updateNbNotifConfTot(nbNotifToAdd, 'add');
}

//remove notif conv dsl e cas ou l'user leave une conversation par exemple
function removeNotifConv(idconv) {
    $('div[data-chatNotif="'+ idconv +'"]').remove();
}

function actionNotif(args, actionName) {
    //define vars
    var convid = args['convid'];

    //mess center
    $convLoaded         = $('div[data-elem="discussion-content"]');
    var convIsLoaded    = true;

    //chatBoxes
    $convChatBox        = $('#chatBox-' + convid);
    var chatBoxExist    = true;
    var convIsFocus     = true;

    //globals
    $convNotif          = $('div[data-chatNotif="'+ convid +'"]');
    var convNotifExist  = true;
    var messageContent =  ''; //new message content to display in notif when we update it
    var messageDate =     ''; //new mesage date
    var authNick =        ''; //nickname new message
    var iduser =          ''; //iduser ne message, usefull to test if the mess come from current user himself or not
    var nbNotif =         ''; //nb new messages to read;

    //args
    if(actionName == "newMessage")
    {
        messageContent =    args['messageContent'];
        messageDate =       args['messageDate'];
        authNick =          args['authNick'];
        iduser =            args['iduser'];
        nbNotif =           args['nbNotif'];
    }
    else if(actionName == 'newConvNotif')
    {
        var notifConvHtml = args['notifConvHtml'];
        //on met a jour la notifCOnv
        var newHtml = $(notifConvHtml).html();
        //get notif infos to update apConv if nescessary
        var apConvHtml = args['apConvHtml'];
    }

    //-----------
    //  ACTIONS
    //-----------
    if(pageName == "MessageCenter")
    {
        //define if conv notif exist
        if($convNotif.length < 1)
        {
            convNotifExist = false;
        }
        //define if conv is loaded
        if($convLoaded.attr('data-conv') != convid)
        {
            convIsLoaded = false;
        }

        //actions
        //un nouveau message arrive
        if(actionName == "newMessage")
        {
            //si la conv est focus
            if(convIsLoaded)
            {
                //on met a jour la notif conv, mais pas le compteur car la conv est loaded, d'ou le 0
                updateConvNotifWithValue(convid, messageContent, messageDate, authNick, 0, false);

                //on met a jour l'ap conv
                updateApConv(convid, messageContent, messageDate, authNick, 0, false);

                //si le message viens de moi meme on ne fais rien
                if(iduser != myid)
                {
                    //send node event read
                    readConv(convid);
                }
            }
            //sinon
            else{
                //si la notif conv existe
                if(convNotifExist)
                {
                    //on decheck la notifConv, on ajoute 1 desssus, on met a jour le nb tot de notifs
                    updateConvNotifWithValue(convid, messageContent, messageDate, authNick, 1, true);
                }
                else{
                    //on créé la notifConv
                    getConvNotif(convid, iduser);
                }
            }
        }
        //si 'newMessage' sur une conv non loaded
        if(actionName == 'newConvNotif')
        {
            //update ap conv and put to top
            updateApConvWithHtml(convid, $(apConvHtml));

            //si conv notif exist on met a jour l'html
            if(convNotifExist)
            {
                updateConvNotifWithHtml(convid, newHtml);
            }
            //si la notif conv n'existe pas
            else{
                //add notif to under menu
                addConvNotifHtml($(notifConvHtml));
            }
        }
        //action de consulter la notif under menu ou de cliquer sur un apercu conv
        if(actionName == 'consultNotif')
        {
            //consult notif conv in under menu
            consultConvNotif(convid);
            //consult notif in ap conv
            consultApConv(convid);
            //read conv
            readConv(convid);
            //reset nb notif apDiscussion
            $('.discussion[data-conv="'+ convid +'"]').find($('p[data-elem="notif-cntr"]')).text('');
            //reset nb notif convNotif
            $convNotif.find($('P[data-elem="notif-cntr')).text('');
        }
    }
    else{
        //define if chatBox exist
        if($convChatBox.length < 1)
        {
            chatBoxExist = false;
        }
        //define if conv notif exist
        if($convNotif.length < 1)
        {
            convNotifExist = false;
        }
        //define if conv chatBoxe is focus
        if(!$convChatBox.hasClass('focus'))
        {
            convIsFocus = false;
        }

        //actions
        if(actionName == "newMessage")
        {
            //si la chatBoxe exist
            if(chatBoxExist)
            {
                //si la conv est focus
                if(convIsFocus)
                {
                    //si le message viens de moi meme on ne fais rien
                    if(iduser != myid)
                    {
                        //si la notif conv existe
                        if(convNotifExist)
                        {
                            //on met a jour la notif conv, mais pas le compteur cas la conv est focus, d'ou le 0
                            updateConvNotifWithValue(convid, messageContent, messageDate, authNick, 0, false);
                        }
                        //sinon on ne fait rien
                        else{
                            //ne riend faire
                        }
                        readConv(convid);
                    }
                }
                //sinon
                else{
                    //si la notif conv existe
                    if(convNotifExist)
                    {
                        //on decheck la notifConv, on ajoute 1 desssus, on met a jour le nb tot de notifs
                        updateConvNotifWithValue(convid, messageContent, messageDate, authNick, 1, true);
                    }
                    else{
                        //on créé la notifConv
                        getConvNotif(convid, iduser);
                    }
                }
            }
            //la chatBox n'existe pas
            else{
                //si la notifConv existe
                if(convNotifExist)
                {
                    //on met a jour la notif et la decheck s'il faut pour mettre a jour le nb tot notifs convs
                    updateConvNotifWithValue(convid, messageContent, messageDate, authNick, 1, true);
                }
                //sinon
                else{
                    //on créé la notifConv
                    getConvNotif(convid, iduser);
                }
            }
        }
        if(actionName == 'newConvNotif')
        {
            //si conv notif exist on met a jour l'html
            if(convNotifExist)
            {
                updateConvNotifWithHtml(convid, newHtml);
            }
            //si la notif conv n'existe pas
            else{
                addConvNotifHtml($(notifConvHtml));
            }
        }
        if(actionName == 'consultNotif')
        {
            consultConvNotif(convid);
            readConv(convid);
            //reset nb notif chatBox
            $convChatBox.find($('p[data-elem="notif-cntr"]')).text('');
            //reset nb notif convNotif
            $convNotif.find($('P[data-elem="notif-cntr')).text('');
        }
    }
}

function updateNbNotifConfTot(toAddSub, actionAddorSub) {
    //get old nb total convNotif um
    var oldNbNotifUm = 0;
    if($('#user-item-message p[data-elem="unreadedNotifs"]').text() != '')
    {
        oldNbNotifUm = parseInt($('#user-item-message p[data-elem="unreadedNotifs"]').text());
    }
    //get new nb total convNotif um
    var newNbNotifUm;
    if(actionAddorSub == 'add')
    {
        newNbNotifUm = oldNbNotifUm + toAddSub;
    }
    if(actionAddorSub == 'sub')
    {
        newNbNotifUm = oldNbNotifUm - toAddSub;
    }
    //replace value of nb convNotif tot with updated value
    if(newNbNotifUm <= 0)
    {
        $('#user-item-message p[data-elem="unreadedNotifs"]').text('');
    }
    else{
        $('#user-item-message p[data-elem="unreadedNotifs"]').text(newNbNotifUm);
    }
}

function focusInConv(elemJquery) {
    elemJquery.trigger('click');
    var tmp =       elemJquery.attr('id');
    var pieces =    tmp.split('-');
    var convId =    pieces[1];

    var args = [];
    args['convid'] = convId;
    actionNotif(args, 'consultNotif');
}

function focusOutConv(elemJquery) {
    elemJquery.removeClass('focus');
    elemJquery.find($('div[data-elem="write-box-chatbox"]')).focusout();
}

function scrollConvToBottom(elemJquery, time){
    if(pageName == "MessageCenter")
    {
        $msgContainer = $(elemJquery).find($('div[data-elem="messages-container"]'));
    }
    else{
        $msgContainer = $(elemJquery).find($('.messages-box'));
    }
    $msgContainer.animate({scrollTop: $msgContainer.prop('scrollHeight')}, time);
}
//fonction pour deplacer la conversation elemJquery de grouped a current, deplace une conv de open a grouped si le nb de conv ouvertes est maximum
//elemJquery: chatBox elem, conversation grouped a deplacer vers les open conv
//si le paramètre elemJquery est absent alors on deplace une grouped conv, peu importe laquelle vers les open convs
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
        addSubNotifsGrouped($elemToMoveFromConvToGrouped, 'add', false);

        //on place la conv grouped selectionnée ds les conv courantes
        $('.chat-boxes-container').append(elemJquery);
        elemJquery.attr('data-state','open').attr('data-open','just-opened');
        //on met a jour le nb notif grouped si nescessaire
        addSubNotifsGrouped(elemJquery, 'sub', false);

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
            addSubNotifsGrouped($elemToMoveFromGroupedToConv, 'sub', false);
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
function moveConvToGroupedConv(elemJquery, changeState){
    //change data-state ds tt les cas, (meme si ce n'est pas nescessaire..) #//todo ameliorer le fonctionnement de ces fonctions a ce niveau (lire commentaire de la fonction)
    elemJquery.attr('data-state','grouped');
    $('.chat-boxes-grouped-container').append(elemJquery);
    //change state if true
    if(changeState)
    {
        //get conv params
        var tmp =       elemJquery.attr('id');
        var pieces =    tmp.split('-');
        var convId =    pieces[1];

        //change state BD
        changeChatState(convId, 'grouped');
    }
    //on met a jour le nb notif grouped si nescessaire
    addSubNotifsGrouped(elemJquery, 'add', false);
}

//fonction pour inserer la conv elemJquery ds les conv ouvertes, si le nb de conv ouvertes est maximum, deplace une conv dsl les convs grouped
//elemJquery: chatBox elem
//idConvInsertBefore: id chatBox elem pour inserer avant
//changeState: boolean, changer l'etat de la conv deplacée a grouped
//justAdd: boolean, simplement deplacer la conv ds les conv open sans controles, sans changement d'etat
function moveConvToOpenConvs(elemJquery, idConvInsertBefore, changeState, justAdd){
    //cas ou on ajoute d'une conv grouped au conv ouvertes: on doit deplacer une conv vers les grouped si nescessaire
    //si c'est une simple action d'ajout
    if(justAdd)
    {
        //on move la conv de grouped vers les conv open
        $('.chat-boxes-container').append(elemJquery);
        //on met a jour le nb notif grouped si nescessaire
        addSubNotifsGrouped(elemJquery, 'sub', false);
        return true;
    }
    //si l'action d'ajout implique des controles
    else{
        //on test si le nb de fenetres ouvertes est maximum avant de faire tt action
        //s'il l'est alors l'action implique de deplacer une conv ouverte vers les grouped
        if($('.chat-boxes-container').find($('.chat-box')).length >= limitBoxesOpen)
        {
            $elemToMoveToGrouped = $('.chat-boxes-container').find($('.chat-box')).last();

            //move conv to grouped
            moveConvToGroupedConv($elemToMoveToGrouped, changeState);

            //move elemJquery to current chatBox container
            if(idConvInsertBefore)
            {
                elemJquery.insertBefore($('#chatBox-' + idConvInsertBefore));
            }
            else{
                $('.chat-boxes-container').append(elemJquery);
            }
            //on met a jour le nb notif grouped si nescessaire
            addSubNotifsGrouped(elemJquery, 'sub', false);
        }
        // le nb de fenetres ouvertes n'est pas maximum, inutile de bouger unbe conv ouverte vers les grouped après l'action
        else{
            //on met a jour le nb notif grouped si nescessaire
            addSubNotifsGrouped(elemJquery, 'sub', false);
            //on move la conv de grouped vers les conv open
            $('.chat-boxes-container').append(elemJquery);
        }
    }
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
    var htmlToAdd = '<div class="user-added-to-convers"><div class="user-nickname">"'+ userNickname +'"</div><div class="cross-cancel-user-add" data-action="cancel-user-add" data-u="'+ iduser +'"><span>x</span></div></div>';
    jqueryChatBox.find($('div[data-elem="user-added-container"]')).append(htmlToAdd);
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
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
    }
    else{
        $elem = $('#chatBox-' + idConv);
    }
    $container = $elem.find($('.add-user-container'));
    $container.removeClass('active').find($('div[data-elem="searh-user"]')).html('');
    $container.find($('div[data-elem="user-added-container"]')).html('')
}

function displayUsersToDelete(idConv) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/displayDelUserConv.php",
        data: {idconv: idConv}
    }).done(function (msg) {
        if (msg != 'err')
        {
            if(pageName == "MessageCenter")
            {
                $elem = $('div[data-elem="discussion-content"]');
            }
            else{
                $elem = $('#chatBox-' + idConv);
            }
            $elem.find('.user-delete-container').html(msg).addClass('active');
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
            if(pageName == "MessageCenter")
            {
                $elem = $('div[data-elem="discussion-content"]');
                //change title apercu conv
                $('.discussion[data-conv="'+ idconv +'"]').find($('p[data-elem="title-chat"]')).html(title);
                //add title and pic user to conv
                $elem.find($('p[data-elem="conv-title"]')).html(title); //#todo modif pic user too
            }
            else{
                $elem = $('#chatBox-' + idConv);
                $elem.find($('.chat-box-title p')).html(title);
            }
            $elem.find($('.user-delete-container')).removeClass('active');
            setTimeout(function(){
                $elem.find($('.user-delete-container')).html('');
            },1000);
            imgSmoothLoading();
            deleteUsersFromConv(idconv, idusers);
        }
        else{

        }
    });
}

//cette fonction envoi l'evenement pour delencher les actions de delete chez les users
//idusers: string d'id séparé par ","
function deleteUsersFromConv(idconv, idusers) {
    idusers = idusers.split(',');
    $.each(idusers, function (index, iduser){
        socket.emit('deleteFromConv', {
            convid : idconv,
            iduser : iduser
        });
    });
}

//cette fonction permet a l'user courant de se delete de la conv lorsque l'evenement "getDeleted" est récupéré
function getDeletedFromConv(convid){
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/deleteUserFromConv.php",
        data: {idconv: convid, action:"getDeleted"}
    }).done(function (msg) {
        if (msg != 'err')
        {
            if(pageName == "MessageCenter")
            {
                //display new conversation
                dispayLastDiscussion();
                //remove apercu Conv
                $('.discussions-container').find($('.discussion[data-conv="'+ idConv +'"]')).remove();
            }
            else{
                restructureChatBoxes($('#chatBox-' + convid), 'leaveAfterKick');
            }
        }
        else{

        }
    });
}

//cette fonction permet a l'user de delete lui meme un user d'une conv lorsque l'evenement "deleteUserFromConv" est récupéré
function deleteUserFromConv(iduser, convid){
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/deleteUserFromConv.php",
        data: {idconv: convid, iduser:iduser, action:"delete"}
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
            if(pageName == "MessageCenter")
            {
                //display new conversation
                dispayLastDiscussion();
                //remove apercu Conv
                $('.discussions-container').find($('.discussion[data-conv="'+ idConv +'"]')).remove();
            }
            else{
                restructureChatBoxes($('#chatBox-' + idConv), 'leave');
            }
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

function askDeleteConv(idConv) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/prepareDeleteConv.php",
        data: {idelem:idConv, type:'conv'}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('.viewer-content').html('').append(msg);
            $('.viewer').addClass('active');
            $('.wrap').addClass('active');
            //deleteConv(idconv);
        }
        else{
            window.location.reload();
        }
    });
}

function deleteConv() {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/deleteConv.php",
        data: {}
    }).done(function (idconv) {
        if (idconv != 'err')
        {
            //remove ask
            $('.viewer-content').html('');
            $('.viewer').removeClass('active');
            $('.wrap').removeClass('active');
            
            //delete conv html
            if(pageName == "MessageCenter")
            {
                deleteDiscussion(idconv);
            }
            else{
                restructureChatBoxes($('#chatBox-' + idconv), 'delete');
            }
        }
        else{

        }
    });
}

//fonction propre aux pages differentes du message center;
function receiveMessage(iduser, idconv, message, typeMess) {
    if(pageName == "MessageCenter")
    {
        var loadedConvId = $('div[data-elem="discussion-content"]').attr('data-conv');
        if(loadedConvId == idconv)
        {
            writeMessage(iduser,idconv,message,typeMess);
        }
        else{
            //#todo rendre ca plus propre
            if(myid != iduser)
            {
                getConvNotif(idconv, iduser);
            }
        }
    }
    else{
        writeMessage(iduser,idconv,message,typeMess);
    }
}

//ajoute la notif aux convs notifs, pour eviter le bug lié a la recuperation de message lorsque le message viens de l'user courant, on transmet le texte du message
function getConvNotif(idconv, iduser){
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/displayNotifConv.php",
        data: {iduser: iduser, idconv: idconv},
        dataType: "json"
    }).done(function (msg) {
        var args = [];
        args['convid']          = idconv;
        args['notifConvHtml']   = msg.notifConvHtml;
        args['apConvHtml']      = msg.apConvHtml;
        actionNotif(args, 'newConvNotif');
        imgSmoothLoading();
    });
}

function writeMessage(iduser, idconv, message, typeMess) {
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/displayMessage.php",
        data: {iduser: iduser, idconv: idconv, message: message, typeMess:typeMess},
        dataType: "json"
    }).done(function (msg) {
        if (msg != 'err') {
            //get vars
            var messageHtml = msg.html;
            var messageContent = msg.message;
            var messageDate = msg.date;
            var authNick = msg.authNick;

            if (pageName == "MessageCenter")
            {
                $conv = $('div[data-elem="discussion-content"]');
                $msgContainer = $conv.find('div[data-elem="messages-container"]');
                $prevMessage = $msgContainer.find($('div[data-elem="message"]')).last();
            }
            else {
                $conv = $('#chatBox-' + idconv);
                $msgContainer = $conv.find('div[data-elem="messages-box"]');
                $prevMessage = $msgContainer.find($('div[data-elem="message"]')).last();

                //add notifs to conv chatBox if nescessary
                if(iduser != myid)
                {
                    addNotif($conv, 1);
                }
            }

            //reset viewedBy
            resetViewedByForConv($conv);

            //add message with scle animation
            if ($prevMessage) {
                addMessageInFront($msgContainer, messageHtml, $prevMessage);
            }
            else {
                addMessageInFront($msgContainer, messageHtml, false);

            }

            scrollConvToBottom($conv, 500);

            //images display
            imgSmoothLoading();

            //add or update notifs Conv
            var args = [];
            args['convid'] = idconv;
            args['messageContent'] = messageContent;
            args['messageDate'] = messageDate;
            args['authNick'] = authNick;
            args['iduser'] = iduser;
            args['nbNotif'] = 1;

            //add notif
            actionNotif(args, 'newMessage');
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
                socket.emit('newConvToDisplayOnActivateConv', {
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

//cette fonction sert a inserer la conversation ds les conversation de l'user que l'on ajoute a la conv ds le cas ou celui n'est pas connécté
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
            window.location.reload();
        }
    });
}

function readConv(idconv){
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/updateReaded.php",
        data: {idConv: idconv}
    }).done(function(stringIdusers) {
        if(stringIdusers != 'err')
        {
            //on retir l'user courant
            //on emit l'event avec les id des users ds une chaine
            socket.emit('readConv', {
                convid : idconv,
                whoread : myid,
                usersinconv : stringIdusers
            });
        }
        else{
            window.location.reload();
        }
    });
}

function updateReadedBy(idconv, iduserWhoRead) {
    if (pageName == "MessageCenter")
    {
        $conv = $('div[data-elem="discussion-content"]');
    }
    else{
        $conv = $('#chatBox-' + idconv);
    }

    $message = $conv.find('div[data-elem="message"]').last();

    //define if message already read by user
    var readByIds = $message.find('p[data-elem="readed"]').attr('readby');
    var getReadBy = false;
    var idusersArr = [];

    //define if message is already readed by user
    //si readBy n'est pas nul
    if(readByIds != '')
    {
        idusersArr = readByIds.split(',');
        if($.inArray(iduserWhoRead, idusersArr) !== -1)
        {
            return false;
        }
        else{
            getReadBy = true;
        }
    }
    else{
        getReadBy = true;
    }

    //if get readBy is true then we can get "readed by"
    if(getReadBy)
    {
        //add user id to readedBy list and update it
        idusersArr.push(iduserWhoRead);
        var newStringIdUsers = idusersArr.join(',');
        $message.find($('p[data-elem="readed"]')).attr('readby',newStringIdUsers);

        //define if "viewed by" message is already writed, usefull to know if the text "readed by" have to be generated or not
        var readExist = false;
        if(readByIds != '')
        {
            readExist = true
        }
        $.ajax({
            method: 'POST',
            url: "inc/MessageCenter/convAction.php",
            data: {convid: idconv, iduser: iduserWhoRead, readExist:readExist, action:'updateReaded'}
        }).done(function(msg) {
            if(msg != 'err')
            {
                if(readExist)
                {
                    //alor msg est un nickname
                    var oldtext = $message.find($('p[data-elem="readed"]')).text();
                    var newtext = oldtext + ',' + msg;
                    setTimeout(function(){
                        $message.find($('p[data-elem="readed"]')).text(newtext);
                        scrollConvToBottom($conv, 0);
                    },1000);
                }
                else{
                    //alors msg est 'Readed by nickname'
                    setTimeout(function(){
                        $message.find($('p[data-elem="readed"]')).text(msg);
                        scrollConvToBottom($conv, 0);
                    },1000);
                }
            }
            else{
                window.location.reload();
            }
        });
    }
}

function resetViewedByForConv(elemJquery) {
    elemJquery.find('p[data-elem="readed"]').text('');
}

//charger les messages precedents
function loadPrevMessages(idconv, nbMessagesLoaded, typeConv)
{
    $.ajax({
        method: 'POST',
        url: "inc/MessageCenter/loadMessages.php",
        data: {convid: idconv, nbMessagesLoaded: nbMessagesLoaded},
        dataType: "json"
    }).done(function(msg){
        if(msg != 'err')
        {
            if(typeConv == 'chatBox')
            {
                $conv =         $('#chatBox-' + idconv);
                $messageBox =   $conv.find('div[data-elem="messages-box"]');
            }
            else if(typeConv == 'discussion')
            {
                $conv =         $('div[data-elem="discussion-content"]');
                $messageBox =   $conv.find('div[data-elem="messages-container"]');
            }

            //prep vars to fix scroll when adding
            //where the box is currently:
            $firstMessAded = $messageBox.find('div[data-elem="message"]').first();
            var curOffset = $firstMessAded.offset().top - $messageBox.scrollTop();

            var lengthMessages = msg.messages.length;
            $.each(msg.messages, function (index, messageHtml) {
                if(index != lengthMessages - 1)
                {
                    addMessageInBack($messageBox, messageHtml, $(msg.messages[index + 1]));
                }
                else if(index == lengthMessages - 1)
                {
                    addMessageInBack($messageBox, messageHtml, false);
                }
                imgSmoothLoading();
            });

            //display messages
            $conv.find('div[data-elem="message"]').show('slow').find('.user-message').css('transform', 'scale(1)');

            //get scroll position to back after prepend
            $messageBox.scrollTop($firstMessAded.offset().top - curOffset);

            //allow conv to load other messages
            $messageBox.removeClass('loading');
        }
        else{
            window.location.reload();
        }
    });
}

function userIsConnected(iduser)
{
    //send message event
    socket.emit('isConnected', {
        userid  : myid
    });
}

//remove focus class from chatBoxes
$(window).click(function() {
    $('.chat-box').removeClass('focus');
});


//create chat box when click on message user logo
$(document).on("click", "a[data-elem='msg-user']", function(){
    createChatBoxe();
});

//create empty chat bow when click on new message
$('a[data-action="new-msg"]').click(function(){
    if(pageName == "MessageCenter")
    {
        var idconv = $('.discussions-container').find('div[data-elem="ap-discussion"]').eq(0).attr('data-conv')
        getConv(idconv, 'discussion', false, '');
    }
    else{
        createEmptyChatBox();
    }
});

//#todo factoriser avec des regew
$(document).on('click', "div[data-action='close-chatbox']", function(){
    $elem = $(this).closest($('.chat-box'));
    restructureChatBoxes($elem, 'close');
});

$(document).on('click', "div[data-action='minimize-box']", function(e){
    $elem = $(this).closest($('.chat-box'));
    focusOutConv($elem);
    restructureChatBoxes($elem, 'minimized');
    e.stopPropagation();
});

//change state convers: minimized -> open, when click on header
$(document).on('click', "div[data-state='minimized'] .chat-box-title-container", function(e){
    //ne rien faire si le header n'est pas directement cliqué
    $elem = $(this).closest($('.chat-box'));
    restructureChatBoxes($elem, 'open');
});

//change state convers: grouped -> open, when click on header
$(document).on('click', "div[data-state='grouped'] .header-chat-box", function () {
    $elem = $(this).closest($('.chat-box'));
    restructureChatBoxes($elem, 'open');
});

//open adduser input
$(document).on('click', "*[data-action='add-user-to-convers']", function () {
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
    }
    else{
        $elem =  $(this).closest($('.chat-box'));
    }
    $elem.find($('.active')).removeClass('active');
    $elem.find($('.add-user-container')).addClass('active');
});

//Add user to conv function
$(document).on("keyup","div[data-elem='searh-user']", function(){
    var convId = '';
    var from = '';
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
        convId =  $elem.attr('data-conv');
        from = 'MessageCenter';
    }
    else{
        $elem =  $(this).closest($('.chat-box'));
        var tmp = $elem.attr('id');
        var pieces = tmp.split('-');
        convId = pieces[1];
        from = 'chatBox';
    }
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
            searchToAddUser(tosearch, convId, userAdedToExclude, from);
            return true;
        }
    }
});

//delete just created state of conv and manage focus in conv to updatereadConv
$(document).on('click', '.chat-box', function(e){
    //get conf vars
    var tmp = $(this).attr('id');
    var pieces = tmp.split('-');
    var idConv = pieces[1];

    //remove edit options if they are opened
    $(this).find($('.edit-options')).removeClass('active');

    //reset focus other convs
    $(this).siblings().removeClass('focus');

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
        readConv(idConv);

    }
    if(validAddClass)
    {
        //$('.chat-box').removeClass('focus');
        $(this).addClass('focus');
    }
    //leave hover when mouse is on menu
    $('.user-menu-items li').mouseleave();

    //consult notif
    var args = [];
    args['convid'] = idConv;
    actionNotif(args, 'consultNotif');

    //avoid blur bug
    e.stopPropagation();
    return false;
});

$(document).on('blur', '.chat-box', function(){
    //$(this).closest($('.chat-box')).removeClass('focus');
});

//Add users to convers
var arrayIdAdded = [];
$(document).on('click', '*[data-action="add-user-to-conv"]', function(){
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
    }
    else{
        $elem = $(this).closest($('.chat-box'));
    }

    //get parameters
    var idUser = $(this).attr('data-id');
    var userNickname = $(this).find($('.users-ids h4')).text();

    //effacer les resultats
    $elem.find($('.add-user-result-container')).remove();

    //effacer le champ input
    $elem.find($('div[data-elem="searh-user"]')).text('');

    //add userid to array of added user to conv (sert aux controle pour la recherche d'user a ajouter)
    arrayIdAdded.push(idUser);

    //prepare add
    displayUserAdedToConv(idUser,userNickname, $elem);
});

//valid add user to conv
$(document).on('click', 'button[data-action="valid-add-user"]', function(){
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
    }
    else{
        $elem = $(this).closest($('.chat-box'));
    }
    //si le tableau d'user a add est vide
    if (arrayIdAdded.length > 0)
    {
        var emptyConv = false;
        var idConv = '';
        var from = '';
        if(pageName == "MessageCenter")
        {
            idConv = $elem.attr('data-conv');
            from = "MessageCenter";
        }
        else{
            var pieces = $elem.attr('id').split('-');
            idConv = pieces[1];
            from = "chatBox";
        }

        //definir si la conv d'ou on clique est une conv new ou pas
        if($elem.attr('empty-conv') == 'true')
        {
            emptyConv = true;
        }

        //implode userid array
        var strIdAded = arrayIdAdded.join(',');
        prepareAddUserToConv(idConv, strIdAded, emptyConv, from);

        //vider le tableau d'ids
        arrayIdAdded = [];
    }

    //vider les champs
    $container = $elem.find($('.add-user-container'));
    $container.removeClass('active').find($('div[data-elem="searh-user"]')).html('');
    $container.find($('div[data-elem="user-added-container"]')).html('');
    $container.find($('.add-user-results')).html('');
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

//delete adding user
$(document).on('click', 'div[data-action="cancel-user-add"]', function () {
    var idUserToCancelAdd = $(this).attr('data-u');
    //remove user from addingUser array
    arrayIdAdded = jQuery.grep(arrayIdAdded, function(value) {
        return value != idUserToCancelAdd;
    });
    //remove elem
    $(this).closest($('.user-added-to-convers')).remove();
});
//delete user from conv

$(document).on('click', 'button[data-action="del-users-from-conv"]', function () {
    var convId = '';
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
        convId = $elem.attr('data-conv');
    }
    else{
        $elem =  $(this).closest($('.chat-box'));
        var tmp = $elem.attr('id');
        var pieces = tmp.split('-');
        convId = pieces[1];
    }
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
    var convId = '';
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
        convId = $elem.attr('data-conv');
    }
    else{
        $elem = $(this).closest($('.chat-box'));
        var tmp = $elem.attr('id');
        var pieces = tmp.split('-');
        convId = pieces[1];
    }
    prepareDeleteUserFromConv(convId, arrayUserToDelete.join(','));
    arrayUserToDelete = [];
});

//leave conv
$(document).on('click', 'button[data-action="leave-conv"]', function () {
    var convId = '';
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
        convId = $elem.attr('data-conv');
    }
    else{
        $elem = $(this).closest($('.chat-box'));
        var tmp = $elem.attr('id');
        var pieces = tmp.split('-');
        convId = pieces[1];
    }
    leaveConv(convId);
});

//display rename conv
$(document).on('click', 'button[data-action="rename-conv"]', function () {
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
    }
    else{
        $elem = $(this).closest($('.chat-box'));
    }
    //var nameConv = $elem.find($('.chat-box-title p')).text();
    //$elem.find($('div[data-elem="rename-conv-input"]')).html(nameConv);
    $elem.find($('.active')).removeClass('active');
    $elem.find($('.nameconv-container')).addClass('active');
});

//rename conv
$(document).on('click', 'button[data-action="valid-name-conv"]', function () {
    var convId =  '';
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
        convId = $elem.attr('data-conv');
    }
    else{
        $elem = $(this).closest($('.chat-box'));
        var tmp = $elem.attr('id');
        var pieces = tmp.split('-');
        convId = pieces[1];
    }

    var newName = $elem.find($('div[data-elem="rename-conv-input"]')).text();

    if($.trim(newName) == '')
    {
        $elem.find($('.nameconv-container')).removeClass('active');
        return false;
    }


    //appliquer la modif instatanement
    $elem.find($('p[data-elem="conv-title"]')).html(newName);

    //erase field content
    $elem.find($('div[data-elem="rename-conv-input"]')).html('');

    //cacher le champ
    $elem.find($('.nameconv-container')).removeClass('active');
    renameConv(convId, $.trim(newName));
});

//delete conv
$(document).on('click', 'button[data-action="delete-conv"]', function () {
    var convId = '';
    if(pageName == "MessageCenter")
    {
        $elem = $(this).closest($('div[data-elem="discussion-content"]'));
        convId =  $elem.attr('data-conv');
    }
    else{
        $elem =  $(this).closest($('.chat-box'));
        var tmp = $elem.attr('id');
        var pieces = tmp.split('-');
        convId = pieces[1];
    }
    askDeleteConv(convId);
});

//cancel ask delete conv delete
$(document).on('click', 'button[data-action="cancel-delete-conv"]', function () {
    $('.viewer-content').html('');
    $('.viewer').removeClass('active');
    $('.wrap').removeClass('active');
});

//cancel ask delete conv delete
$(document).on('click', 'button[data-action="valid-delete-conv"]', function () {
    deleteConv();
});

//send message when click send
$(document).on('click', 'button[data-action="sendMess"]', function(e) {
    $elem = $(this).closest($('div[data-elem="discussion-content"]'));
    $valinput = $elem.find('div[data-elem="write-box-chatbox"]');
    var val = $valinput.html().replace(/<\/?(?!img)(?!br)(?!a)[a-z]+(?=[\s>])(?:[^>=]|=(?:'[^']*'|"[^"]*"|[^'"\s]*))*\s?\/?>/gi, '');

    if($valinput != '' )
    {
        var convId = $elem.attr('data-conv');

        //display message and write in db
        //writeMessage(convId, $valinput, 'chatBox');
        enregMessage(convId, val);
        $valinput.html('');

        //send message event
        socket.emit('newMessage', {
            message : val,
            convid  : convId,
            userid  : myid
        });
        e.stopPropagation();
        e.preventDefault();
    }
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

            if($valinput != '' )
            {
                var convId = '';
                if(pageName == "MessageCenter")
                {
                    $elem = $(this).closest($('div[data-elem="discussion-content"]'));
                    convId =  $elem.attr('data-conv');
                }
                else{
                    //get convid
                    $elem = $(this).closest($('.chat-box'));
                    var tmp = $elem.attr('id');
                    var pieces = tmp.split('-');
                    convId = pieces[1];
                }


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
        e.preventDefault();
    }
    return e.which != 13;
});

//display conv when click on notif header menu
$(document).on('click', 'div[data-elem="conv-notified"]', function () {
    var idConv = $(this).attr('data-chatNotif');
    if(pageName == "MessageCenter")
    {
        getConv(idConv, 'discussion', false, '');
    }
    else{
        //si la conv ext deja chatBox
        if($('#chatBox-' + idConv).length == 1)
        {
            restructureChatBoxes($('#chatBox-' + idConv), 'open', false);
        }
        //si la conv est fermée (grande majorirté des cas)
        else{
            getConv(idConv, 'chatBox', false, 'open');
        }
        var args = [];
        args['convid'] = idConv;
    }
    actionNotif(args, 'consultNotif');
});

//display old messages when scrool top
$(document).on('mouseenter', 'div[data-elem="messages-box"]', function(){
    $(this).scroll(function(e)
    {
        var scrollTop = $(this).scrollTop();
        //var topDistance = $(this).offset().top;
        if(scrollTop < 50)
        {
            if(!$(this).hasClass('loading'))
            {
                $elem = $(this).closest($('.chat-box'));
                var tmp = $elem.attr('id');
                var pieces = tmp.split('-');
                var convId = pieces[1];
                var nbMessagesLoaded = $(this).find('div[data-elem="message"]').length;
                $(this).addClass('loading');
                loadPrevMessages(convId, nbMessagesLoaded, 'chatBox');
                return false;
            }
        }
    });
    $(this).mouseleave(function() {
        $(this).unbind();
    });
});

$('div[data-elem="messages-box"]').scroll(function(){
    //alert('ok');
});

//lor du click sur un element search result conversation
$(document).on('click', '.chat-box div[data-elem="ap-discussion"]', function () {
    $elem = $(this).closest($('.chat-box'));
    var tmp = $elem.attr('id');
    var pieces = tmp.split('-');
    var convIdEmpty = pieces[1];
    
    //fermer conv empty
    restructureChatBoxes($('#chatBox-' + convIdEmpty), "close");

    //open conv
    var convidToOpen = $(this).attr('data-conv');
    var stateConv = $('#chatBox-' + convidToOpen).attr('data-state');
    if(stateConv == 'grouped' || stateConv == 'open' || stateConv == 'minimized')
    {
        restructureChatBoxes($('#chatBox-' + convidToOpen), "open");
    }
    else{
        getConv(convidToOpen, 'chatBox', false, 'open');
    }
});

//node.js part
$(function () {
    //lor de la recetion d'un nvx message
    socket.on('newMessage', function (messageDetails){
        receiveMessage(messageDetails.userid, messageDetails.convid, messageDetails.message, 'chatBox');
    });

    //#todo action messsages
    //lor de la reception d'un
    socket.on('newInfoMessage', function (messageDetails){
        var convid =    messageDetails.convid;
        var typeMess =  messageDetails.typeMess;
        if(typeMess == 'adduser')
        {
            var useridadded = messageDetails.userid;
            writeInfoMessage(convid, useridadded, typeMess);
        }
    });

    //lor de l'activation d'une conv à laquelle participe l'user courant
    //evenement recu par l'user courant si un user distant l'ajoute ou demare une conv avec lui
    socket.on('newConvToDisplayOnActivateConv', function (ObjResult){
        if(pageName == "MessageCenter")
        {
            //on genere la conv notif ds le cas du mess center 
            getConv(ObjResult.convid, 'notif/apDiscu', true, '');
        }
        else{
            getConv(ObjResult.convid, 'chatBox', false, 'openOnCreate');
        }
    });

    //lor l'ajout de l'user courant a une conv a été efféctué, afficher la conv
    //evenement recu par l'user courant si un user distant l'ajoute ou demare une conv avec lui
    socket.on('newConvToDisplayOnAddUser', function (ObjResult){
        if(pageName == "MessageCenter")
        {
            //on genere la conv notif ds le cas du mess center
            getConv(ObjResult.convid, 'notif/apDiscu', true, '');
        }
        else{
            getConv(ObjResult.convid, 'chatBox', false, 'openOnCreate');
        }
    });

    //lorsque l'evenement ajout / activation de conv ne parviens pas aux users concernés on effectue nous meme les operations d'insertion ds la BD
    socket.on('newConvToCreate', function (ObjResult){
        openConv(ObjResult.userid, ObjResult.convid);
    });

    //lorsque l'event kicked from conv est recu par l'user courant
    socket.on('getDeletedFromConv', function (ObjResult){
        getDeletedFromConv(ObjResult.convid);
    });

    //lorsque l'evenement kickedOutConv ne parviens pas aux users concernés on effectue nous meme les operations d'insertion ds la BD
    socket.on('deleteUserFromConv', function (ObjResult){
        deleteUserFromConv(ObjResult.iduser, ObjResult.convid);
    });

    //lorsqu'un user d'une conv lis un message
    socket.on('readedBy', function (ObjResult){
        updateReadedBy(ObjResult.convid, ObjResult.whoread);
    });
});