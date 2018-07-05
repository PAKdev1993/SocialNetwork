function displayModifsOptions(datas, index) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/modifsoptions.php",
        data: {datas: datas}
    }).done(function(msg){
        if(msg != 'err')
        {
            domElem = $('.timeline-elem').get(index);
            $elem = $(domElem).find('.post-edit-options-container');
            $elem.append(msg);
            $elem.parent().addClass('active');
            $elem.parent().find('.bt-modifs').click(function(){
                $(this).parents('.post-edit-options').removeClass('active');
            })
        }
        else{
            window.location.reload();
        }
    });
}

function validModifications(newtext, newPreview, newimgstring, datas, index){
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/modifspost.php",
        data: {newtext: newtext, newPreview:newPreview, newimgstring: newimgstring, datas:datas}
    }).done(function(msg){
        if(msg != 'err')
        {
            var domElem = $('.timeline-elem').get(index);
            $body = $(domElem).find('.post-body');
            $header = $(domElem).find('.post-header');
            $body.remove();
            $header.remove();
            $(msg).insertBefore($(domElem).find('.post-options'));

            //resore linkArray (linkpreview.js)
            links = [];
        }
        else{
            window.location.reload();
        }
    });
}

function askDeletePost(datas){
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/preparedeletepost.php",
        data: {datas:datas}
    }).done(function(msg){
        if(msg != 'err')
        {
            $('.viewer-content').html('').append(msg);
            $('.viewer').addClass('active');
            $('.wrap').addClass('active');
        }
        else{
            window.location.reload();
        }
    });
}

function deletePost(indexToDelete) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/deletepost.php"
    }).done(function(msg){
        if(msg != 'err') {
            $('.viewer').removeClass('active');
            $('.viewer-content').html('');
            domElem = $('.timeline-elem').get(indexToDelete);
            $(domElem).addClass('deleted');
            $('.wrap').removeClass('active');
            //#todo suppression de la div cause un bug au niveau du show-next comments, trouver comment resoudre
            setTimeout(
                function () {
                    //$(domElem).remove();
                    $(domElem).html('');
                }, 700);
        }
        else{
            window.location.reload();
        }
    });
}

function hidePost(datas, indexToHide) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/hidepost.php",
        data: {datas:datas}
    }).done(function(msg){
        if(msg != 'err') {
            $('.viewer').removeClass('active');
            $('.viewer-content').html('');
            domElem = $('.timeline-elem').get(indexToHide);
            $(domElem).addClass('deleted');

            //suppression de la div cause un bug au niveau du show-next comments
            setTimeout(
                function () {
                    $(domElem).remove();
                    $(domElem).html('');
                }, 700);
        }
        else{
            window.location.reload();
        }
    });
}

function askhideUsersPost(datas) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/preparehideusersposts.php",
        data: {datas:datas}
    }).done(function(msg){
        if(msg != 'err') {
            $('.viewer-content').html('').append(msg);
            $('.viewer').addClass('active');
            $('.wrap').addClass('active');
        }
        else{
            window.location.reload();
        }
    });
}

function hideUsersPost(datas, indexToHide) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/hideusersposts.php",
        data: {datas:datas}
    }).done(function(msg){
        if(msg != 'err') {
            $('.viewer').removeClass('active');
            $('.viewer-content').html('');
            domElem = $('.timeline-elem').get(indexToHide);
            $(domElem).addClass('deleted');
            //suppression de la div cause un bug au niveau du show-next comments
            setTimeout(
                function () {
                    $(domElem).remove();
                    $(domElem).html('');
                }, 700);
        }
        else{
            window.location.reload();
        }
    });
}
    

var oldimgArray =       [];
var imgArrayToDelete =  [];
var oldtext =           '';
var oldHtml =           '';
var oldPreview =        '';
//special var just for delete system
var indexToDelete =     '';
//special var for hide system
var indexToHideusersPosts = '';
var datasusersToHide =  '';

/*----------------------------------------------------------------------------------------------------------*\
 * afficher les options d'edition disponnible en fonction du status de l'user: auteur du post ou non        *
\*----------------------------------------------------------------------------------------------------------*/
$('.timeline-container').on('click', '.bt-display-options', function() {
    var datas = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');
    //recupère l'index du post pour la manipulation du dom
    var index = $(this).parents('.timeline-elem').index();
    displayModifsOptions(datas, index);
});

