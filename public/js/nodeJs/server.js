/* global vars */
//creer un serv http
var http = require('http'); //sert a inclure des libs, telecharger des modules
var users = {};

//demarre le server
httpServer = http.createServer(function(request, response){
   response.end('Hello world');
});

//se connecter au serv, quel port ecouter
httpServer.listen(1337);

//relier le io au server et lui dire quoi ecouter
//request socket.io
var io = require('socket.io').listen(httpServer);

/* propre a chaque user */
//ecouter a quel moment on a une connection au server
//socket: socket de l'user en cours
io.sockets.on('connection', function(socket){
   
   //update user connected array on connect
   socket.on('login', function(data){
      //users[socket.id] = data.userid;
      users[socket.id] = data.userid;
   });

   //update user connected array on disconnect
   socket.on('disconnect', function(){
      delete users[socket.id];
   });

   //detecter newMessage et le renvoyer au client pour l'afficher
   socket.on('newMessage', function (messageDetails) {
      io.sockets.emit('newMessage', messageDetails);
   });

   //detecter l'activation d'une conv et le renvoyer aux participants pour display la conv
   //ObjResult: iduserto, iduserfrom, idconv
   socket.on('newConvToDisplayOnActivateConv', function (ObjResult) {
       var iduserto      = ObjResult.touserid;
       var iduserfrom    = ObjResult.fromuserid;
       var result = isConnected(iduserto);
       //if user is connected, send event to "client to": then "client to" get newConvToDisplay
       if(result.isConnected)
       {
            io.to(result.socketConnected).emit('newConvToDisplayOnActivateConv', ObjResult);
       }
       //if user is not connected, send back event to client
       else{
           var socketUserFrom = getSocketFromUserid(iduserfrom);
           io.to(socketUserFrom).emit('newConvToCreate', ObjResult);
       }
   });

   //detecter l'ajout d'un user a la conversation
   //ObjResult: iduserto, idconv
   socket.on('newConvToDisplayOnAddUser', function (ObjResult) {
       var iduserto = ObjResult.userid;
       var result = isConnected(iduserto);
       //if user is connected, send event to "client to": then "client to" get newConvToDisplay
       if(result.isConnected)
       {
            io.to(result.socketConnected).emit('newConvToDisplayOnAddUser', ObjResult);
       }
       //if user is not connected, send back event to client
       else{
           socket.emit('newConvToCreate', ObjResult);
       }
   });
    
   //detecter un kick d'user d'une conv
   //ObjResult: iduser to kick, idconv
   socket.on('deleteFromConv', function (ObjResult) {
       var iduser = ObjResult.iduser;
       var result = isConnected(iduser);
       //if user is connected, send event to "client to": then "client to" get newConvToDisplay
       if(result.isConnected)
       {
            io.to(result.socketConnected).emit('getDeletedFromConv', ObjResult);
       }
       //if user is not connected, send back event to client
       else{
           socket.emit('deleteUserFromConv', ObjResult);
       }
   });

   //detecter un read d'un user
   //ObjResult: convid : idconv, whoread : iduser, usersinconv : stringIdusers
   socket.on('readConv', function (ObjResult) {
       var usersinconv =    ObjResult.usersinconv;
       var arrIdUsers =     usersinconv.split(',');
       var arrLength =      arrIdUsers.length;
       for (var i = 0; i < arrLength; i++)
       {
           var result = isConnected(arrIdUsers[i]);
           //if user is connected, send event to clients connected
           if(result.isConnected)
           {
               io.to(result.socketConnected).emit('readedBy', ObjResult);
           }
       }
   });

   //detecter si un user est logged
   socket.on('isConnected', function (ObjResult) {
      io.sockets.emit('isConnectedResponse', ObjResult);
   });
});

function isConnected(iduser)
{
    var result = {};
    result.isConnected = false;
    for(var i in users)
    {
        if(users[i] == iduser)
        {
            result.isConnected = true;
            result.socketConnected = i;
        }
    }
    return result;
}

function getSocketFromUserid(iduserfrom) {
    for(var i in users)
    {
        if(users[i] == iduserfrom)
        {
            return i;
        }
    }
}
