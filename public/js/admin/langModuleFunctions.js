/*
** VARIABLES
 */

//afficher l'icone de moficiation lorsque c'est possible
var toogleModifs = 0;

//variables nescessaire a la récuperation de la traduction existante avant modifications
var dataLangs; //definis lor du mouseenter;
var langSelected; //definis lor du radio-lang .change
var pageName = $('body').attr('id');

//affiche le logo lorsque la traduction est possible = présence de la balise data-lang
$('*').mouseenter(function(){
    if($(this).is('[data-lang]') && toogleModifs == 0)
    {
        //afficher la div grise avec le logo check
        $('.modifs').removeClass('modifs-active');
        $(this).find('.modifs').addClass('modifs-active')
        dataLangs = $(this).attr('data-lang');
    }
});

//afficher le modification bloc lor du clique sur l'élément a traduire
var toogleCross = 0;
$('.cross-close').click(function(){
    if(toogleCross == 0)
    {
        //passer en mode edition de langue
        $('.modifs').addClass('modifs-active-permanent');

        //detecter si le bloc modif depase de l'ecran
        $('.modifs-inner').css('left',0);
        var documentWidth = $('body').width();
        var leftOffset = $(this).offset().left;
        var widthBloc = $('.modifs-inner').width();
        //le bloc module dépasse sur la droite
        if((documentWidth - leftOffset) <= widthBloc)
        {
            var left = widthBloc - (documentWidth - leftOffset) +20;
            $('.modifs-inner').css('left',-left);
        }
        //le bloc module depasse en bas
        var documentHeight = $('.wrap').height();
        var parentTopOffset = $(this).parents('[data-lang]').offset().top;
        if((documentHeight - parentTopOffset) <= 120)
        {
            //var top = 120;
            $('.modifs-inner').css('top','-330px');
        }
        //alert(documentHeight+ " " + parentTopOffset);
        //stopper le defilement du carousel
        $('.carousel').carousel('pause');

        toogleModifs = 1;
        toogleCross = 1;
    }
    else {
        //sortir du mode edition de langue
        $('.modifs').removeClass('modifs-active-permanent');
        $('.traduce-fields').remove();
        toogleModifs = 0;
        toogleCross = 0;

        //relancer le defilement du carousel
        $('.carousel').carousel('cycle');

        //gestion du cas particulier ou l'utilisateur clique deux fois d'affilés sur le meme editeur APRES avoir deja checké une box de langue
        $('.radio-lang').prop('checked', false);
        //retourner a l'etat 'SELECT LANGUE BEFORE'
        $('.traduce-inner').removeClass('traduce-inner-active');
        $('.traduce-inner-before-active').removeClass('traduce-inner-before-unactive');
    }
});

//au check d'un élément afficher les formulaires
$(".radio-lang").change(function(){
    tinymce.remove('.textarea-tinymce');
    $('.traduce-fields').remove();
    if(this.checked)
    {
        langSelected = $(this).val();
        $('.traduce-inner').addClass('traduce-inner-active');
        $('.traduce-inner-before-active').addClass('traduce-inner-before-unactive');

        var test = dataLangs.split('-');
        if(test.length === 1)
        {
            //appel de la fonction de recherche des traduction pour la langue séléctionnée
            getTraduceFromDBtoInputs(dataLangs, langSelected, pageName);
            AJAXloader(false);
        }
        else{
            //si test ,'est pas vide alors le datalang est en position: 0
            var dataLang = test[0];
            AJAXloader(true, '.traduce-bloc .loader-container');
            //supprime les anciens champs traduce-fields
            var dataLangText = dataLangs;
            var idTextarea = 'textarea-'+ dataLangText;
            $(this).parents('.modifs').find('.traduce-fields-text textarea').attr('id', idTextarea);
            //$('.traduce-fields-text textarea').attr('id', 'textarea-'+ dataLangText);
            //affiche de nouveau les champs traduce-fields
            //var html = '<div class="traduce-fields"><h4></h4><div class="text-block"><textarea class="textarea-tinymce" id="textarea-' + dataLang + '">text to avoid tinymce bug</textarea></div><div class="bt-valide-traduce" onclick="sendTraduce($(this))"><p>TO TRADUCE !</p></div></div>';
            //$('.traduce-bloc-text').find('.traduce-inner').html(html);
            //active de nouveau tinyMCE
            tinymce.init({selector: '.textarea-tinymce'});
            alert(tinymce.get(idTextarea));
            var tinyMCEInstance = tinymce.get(idTextarea);
            tinyMCEInstance.setContent('ok');
            AJAXloader(false);
        }
    }
});

//changer la langue de la page
//@override in js-landing;

//afficher l'input d'ajout de langue
var toogle = 0;
$('#add-lang').click(function(){
    if(toogle == 0){
        $('.langs-bloc ul').addClass('add-lang-active');
        $(this).text('-');
        toogle = 1;
    }
    else
    {
        $('.langs-bloc ul').removeClass('add-lang-active');
        $("input[name='new-lang']").css('border','1px solid grey');
        $(this).text('+');
        toogle = 0;
    }
});

//envoyer la nouvelle langue a ajouter
$('#add-lang-buttom').click(function(){
    if($("input[name='new-lang']").val() == '')
    {
        $("input[name='new-lang']").css('border','1px solid red');
    }
    else
    {
        toPost = $("input[name='new-lang']").val();
        $.post('inc/admin/langChange.php', {newlang: toPost}).complete(
            function(){
                $('.langs-bloc>ul li:last-child').after("<li class='lang-item' id='adminLand-"+ toPost +"'>"+ toPost +"</li>");
                window.location.href = 'index.php';
            })
    }
});

//annuler la redirection lor des clicks sur les bouttons submit
$("input[type='submit']").click(function () {
    return false;
});
$("#subfb-signup").click(function () {
    return false;
});
$("#fb-login").click(function () {
    return false;
});
$('#pwd-forgot').click(function(){
    return false;
});

//envoyer la traduction
$('.bt-valide-traduce').click(function(){
    alert('ok');
});

//tinyMCE
