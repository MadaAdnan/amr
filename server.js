
const express = require("express");
const { Server } = require("socket.io");
const app = express();

app.get("/", (req, res) => {
  res.send("Hello World!");
});

const server = require("http").createServer(app);
const messagesByReservation = {};
// Create an object to store messages for each reservation.
const io = new Server(server, {
  cors: { origin: "*" },
});

io.on("connection", (socket) => {
  console.log('connected');

  socket.on("reservation", (reservationId) => {
    const channelName = "reservation_"+reservationId;
    socket.join(channelName);
    console.log('Received a custom event from channel:',channelName);
    if (!messagesByReservation[channelName]) {
      messagesByReservation[channelName] = [];
    }
    // Send the existing messages to the client.
    socket.emit("newMessage", messagesByReservation[channelName]);

    socket.on('sendMessage', (data) => {
      const channelName = 'reservation_'+data.reservationId;
      io.to(channelName).emit('newMessage', data.message);
      newMessage = data.message;
      messageSender = data.sender;
     
      messagesByReservation[channelName].push( {messageSender, newMessage});


    });

  });
  socket.on('disconnect', () => {
        console.log('A user disconnected');
      });
});

server.listen(3000, '192.168.1.40');