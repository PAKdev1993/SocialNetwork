/*----------------------------------------------------------------------*\
 * DETECT LINK IN CONTENTEDITABLE DIV                                   *
\*----------------------------------------------------------------------*/
var links = [];
//var divContentEditable = jQuery('div[contenteditable="true"]');
$(document).on('keyup', 'div[contenteditable="true"]', function() {
    checkForLinks(jQuery(this), false, links);
    saveSelection();
});

/*old
 jQuery('div[contenteditable="true"]').keyup(function() {
 checkForLinks(jQuery(this));
 saveSelection();
 }).blur(function() {
 checkForLinks(jQuery(this), true);
 });
*/

function checkForLinks(elem, isBlur, linkArray) {
    var text = elem.html();
    var urlCheckString = '((?:http[s]?:\\/\\/(?:www\\.)?|www\\.){1}(?:[0-9A-Za-z\\-%_]+\\.)+[a-zA-Z]{2,}(?::[0-9]+)?(?:(?:/[0-9A-Za-z\\-\\.%_]*)+)?(?:\\?(?:[0-9A-Za-z\\-\\.%_]+(?:=[0-9A-Za-z\\-\\.%_\\+]*)?)?(?:&amp;(?:[0-9A-Za-z\\-\\.%_]+(?:=[0-9A-Za-z\\-\\.%_\\+]*)?)?)*)?(?:#[0-9A-Za-z\\-\\.%_\\+=\\?&;]*)?)';
    //full url
    if(isBlur) {
        var regex = new RegExp(urlCheckString, 'gi');
    } else {
        var regex = new RegExp(urlCheckString + '(?!<br>)[^0-9A-Za-z\-\.%_\+\/=&\?;#]', 'gi');
    }

    //console.log("Text: " + text);
    var newText = text;
    newText = newText.replace(new RegExp('<a class="link" target="_blank">([^<]*)</a>', 'gi'), '$1');
    newText = newText.replace(new RegExp('<a class="link" target="_blank">([^<]*<br>[^<]*)</p>', 'gi'), '$1');
    newText = newText.replace(new RegExp('<p></p>', 'gi'), '');
    newText = newText.replace(new RegExp('<a[^>]*>([^<]*)</a>', 'gi'), '$1');
    //change back the IE	autochange
    //console.log("newText: " + newText);

    newText = newText.replace(regex, function(match, link, offset, string) {
        var trailingChar = match.substr(link.length);
        if($.inArray(link, linkArray) == -1) {
            linkArray.push(link);
            preview(linkArray[linkArray.length - 1], elem);
        }
        //put http at the begining of link if nescessary
        if (link.indexOf("http") == -1)
        {
            return '<a class="link" href="'+ link.replace("www.","http://www.") +'" target="_blank">' + link + '</a>' + trailingChar;
        }
        else{
            return '<a class="link" href="'+ link +'" target="_blank">' + link + '</a>' + trailingChar;
        }
    });

    //console.log("newTextafter: " + newText);
    if(text.localeCompare(newText) != 0) {
        elem.html(newText);
    }
}

function linkDetected(linkString){
    //divContentEditable.append('<pre>' + linkString + '</pre>');
}

var savedRange, isInFocus;
function saveSelection() {
    if(window.getSelection)//non IE Browsers
    {
        savedRange = window.getSelection().getRangeAt(0);
    } else if(document.selection)//IE
    {
        savedRange = document.selection.createRange();
    }
}

function restoreSelection() {
    isInFocus = true;
    document.getElementById("share-input").focus();
    if(savedRange != null) {
        if(window.getSelection)//non IE and there is already a selection
        {
            var s = window.getSelection();
            if(s.rangeCount > 0)
                s.removeAllRanges();
            s.addRange(savedRange);
        } else if(document.createRange)//non IE and no selection
        {
            window.getSelection().addRange(savedRange);
        } else if(document.selection)//IE
        {
            savedRange.select();
        }
    }
}

