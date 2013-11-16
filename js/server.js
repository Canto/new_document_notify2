var io = require('socket.io').listen(3000);

// set log level
io.set("log level",0);

// set origins ex) test.com:* 형식
//io.set("origins","홈페이지주소:*"); 

// open the socket connection
io.sockets.on('connection', function (socket) {
  //console.log('클라이언트 정보:' + socket.id);
  
  //call back trigger to server
  socket.on('sendToServer',function(data){
    //console.log('Notify - Document Title : '+data.title+' / Document Srl :'+data.document_srl);
    //send to client
    io.sockets.emit('sendToClient',data);
  });
  
});