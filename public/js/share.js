/*
* VARS
 */
$shareinput = $('#share-input');
var imgsToPost = [];
var formData;


//share function imgs
function sharePostImgs(formData, event) {
    $.ajax({
        method: 'POST',
        url: "inc/Share/shareimgs.php",
        data: formData,
        processData: false,
        contentType: false
    }).done(function(msg){

    });
}

//delete img function
function deleteTmpImgs(imgName) {
    $.ajax({
        method: 'POST',
        url: "inc/Share/deleteimgtmp.php",
        data: {imgName: imgName}
    })
}

function deleteTmp() {
    $.ajax({
        url: "inc/Share/emptytmp.php"
    })
}

//shared postText
function share(text, imgToPost, dataPreviewStr) {
    $.ajax({
        method: 'POST',
        url: "inc/Timeline/post.php",
        data: {dataPreviewStr:dataPreviewStr, texte: text, imgsToPost:JSON.stringify(imgToPost)}
    }).done(function(msg){
        $('.timeline-container').addClass('active').prepend(msg);
        //resore linkArray (linkpreview.js)
        links = [];
        emptyShareBloc();
    });
}

//prepare share: rename img, update imgToPost array with img renamed, upload img renamed in TMP
function prepareImgShare(formData, event){
    $.ajax({
        method: 'POST',
        url: "inc/Share/prepareShareImgs.php",
        data: formData,
        processData: false,
        contentType: false
    }).done(function(msg){
        //rend activable le bt-send
        $('#share-post').parent().removeClass('active-mask');
        //methode utilisé car impossible de recuperer les valeurs en cas de retour JSON
        var pieces = msg.split('-');
        //VALID IMG
        if(pieces.length < 3)
        {
            var imgName = pieces[1];
            imgsToPost.push(imgName);
            //important pour la fonction de delete
            var containerName = "cont-" + imgName;
            var apercu_src = URL.createObjectURL(event.target.files[0]);
            var html = "<div class='img-share-container' id='"+ containerName +"' onClick='deletePhoto($(this))'><div class='img-container col-md-12'><img src='"+ apercu_src +"'><div class='delete-img-post'></div></div></div>";
            $('.img-share-bloc').append(html);
            imgSmoothLoading();
        }
        //INVALID IMG
        if(pieces.length >= 3)
        {
            //IMG INVALID
            //suppression de l'image du tableau d'images
            var removeItem = pieces[1];
            imgsToPost = $.grep(imgsToPost, function(value) {
                return value != removeItem;
            });
            //display du message d'erreur
            var locationError = ".img-share-input";
            var classBulleSpecial;
            var errorMsg = pieces[3];
            if(errorMsg == "error upload")
            {
                classBulleSpecial = ".upload-error";
            }
            if(errorMsg == "wrong types")
            {
                classBulleSpecial = ".error-type";
            }
            if(errorMsg == "wrong extension")
            {
                classBulleSpecial = ".ext-error"
            }
            if(errorMsg == "too large")
            {
                classBulleSpecial = ".size-error";
            }
            displayErrorBulleSpecial(locationError, classBulleSpecial);
        }

        AJAXloader(false, '#loader-imgs');
    });
}

//preview Link
function parse_link(){
    alert($('#share-input').html());
    if(!isValidURL($('#share-input').html()))
    {
        alert($('#share-input').html());
        alert('ok');
        alert('Please enter a valid url.');
        return false;
    }
    else
    {
        $('#atc_loading').show();
        $('#atc_url').html($('#share-input').html());

        $.post("inc/Urlpreview/fetch.php?url="+($('#share-input').html()), {}, function(response){

            //Set Content
            $('#atc_title').html(response.title);
            $('#atc_desc').html(response.description);
            $('#atc_price').html(response.price);

            $('#atc_total_images').html(response.total_images);

            $('#atc_images').html(' ');
            $.each(response.images, function (a, b)
            {
                $('#atc_images').append('<img src="'+b.img+'" width="100" id="'+(a+1)+'">');
            });
            $('#atc_images img').hide();

            //Flip Viewable Content
            $('#attach_content').fadeIn('slow');
            $('#atc_loading').hide();

            //Show first image
            $('img#1').fadeIn();
            $('#cur_image').val(1);
            $('#cur_image_num').html(1);

            // next image
            $('#next').unbind('click');
            $('#next').bind("click", function(){

                var total_images = parseInt($('#atc_total_images').html());
                if (total_images > 0)
                {
                    var index = $('#cur_image').val();
                    $('img#'+index).hide();
                    if(index < total_images)
                    {
                        new_index = parseInt(index)+parseInt(1);
                    }
                    else
                    {
                        new_index = 1;
                    }

                    $('#cur_image').val(new_index);
                    $('#cur_image_num').html(new_index);
                    $('img#'+new_index).show();
                }
            });

            // prev image
            $('#prev').unbind('click');
            $('#prev').bind("click", function(){

                var total_images = parseInt($('#atc_total_images').html());
                if (total_images > 0)
                {
                    var index = $('#cur_image').val();
                    $('img#'+index).hide();
                    if(index > 1)
                    {
                        new_index = parseInt(index)-parseInt(1);;
                    }
                    else
                    {
                        new_index = total_images;
                    }

                    $('#cur_image').val(new_index);
                    $('#cur_image_num').html(new_index);
                    $('img#'+new_index).show();
                }
            });
        });
    }
}

