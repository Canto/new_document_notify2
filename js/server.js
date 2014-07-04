var io = require('socket.io').listen('3000');
// open the socket connection
io.set('origins', '//홈페이지주소:*');
// ex) io.set('origins', '//naver.com:*');

io.on('connection', function (socket) {
    //call back trigger to server
    socket.on('sendToServer',function(data){
        //send to client
        io.emit('sendToClient',data);
    });

});