var io = require('socket.io').listen('3000');
// open the socket connection
io.on('connection', function (socket) {
    //call back trigger to server
    socket.on('sendToServer',function(data){
        //send to client
        socket.emit('sendToClient',data);
    });

});