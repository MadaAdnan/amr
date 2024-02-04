@extends('dashboard.layouts.index')

@section('content')
    <style>
        body {
            margin: 0;
            padding-bottom: 3rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        #form {
            background: rgba(0, 0, 0, 0.15);
            padding: 0.25rem;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            height: 3rem;
            box-sizing: border-box;
            backdrop-filter: blur(10px);
        }

        #input {
            border: none;
            padding: 0 1rem;
            flex-grow: 1;
            border-radius: 2rem;
            margin: 0.25rem;
        }

        #input:focus {
            outline: none;
        }

        #form>button {
            background: #333;
            border: none;
            padding: 0 1rem;
            margin: 0.25rem;
            border-radius: 3px;
            outline: none;
            color: #fff;
        }

        #messages {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        #messages>li {
            padding: 0.5rem 1rem;
        }

        #messages>li:nth-child(odd) {
            background: #efefef;
        }
    </style>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
            </div>

        </div>
        <div class="card">
            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="row p-4">
                        <!-- Form to enter user ID -->
                        {{-- <form id="user-id-form">
                            <input type="text" id="userId" placeholder="User ID" value="{{$user->id}}">
                        </form> --}}

                        <!-- Input field for sending messages -->
                        {{-- <form id="private-message-form">
                            <input type="text" id="message" placeholder="Message">
                        </form> --}}

                        <!-- Display incoming private messages -->
                        {{-- <div id="private-messages"></div> --}}
                        <div>
                            <ul id="chat-messages"></ul>
                            <input type="text" id="message" placeholder="Type a message..." />
                            <button id="send">Send</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/socket.io/socket.io.js"></script>
    <script>
        // Initialize Socket.io and connect to the server
        const socket = io('http://192.168.1.40:3000'); // Replace with your Node.js server URL

        // Get the user's ID from the Blade template
        const recipientUserId = '{{ $user->id }}';

        // Function to display a received message
        function displayMessage(message) {
            const chatMessages = document.getElementById('chat-messages');
            const li = document.createElement('li');
            li.textContent = message;
            chatMessages.appendChild(li);
            window.scrollTo(0, document.body.scrollHeight);

        }

        // Event listener for the "Send" button
        document.getElementById('send').addEventListener('click', () => {
            const messageInput = document.getElementById('message');
            const message = messageInput.value.trim();

            if (message !== '') {
                // Emit a 'chat message' event to the server
                socket.emit('chat message', {
                    recipientUserId,
                    message
                });

                // Display the sent message in your own chat interface
                //   displayMessage(`You: ${message}`);

                // Clear the input field
                messageInput.value = '';
            }
        });

        // Listen for incoming messages from the server
        socket.on('chat message', (message) => {
            // Display the received message in the chat interface
            displayMessage(`Other User: ${message}`);
        });

        socket.on('sendChatToCLient', (message) => {
            console.log(message);

            displayMessage(`meeee: ${message}`);
        });
    </script>
@endsection