/*----------------------------------------------------------------------*\
 * PREPARE LINK PREVIEW                                                 *
\*----------------------------------------------------------------------*/
//Params link preview, this parameters are send to share function, to post--
var site_name;
var image_link;     var imgPertinence = 0;
var description;    var descPertinence = 0;
var title;          var titlePertinence = 0;
var type_link;
var link;
var empty;
var dataPreviewStr = ''; //variable sent to share function, to post
//--------------------------------------------------------------------------
function preview(url, elem){
    //init variables
    site_name =     '';
    image_link =    '';  imgPertinence = 0;
    description =   '';  descPertinence = 0;
    title =         '';  titlePertinence = 0;
    type_link =     'default';
    link =          getValidLinkFromUrl(url);
    empty =         0;

    //get page meta
    $.getJSON("http://query.yahooapis.com/v1/public/yql?" +
        "q=SELECT%20*%20FROM%20html%20WHERE%20url=%27" + encodeURIComponent(url) +
        "%27%20AND%20xpath=%27descendant-or-self::meta%27&format=json&callback=?",
    function (data) {
        //on definis si la preview est celle de l'edition de post ou non
        var post = 0;
        var comment = 0;
        if(elem.hasClass('post-text')) {
            post = 1;
        }
        if(elem.hasClass('comment-input')){
            comment = 1;
        }

        //la preview est disponnible
        if (data.query.results){
            //get sitename
            site_name = getSiteName(url);

            //get other variables and other pertinence
            var metaArray = data.query.results.meta;
            var length = metaArray.length;
            for (var i = 0; i < length; i++){
                imgPertinence =  getImagePertinence(metaArray[i]);
                descPertinence = getDescPertinence(metaArray[i]);
                titlePertinence = getTitlePertinence(metaArray[i]);
            }

            //cas ou le tite n'a pas été trouve
            if(titlePertinence !== 1){
                //get page title
                $.getJSON("http://query.yahooapis.com/v1/public/yql?" +
                    "q=select%20*%20from%20html%20where%20url%3D%22" + encodeURIComponent(url) +
                    "%22%20and%0A%20%20%20%20%20%20xpath%3D'%2F%2Ftitle'&format=json&callback=?",
                    function (data /*, title*/){
                        //get site title
                        if(data.query.results.title){
                            title = data.query.results.title;
                            titlePertinence = 2;
                        }
                    });
            }

            //prepare preview display
            //AJAXloader(true, '#loader-share-preview');
            displayPreview(link, type_link, site_name, title, image_link, description, empty, post, comment, elem);
        }
        //la preview n'est pas disponnible
        else{
            empty = 1;
            displayPreview(link, type_link, site_name, title, image_link, description, empty, post, comment, elem);
        }
    });
}
//transform url when the url begin with www.
function getValidLinkFromUrl(url){
    var res = url.substring(0, 3);
    if(res == 'www'){
        return url.replace('www.','http://www.');
    }
    else{
        return url;
    }
}

//assign value for ajax callback
function getTitle(){
    return title;
}

//defini la pertinence et le lien de l'image
function getSiteName(url){
    var domain;
    //find & remove protocol (http, ftp, etc.) and get domain
    if (url.indexOf("://") > -1) {
        domain = url.split('/')[2];
    }
    else {
        domain = url.split('/')[0];
    }

    //find & remove port number
    domain = domain.split(':')[0];

    return domain;
}

