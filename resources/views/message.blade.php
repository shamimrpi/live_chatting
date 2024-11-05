<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <style>
        .chat-container { max-width: 600px; margin: 50px auto; }
        .messages { border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: scroll; margin-bottom: 10px; }
        .message { padding: 5px; margin-bottom: 5px; border-bottom: 1px solid #ddd; }
        .message .author { font-weight: bold; }
        .message-form { display: flex; }
        .message-form input { flex: 1; padding: 5px; }
        .message-form button { padding: 5px; }
    </style>
</head>
<body>
<div class="chat-container">
    <h2>Live Chat</h2>
    <div class="messages" id="messages"></div>
    <form id="messageForm" class="message-form">
        <input type="text" id="messageInput" placeholder="Type your message..." required>
        <button type="submit"><i class="fa fa-paper-plane"></i> Send</button>
    </form>
</div>

<script>
    // Laravel Echo setup
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env('PUSHER_APP_KEY') }}',
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
    });

    // Listen for new messages on the 'chat' channel
    window.Echo.channel('chat')
        .listen('.message.sent', (e) => {
            addMessage(e.message, e.user);
        });

    // Add message to the chat
    function addMessage(message, user) {
        const messagesDiv = document.getElementById('messages');
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.innerHTML = `<span class="author">${user}: </span> ${message}`;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight; // Auto-scroll to the bottom
    }

    // Handle form submission to send a new message
    document.getElementById('messageForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value;

        // Send message via AJAX (assuming a route for sending messages exists)
        axios.post('/send-message', { message: message })
            .then(response => {
                messageInput.value = ''; // Clear input field
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
    });
</script>
</body>
</html>
