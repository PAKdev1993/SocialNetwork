/*permet de load la fonction, checkForChange responsable
* du changement du titre du carousel
*/
$(document).ready(function() {
    checkForChanges();
});

//Slide lobys pg-4
var left = 0;
var right = 1;

$("#bt-left").click(function(){
    if(left !== 0)
    {
        $("#icons-loby-carousel ul").addClass("ul-active-right");
        $("#icons-loby-carousel ul").removeClass("ul-active-left");
        left = 0;
        right = 1;
    }
});

$("#bt-right").click(function(){
    if (right !== 0)
    {
        $("#icons-loby-carousel ul").addClass("ul-active-left");
        $("#icons-loby-carousel ul").removeClass("ul-active-right");
        left = 1;
        right = 0;
    }
});

//Scroll entre les pages
var animSpeed = 500;
$('.land-pg-suiv').click(function(){
    if($(this).attr('id') == "our-loby-pg-suiv")
    {
        var hauteur = $('#explore-loby').offset().top;
        $('html, body').animate({scrollTop: hauteur + "px"}, animSpeed , 'easeInOutQuint');
    }
    else
    {
        //recuperation de l'index de la page parent au clique pour le déplacement vers l'ancre suivante
        var array = $(this).parent().attr('id').split('-');
        var indexDuClick = parseInt(array[array.length - 1]);
        var indexToGo = indexDuClick + 1;
        var hauteur = $('#pg-'+indexToGo).offset().top;
        $('html, body').animate({scrollTop: hauteur + "px"}, animSpeed , 'easeInOutQuint');
    }
});

//Relancer la vidéo
$('.tovid').click(function() {
    var vid = $('#vid').get(0);
    vid.paused ? vid.play() : vid.pause();
    $('#vid').prop('muted', true);
});

//Carousel:
/*
* Interval between slides
*/
$('#our-team-carousel').carousel({
    interval: 7000 //7000
});

/*
* To change the carousel title our team -> About us - > Mission statement
*/
function checkForChanges()
{
    if ($('.active').hasClass('aboutus-item'))
    {
        $('.team-title').removeClass('active-h2');
        $('#aboutus-title').addClass('active-h2');
        setTimeout(checkForChanges, 500);
    }
    if ($('.active').hasClass('mission-item'))
    {
        $('.team-title').removeClass('active-h2');
        $('#mission-title').addClass('active-h2');
        setTimeout(checkForChanges, 500);
    }
    if ($('.active').hasClass('ourteam-item'))
    {
        $('.team-title').removeClass('active-h2');
        $('#our-team-title').addClass('active-h2');
        setTimeout(checkForChanges, 500);
    }
}

$('.carousel-indicators li').click(function(){
    checkForChanges();
});



//Apparition des documents
var btnsdoc = $("#legals-mentions span");
btnsdoc.on('click', function(e){
    if( $(this).hasClass('terms') )
    {
        $("#terms").addClass("doc-active");
    }
    if( $(this).hasClass('privacy') )
    {
        $("#Privacy").addClass("doc-active");
    }
    if( $(this).hasClass('codeofconduct') )
    {
        $("#CodeConduct").addClass("doc-active");
    }
});

$('.doc').click(function() {
    $(this).removeClass('doc-active');
});

//afficher le register form
$("#register").click(function(){
    //remonte le signup bloc
    $("#signup-bloc").addClass("signup-bloc-active");
    //baisse le singin form
    $("#log-form-bloc").addClass("log-form-bloc-active-mobile");
    //diminue la ytaille du logo
    $("#logo-we").addClass("logo-we-active");
    //diminue la taille du h1
    $("#logo-we>h1").addClass("h1-active");
});

//retablir la border des input après l'execution de la fonction displayError
$('#signup-form input').on('click', function(e){
    if($(this).attr('type') == 'text' || $(this).attr('type') == 'password')
    {
        $(this).css('border','1px solid rgba(204,204,204,1)');
    }
});

$('#log-form input').on('click', function(e){
    if($(this).attr('type') == 'text' || $(this).attr('type') == 'password')
    {
        $(this).css('border','1px solid rgba(204,204,204,1)');
    }
});

//affiche l'erreur d'inscription si elle est set par la session a l'ouverture de la page
$(function(){
    //errors
    if($('#signup-bloc .error li').length !== 0)
    {
        $('#signup-bloc .error').css('height','42px');
    }
    if($('#log-form .error li').length !== 0)
    {
        $('#log-form .error').css('height','42px');
    }
    //special
    if($('#log-form .special li').length !== 0)
    {
        $('#log-form .special').css('height','32px');
    }
});

//afficher le champ mail pour le login facebook
$('#subfb-signup').click(function(){

});

//effet de spread shadow au mouseleave sur les macarons lobbys slide 4
$('.icon-message h3').mouseenter(function(){
    $(this).parents('.icon').removeClass('active');
    //alert('enter');
});
$('.icon-message h3').mouseleave(function(){
    $(this).parents('.icon').addClass('active');
    //alert('leave');
});

//ERRORS ACTIONS
$('.error-input-container').click(function(){
   $(this).addClass('novisible');
});


