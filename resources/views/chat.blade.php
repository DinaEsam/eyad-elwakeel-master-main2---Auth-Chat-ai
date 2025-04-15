<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e5ddd5;
            margin: 0;
            display: flex;
            height: 100vh;
        }
        #sidebar {
            width: 300px;
            border-right: 1px solid #ccc;
            background-color: #ffffff;
            padding: 10px;
            overflow-y: auto;
        }
        #chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }
        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #ffffff;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 10px;
            max-width: 75%;
            position: relative;
        }
        .message .sender {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message.sent {
            background-color: #dcf8c6;
            align-self: flex-end;
            margin-left: auto;
        }
        .message.received {
            background-color: #f1f0f0;
            align-self: flex-start;
        }
        #message-input-container {
            display: flex;
            margin-top: 10px;
        }
        #message-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
        }
        #send-message {
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            background-color: #25d366;
            color: white;
            cursor: pointer;
        }
        #send-message:hover {
            background-color: #128C7E;
        }
        #chat-list {
            list-style: none;
            padding: 0;
        }
        #chat-list li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ccc;
        }
        #chat-list li:hover {
            background-color: #f0f0f0;
        }
        #search-container {
            margin-bottom: 10px;
        }
        #search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
        }
        #search-results {
            list-style: none;
            padding: 0;
        }
        #search-results li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ccc;
        }
        #search-results li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<div id="sidebar">
    <h3>Your Chats</h3>
    <div id="search-container">
        <input type="text" id="search-input" placeholder="Search for users...">
        <ul id="search-results"></ul>
    </div>
    <ul id="chat-list"></ul>
</div>

<div id="chat-container">
    <div id="chat-box"></div>
    <div id="message-input-container">
        <input type="text" id="message-input" placeholder="Type your message...">
        <button id="send-message">Send</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let chatId = null;

    // Function to load user chats
    function loadChats() {
        $.ajax({
            url: `/api/chats`,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            },
            success: function(response) {
                $('#chat-list').empty();
                response.chats.forEach(chat => {
                    $('#chat-list').append(`<li data-id="${chat.id}">${chat.receiver.name}</li>`);
                });

                // Attach click handler to chat items
                $('#chat-list li').click(function() {
                    chatId = $(this).data('id');
                    loadMessages();
                });
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    }

    // Function to search for users
    $('#search-input').on('input', function() {
        const searchTerm = $(this).val();

        if (searchTerm.length > 0) {
            $.ajax({
                url: `/api/search-users?search_term=${searchTerm}`,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
                },
                success: function(response) {
                    $('#search-results').empty();
                    response.users.forEach(user => {
                        $('#search-results').append(`<li data-id="${user.id}">${user.name}</li>`);
                    });

                    // Attach click handler to search results
                    $('#search-results li').click(function() {
                        const userId = $(this).data('id');
                        startChat(userId);
                    });
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        } else {
            $('#search-results').empty(); // Clear results if search term is empty
        }
    });

    // Function to start a new chat
    function startChat(userId) {
        $.ajax({
            url: '/api/chat/start',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            },
            data: JSON.stringify({ receiver_id: userId }),
            success: function(response) {
                alert(response.message);
                loadChats(); // Reload chats to include the new chat
                $('#search-input').val(''); // Clear search input
                $('#search-results').empty(); // Clear search results
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    }

    // Function to load chat messages (History)
    function loadMessages() {
        if (!chatId) return;

        $.ajax({
            url: `/api/chat/${chatId}/messages`,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            },
            success: function(response) {
                $('#chat-box').empty();
                response.messages.forEach(message => {
                    const messageClass = message.sender.id === localStorage.getItem('user_id') ? 'sent' : 'received';
                    $('#chat-box').append(`
                        <div class="message ${messageClass}" data-id="${message.id}">
                            <span class="sender">${message.sender.name}:</span> ${message.message}
                            <button class="edit-message">Edit</button>
                            <button class="delete-message">Delete</button>
                        </div>
                    `);
                });

                // Attach click handlers to the new buttons
                $('.edit-message').click(function(event) {
                    event.stopPropagation(); // Prevent triggering the chat click
                    const messageId = $(this).parent().data('id');
                    editMessage(messageId);
                });

                $('.delete-message').click(function(event) {
                    event.stopPropagation(); // Prevent triggering the chat click
                    const messageId = $(this).parent().data('id');
                    deleteMessage(messageId);
                });
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    }

    // Function to send a message
    $('#send-message').click(function() {
        const message = $('#message-input').val();

        $.ajax({
            url: '/api/chat/send',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            },
            data: JSON.stringify({ chat_id: chatId, message: message }),
            success: function(response) {
                $('#message-input').val('');
                loadMessages();
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

    // Function to edit a message
    function editMessage(messageId) {
        const newMessage = prompt("Edit your message:");

        if (newMessage) {
            $.ajax({
                url: `/api/chat/message/${messageId}`,
                method: 'PUT',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
                },
                data: JSON.stringify({ message: newMessage }),
                success: function(response) {
                    alert(response.message);
                    loadMessages(); // Reload messages to reflect the changes
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        }
    }

    // Function to delete a message
    function deleteMessage(messageId) {
        if (confirm("Are you sure you want to delete this message?")) {
            $.ajax({
                url: `/api/chat/message/${messageId}`,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
                },
                success: function(response) {
                    alert(response.message);
                    loadMessages(); // Reload messages to reflect the deletion
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        }
    }

    // Load chats on page load
    $(document).ready(function() {
        loadChats();
    });
</script>

</body>
</html>