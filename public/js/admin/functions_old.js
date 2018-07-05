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

//obtenir la valeur du data-lang en bd
//recuperer les dataLang text ou normaux
//afficher dans le panneau de traduction ou un input ou un textarea
var oldDataTinyMce;
function getTraduceFromDB(dataLangs, langSelected, pageName){
    //recupère dans le tableau les dataLang classiques (varchar(50))
    var arrDataLangInput = [];
    //recupère dans le tableau les dataLang de type text (varchar(3500))
    var arrDataLangText = [];

    var arrayDataLang = dataLangs.split(' ');
    $.each(arrayDataLang, function(i, elem){
        var pieces = arrayDataLang[i].split('-');
        //le dataLang est un text (presence suffixe text dans pieces[1] donc pieces de taille 2
        if(pieces.length == 2)
        {
            arrDataLangText.push(pieces[0]);
        }
        //le dataLang n'est pas un text (pas de suffixe text dans pieces[1] donc pieces de taille 1
        else{
            arrDataLangInput.push(pieces[0]);
        }
    });
    $.ajax({
        method: 'POST',
        url: 'inc/admin/getTraduce.php',
        data: {dataLangs: dataLangs, lang: langSelected, page: pageName},
        context:this,
        dataType: 'json'
    }).done(function(msg){
        tinymce.remove('.textarea-tinyMce');
        $('.traduce-fields').remove();
        //$('.traduce-fields:last-child').remove();
        $.each(msg, function (dataLang, traduce) {
            //test si l'élément est un texte
            if ($.inArray(dataLang, arrDataLangText) != -1) {
                var html = '<div class="traduce-fields" dataLang="'+ dataLang +'" lang="'+ langSelected +'" page-name="'+ pageName +'"><h4>' + dataLang + '</h4><div class="text-block"><textarea id="textarea-'+ dataLang +'-'+ langSelected +'" class="textarea-tinyMce" rows="5" cols="15">' + traduce[dataLang] + '</textarea></div><div class="bt-valide-traduce" onclick="sendTraduce($(this))"><p>TRADUCE !</p></div></div>';
                $(".traduce-inner").append(html);
                tinymce.init({selector: '.textarea-tinyMce'});
            }
            //sinon
            else {
                var html = '<div class="traduce-fields" datalang="'+ dataLang +'" lang="'+ langSelected +'" page-name="'+ pageName +'"><h4>' + dataLang + '</h4><div class="text-block"><input class="traduction-field" type="text" value="'+ traduce[dataLang] +'"></div><div class="bt-valide-traduce" onclick="sendTraduce($(this))"><p>TO TRADUCE !</p></div></div>';
                $(".traduce-inner").append(html);
            }
            AJAXloader(false);
        });
    });
}