function isValidURL(url){
    var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    }
}

//prepare img upload
$('#upload-pic').on('change', function(event){
    AJAXloader(true, '#loader-imgs');

    //bt mask
    $('#share-post').parent().addClass('active-mask');

    //enlève les messages d'erreur precédents
    $('.bulle-error').removeClass('.bulle-error-special');

    //recupère l'imageFile
    files = $(this)[0].files[0];
    var imgName = files.name;
    imgName = imgName.replace(/\s|-/g,'');
    formData = new FormData();
    formData.append(imgName, files);

    //prepare share
    prepareImgShare(formData, event);
});

/*----------------------------------------------------------------------*\
 * controles sur le bloc texte avant soummission                        *
\*----------------------------------------------------------------------*/
$('#share-post').click(function () {
    if($shareinput.html() == "")
    {
        displayErrorBulle("#share-input");
        return false;
    }
    else
    {
        //old : pb replace aussi les br : var shareText = $shareinput.html().replace(/<(?!\/?a(?=>|\s.*>))\/?.*?>/gi, '');
        var shareText = $shareinput.html().replace(/<\/?(?!img)(?!br)(?!a)[a-z]+(?=[\s>])(?:[^>=]|=(?:'[^']*'|"[^"]*"|[^'"\s]*))*\s?\/?>/gi, '');
        share($.trim(shareText), imgsToPost, dataPreviewStr);
        return false;
    }
});

//#todo ameliorer cette fonction
function deleteEmptyContent(jQelement) {
    jQelement.find('p').each(function(){
        if($(this).text() == '' || $(this).text() == "<br>"){
            $(this).remove();
        }
        $(this).attr('style','');
    })

}
/*----------------------------------------------------------------------*\
 * delete image from descendant code generated by ading image function  *
\*----------------------------------------------------------------------*/
function deletePhoto(e) {
    var pieces = e.attr('id').split('-');
    var imgName = pieces[1];
    e.remove();
    deleteTmpImgs(imgName);
    var removeItem = imgName;
    imgsToPost = $.grep(imgsToPost, function(value) {
        return value != removeItem;
    });
}

//display error out
$('.img-share-bloc').on('click', '.img-share-input', function(e){
    $(this).removeClass('error-input-special');
    $(this).find('.bulle-error-special').removeClass('bulle-error-special');
});

//afficher le bloc d'agout d'images / videos
var toogleShare = 0;
$('#share-images').click(function(){
    //open
    if(toogleShare == 0)
    {
        $(this).addClass('active');
        $('.img-share-bloc').addClass('active');

        $('#share-videos').fadeOut();
        toogleShare = 1;
        return true;
    }
    //close
    if(toogleShare == 1)
    {
        $(this).removeClass('active');
        $('.img-share-bloc').removeClass('active');
        $('#share-videos').fadeIn();

        if(imgsToPost.length !== 0)
        {
            imgToPost =[];
            deleteTmp();
            $('.img-share-bloc .img-share-container').remove();
        }
        toogleShare = 0;
        return true;
    }
});

//displayErrorOut
$('#share-input').click(function () {
    if($(this).hasClass('error-input')){
        $('div').removeClass('error-input')
    }
});

//resize one photo appercus
$('.post-pic-bloc').each(function(){
    if($(this).find('.post-pic').length == 1)
    {
        resiezOnePhotoBloc($(this));
    }
});

//display images when click on the white shadow division
$('.timeline-container').on('click','.display-next-pic', function(e){
    //recupère la taille d'une des deux div contenat les photos
    var height = $(this).parent().find('.post-pic-container').height();

    //test si l'autre div n'est pas plus grande
    $(this).parent().find('.post-pic-container').each(function(){
        if($(this).height() > height)
        {
            height = $(this).height();
        }
    });
    //ajout de 5 px pour simuler une marge en bas
    height = height + 10;
    $(this).parents('.post-pic-bloc').css('max-height',height).css('height',height);
    $(this).addClass('display-next-pic-active');
});

//Link preview
// delete event
//$('#share-input').bind("keyup", parse_link);


