function sendTraduce(elem, newtraduce){
    var newTraduce;
    var dataLang;
    var langSelected;
    var pageName;

    if($(elem).parent().find('.traduction-field').is('input'))
    {
        newTraduce = $(elem).parent().find('.traduction-field').val();
        dataLang = $(elem).parent().attr('dataLang');
        langSelected = $(elem).parent().attr('lang');
        pageName = $(elem).parent().attr('page-name');
    }
    else{
        dataLang = $(elem).parent().attr('datalang');
        langSelected = $(elem).parent().attr('lang');
        //tinyMCE.get('textarea-'+ dataLang +'-'+ langSelected).setContent('ok') ;
        newTraduce =  tinyMCE.get('textarea-'+ dataLang +'-'+ langSelected).getContent();//window.tinyMCE.get(tinymce).getBody().textContent //$(elem).parent().find('#tinymce p').text();
        alert(newTraduce);
        pageName = $(elem).parent().attr('page-name');
    }
    $.ajax({
        method: 'POST',
        url: 'inc/admin/updateTraduceInput.php',
        data: {newTraduce: newTraduce, dataLang: dataLang, lang:langSelected, page: pageName}
    }).done(function(msg){
        $(elem).text('TRADUCED');
    });
};

//obtenir la traduction de l'element ds les inputs
function getTraduceFromDBtoInputs(dataLangs, langSelected, pageName){
    //si le datalang n'est pas un texte == le string text n'est pas dans le tableau, afficher l'edition avec les inputs
    pieces = dataLangs.split('-');
    if(($.inArray('text', pieces) == -1))
    {
        $.ajax({
            method: 'POST',
            url: 'inc/admin/getTraduce.php',
            data: {dataLangs: dataLangs, lang: langSelected, page: pageName},
            context:this,
            dataType: 'json'
        }).done(function(msg){
            $.each(msg, function (dataLang, traduce){
                var html = '<div class="traduce-fields" datalang="'+ dataLang +'" lang="'+ langSelected +'" page-name="'+ pageName +'"><h4>' + dataLang + '</h4><div class="text-block"><input class="traduction-field" type="text" value="'+ traduce[dataLang] +'"></div><div class="bt-valide-traduce" onclick="sendTraduce($(this))"><p>TO TRADUCE !</p></div></div>';
                $(".traduce-inner").append(html);
                AJAXloader(false);
            });
        });
    }
    else{
        /*alert('pas ok');*/
    }
}