/*----------------------------------------------------------------------------------------------------------*\
 * mettre le post en mode edition, creer le tableau de nom d'images, afficher l'inetgralité des images      *
\*----------------------------------------------------------------------------------------------------------*/
$('.timeline-container').on('click', '.bt-edit', function() {
    //supprime les paragraphes vides
    $(this).parents('.post-wrap').find('.post-text').find('p').each(function(){
       if($.trim($(this).text()) == '')
       {
           $(this).remove();
       }
    });

    //met la div texte en mode editable, remplace le contenu
    $divText = $(this).parents('.post-wrap').find('.post-text');
    //passage en mode edition
    $divText.attr('contenteditable','true').addClass('author-input');
    $divText.html($.trim($divText.html()) + '<span> </span>');
    $(this).parents('.post-wrap').find('.post-body').addClass('post-body-active');

    //reinitialise la valeur du tab:
    oldimgArray  = [];
    var index = $(this).parents('.timeline-elem').index();
    domElem = $('.timeline-elem').get(index);
    $(domElem).find('.post-pic img').each(function(){
        oldimgArray.push($(this).attr('data-name'));
    });
    //declancher le depliement des photos
    $(domElem).find('.display-next-pic').trigger('click');

    //memorise l'ancien texte
    oldtext = $(domElem).find('.post-text').text();
    oldHtml = $(domElem).find('.post-text').html();

    //memorise l'ancien preview
    oldPreview = $(domElem).find('.preview-bloc').html();
});

