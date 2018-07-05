//show next 10 #todo DIPLICATAT cette fonction est un diplicatat de la fonction presente ds comlike.js, problème avec la difference des indexs envoyé, comprendre pourquoi et faire en sorte qu la fonction fonctionne pour les deux cas
$('#Permalink').on('click', '.bt-show-next', function () {
    AJAXloader(true, '#loader-show-nexts');
    var index = $(this).parents('.timeline-elem').index();

    //on compte le nombre de coms affichés pour savoir a partir duquel demarer
    var beginAt = $(this).parents('.timeline-elem').find('.post-comments').length;
    showNexts(index, beginAt);
});

$('.timeline-elem').addClass('selected');