//defini la pertinence et le lien de l'image
function getImagePertinence(meta){
    if(imgPertinence == 1){
        return imgPertinence;
    }
    else{
        //Traitement de la meta
        if(linkIs('youtube')){
            type_link = 'youtube';
            if(meta.hasOwnProperty("property") && meta.property === "og:video:secure_url"){
                image_link = meta.content;
                return 1;
            }
            else{
                return 0;
            }
        }
        if(linkIs('twitch')){
            type_link = 'twitch';
            var pieces = link.split('/');
            image_link = pieces[pieces.length-1];
            return 1;
        }
        if(meta.hasOwnProperty("property") && meta.property === "og:image"){
            image_link = meta.content;
            imgPertinence = 1;
        }
        if(meta.hasOwnProperty("name") && meta.name === "twitter:image"){
            image_link = meta.content;
            imgPertinence = 2;
        }
        if(meta.hasOwnProperty("itemprop") && meta.itemprop === "image"){
            image_link = meta.content;
            imgPertinence = 3;
        }

        //Traitement post found
        if(imgPertinence != 0){
            //image_link = image_link.replace(/http:|https:/gi, '');
            //cas ou la src de l'image est relative et demare par un /
            if(image_link.charAt(0) == '/')
            {
                image_link = 'http://' + site_name + image_link;
            }
            //cas de battlefield.com ou la src des images commencent par http:https
            image_link = image_link.replace("http:https","https");
            image_link = image_link.replace("http:http","https");
        }
        return imgPertinence;
    }
}

//defini la pertinence et le lien de la description
function getDescPertinence(meta){
    if(descPertinence == 1){
        return descPertinence;
    }
    else{
        if(meta.hasOwnProperty("property") && meta.property === "og:description"){
            description = meta.content;
            return 1;
        }
        if(meta.hasOwnProperty("name") && meta.name === "description"){
            description = meta.content;
            return 2;
        }
        if(meta.hasOwnProperty("twitter:description") && meta.name === "twitter:description"){
            description = meta.content;
            return 3;
        }
        else{
            return descPertinence;
        }
    }
}

//defini la pertinence et le texte du titre
function getTitlePertinence(meta){
    if(titlePertinence == 1){
        return titlePertinence;
    }
    else{
        if(meta.hasOwnProperty("property") && meta.property === "og:title"){
            if(meta.content !== undefined){
                title = meta.content;
                return 1;
            }
            else{
                return 0;
            }
        }
        else{
            return titlePertinence;
        }
    }
}

//define if site is specific
function linkIs(name){
    var regYoutube = "^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/(watch).+$"; //Match only youtube videos
    var regTwitch = "^(https?\:\/\/)?(www\.)?(twitch\.tv)\/.+\/(v)\/.+$"; //Match only youtube videos
    if(name == 'youtube'){
        return link.match(regYoutube);
    }
    if(name == 'twitch'){
        return link.match(regTwitch);
    }
}

/*----------------------------------------------------------------------*\
 * DISPLAY PREVIEW                                                 *
\*----------------------------------------------------------------------*/
function displayPreview(link, type_link, site_name, title, image_link, description, empty, post, comment, elem){
    if(image_link == '')
    {
        image_link = 'empty'
    }
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/showLinkPreview.php",
        data: {link:link, type:type_link, sitename:site_name, title:title, imageLink:image_link, description:description, empty:empty, post:post}
    }).done(function(msg){
        if(msg != 'err')
        {
            AJAXloader(false, '#loader-share-preview');

            //si l'emement est le bloc share: cas du post
            var img;
            if(post == 0 && comment == 0){
                //display share bloc preview
                $('#preview-content').html(msg);
                img = $('#preview-content').find('img');
                //add preview parameters to formdata to sent to post function
                var dataPreviewArray = [link, type_link, site_name, title, image_link, description, empty];
                dataPreviewStr = dataPreviewArray.join('&&');
            }
            //si l'element est le post-text: cs modification de post
            if(post == 1 && comment == 0){
                elem.closest('.post-body').find('.preview-bloc').html(msg);
                img = elem.closest('.post-body').find('.preview-bloc').find('img');
            }
            if(comment == 1){
                //
            }
            //image rendering
            imgSmoothLoading();
            centerImgVerticaly(img);
        }
        else{
            window.location.href = 'index.php?p=home';
        }
    });
}

/*----------------------------------------------------------------------*\
 * DELETE LINK PREVIEW                                                  *
\*----------------------------------------------------------------------*/
$('.body').on('click', 'div[data-action="delete-preview"]', function(){
    $(this).closest($('.preview-container')).html('');
    image_link =    '';
    description =   '';
    title =         '';
    type_link =     'default';
    link =          '';
    dataPreviewStr = '';
});