/*----------------------------------------------------------------------*\
 * VALIDATION POST UPDATE                                               *
\*----------------------------------------------------------------------*/
$('.timeline-container').on('click', '.valid-modifs', function() {
    //retirer le boutton valid modilfs
    $(this).closest('.post-wrap').find('.post-body').removeClass('post-body-active');
    //remettre la div contenteditable no editable
    $(this).closest('.post-wrap').find('.post-text').attr('contenteditable','false').removeClass('author-input');

    var datas = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');
    var index = $(this).parents('.timeline-elem').index();
    var domElem = $('.timeline-elem').get(index);

    //VARIABLES
    var newtext =       $.trim($(domElem).find('.post-text').text());
    var newHtml =       $(domElem).find('.post-text').html().replace(/<\/?(?!img)(?!br)(?!a)[a-z]+(?=[\s>])(?:[^>=]|=(?:'[^']*'|"[^"]*"|[^'"\s]*))*\s?\/?>/gi, '');
    var PreviewState =  $(domElem).find('.preview-content').attr('data-state');
    var newPreview =    '';
    var newimgString =  '';
    var noNeedUpdates = 'noNeedImgUpdate';
    var newimgArray =   oldimgArray; //assignation des anciennes valeurs avant la mise a jour pour la comparaison

    //on supprime ces images du tableau d'images a conserver
    $(imgArrayToDelete).each(function(){
        var elemToRemove = this;
        newimgArray = $.grep(newimgArray, function(value) {
            return value != elemToRemove;
        });
    });

    //on créé la string de nom d'images a envoyer en base de donnees
    for(var i = 0; i < newimgArray.length; i++)
    {
        //tout les noms d'images prennent un / a la fin sauf la dernière
        //newsimgString doit etre de la form *.ext/*.ext/*.ext
        if(i == newimgArray.length - 1)
        {
            newimgString = newimgString + newimgArray[i];
        }
        else{
            newimgString = newimgString + newimgArray[i] + '/';
        }
    }

    //si la preview a été modifié
    if(PreviewState == "deleted"){
        newPreview = '';
    }
    if(PreviewState == "updated"){
        newPreview = $(domElem).find('.preview-bloc').html();
    }
    //remettre la preview
    $(this).closest('.post-wrap').find('.preview-content').attr('data-state','updated');

    //si nouveau text vide et images to delete = img du post et previex deleted
    if(newtext.length == 0 && imgArrayToDelete.length >= oldimgArray.length && PreviewState == 'deleted')
    {
        $(domElem).find('.post-text').html(oldHtml);
        indexToDelete = index;
        askDeletePost(datas);

        //reinitialisation du imgArrayToDelete
        imgArrayToDelete = [];
        return true;
    }
    //si aucune image n'a été modifiée mais le texte oui
    if(imgArrayToDelete.length == 0 && newtext != oldtext)
    {
        validModifications($.trim(newHtml), newPreview, noNeedUpdates, datas, index);

        //reinitialisation du imgArrayToDelete
        imgArrayToDelete = [];
        return true;
    }
    //ds tout les autres cas
    else{
        validModifications(newHtml, newPreview, newimgString, datas, index);

        //reinitialisation du imgArrayToDelete
        imgArrayToDelete = [];
        return true;
    }
});

/*----------------------------------------------------------------------*\
 * PUSH PHOTO'S NAME IN ARRAY 'a supprimer'                             *
\*----------------------------------------------------------------------*/
$('.timeline-container').on('click', '.trash-container', function() {
    imgArrayToDelete.push($(this).next('img').attr('data-name'));
    //on ne voit plus la trash au survol
    $(this).addClass('active');
    //la div "Cancel" s'affiche
    $(this).parent().find('.cancel-container').addClass('active');
});

/*----------------------------------------------------------------------*\
 * DELETE PHOTO'S NAME FROM ARRAY 'a supprimer'                         *
\*----------------------------------------------------------------------*/
$('.timeline-container').on('click', '.cancel-pic-modif', function() {
    var removeItem = $(this).parents('.post-pic').find('img').attr('data-name');
    imgArrayToDelete = $.grep(imgArrayToDelete, function(value) {
        return value != removeItem;
    });
    $(this).parent().removeClass('active');
    $(this).parents('.post-pic').find('.trash-container').removeClass('active');
});

/*----------------------------------------------------------------------*\
 * BT y/n GENERALS                                                      *
\*----------------------------------------------------------------------*/
$(document).on('click', '.box .valid', function(e){
    $(this).parents('.wrap').removeClass('active');
    $('.viewer').removeClass('active');
});

/*----------------------------------------------------------------------*\
 * DELETE POST                                                          *
\*----------------------------------------------------------------------*/
$('.timeline-container').on('click', '.bt-delete', function(e){
    var datas = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');
    indexToDelete = $(this).parents('.timeline-elem').index();
    askDeletePost(datas);
});

$(document).on('click', '#box-delete-post .bt-y', function(e){
    deletePost(indexToDelete);
});

$(document).on('click', '#box-delete-post .bt-n', function(e){
    $('.viewer').removeClass('active');
});
/*----------------------------------------------------------------------*\
 * HIDE POST                                                            *
\*----------------------------------------------------------------------*/
$('.timeline-container').on('click', '.bt-hide', function(e){
    var datas = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');
    var indexToHide = $(this).parents('.timeline-elem').index();
    hidePost(datas, indexToHide);
});
/*----------------------------------------------------------------------*\
 * HIDE USERS'S POSTS                                                   *
\*----------------------------------------------------------------------*/
$('.timeline-container').on('click', '.bt-hide-all', function(e){
    datasusersToHide = $(this).parents('.timeline-elem').find('.post-wrap').attr('data-elems');
    indexToHideusersPosts = $(this).parents('.timeline-elem').index();
    askhideUsersPost(datasusersToHide);
});

$(document).on('click', '.valid-hide.bt-y', function(e){
    hideUsersPost(datasusersToHide, indexToHideusersPosts);
});
$(document).on('click', '.valid-hide.bt-n', function(e){
    $('.viewer').removeClass('active');
});

/*----------------------------------------------------------------------*\
 * DELETE / RESTORE LINK PREVIEW                                        *
\*----------------------------------------------------------------------*/
$(document).on('click', 'div[data-action="delete-post-preview"]', function(){
    //opacité de la div a 0.5, attr data-state = deleted
    $(this).closest('.preview-content').attr('data-state','deleted');
});

$(document).on('click', 'div[data-action="cancel-delete-post-preview"]', function(){
    //restore opacité de la div, attr data-state = updated
    $(this).closest('.preview-content').attr('data-state','updated');
});


