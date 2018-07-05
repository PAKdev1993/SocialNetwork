//connection a socket.io
$(function(){
    //var socket = io.connect('http://localhost:1337');

    $(document).on('keypress', 'div[data-elem="write-box-chatbox"]', function(e) {
        //e.preventDefault();

        //get message
        var messageHtml = $(this).html().replace(/<\/?(?!img)(?!br)(?!a)[a-z]+(?=[\s>])(?:[^>=]|=(?:'[^']*'|"[^"]*"|[^'"\s]*))*\s?\/?>/gi, '');

        //gte id conv
        $elem = $(this).closest($('.chat-box'));
        var tmp = $elem.attr('id');
        var pieces = tmp.split('-');
        var convId = pieces[1];

       
    });
});
