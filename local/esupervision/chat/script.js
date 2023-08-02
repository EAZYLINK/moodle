// JavaScript code for sending and receiving chat messages
$(document).ready(function () {
    // Function to display a new chat message
    function displayMessage(sender, message) {
      var chatMessages = $('#chat-messages');
      chatMessages.append('<div><strong>' + sender + ':</strong> ' + message + '</div>');
      chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
    }
  
    // Send button click event
    $('#send-button').on('click', function () {
      var messageInput = $('#message-input');
      var message = messageInput.val().trim();
  
      if (message !== '') {
        // AJAX request to send the message to the server
        $.ajax({
          url: '/local/esupervision/send_message.php',
          type: 'POST',
          data: { message: message },
          success: function (response) {
            // Message sent successfully, display it in the chat
            displayMessage('You', message);
            messageInput.val(''); // Clear the input field
          },
          error: function (xhr, status, error) {
            // Handle error
            console.error('Error sending message:', error);
          }
        });
      }
    });
  
    // Function to periodically check for new messages
    function checkForNewMessages() {
      // AJAX request to check for new messages from the server
      $.ajax({
        url: '/local/esupervision/chat/get_messages.php',
        type: 'POST',
        success: function (response) {
          // Process the response and display new messages
          for (var i = 0; i < response.length; i++) {
            var message = response[i].message;
            var sender = response[i].sender;
  
            // Display the message in the chat
            displayMessage(sender, message);
          }
        },
        error: function (xhr, status, error) {
          // Handle error
          console.error('Error fetching messages:', error);
        },
        complete: function () {
          // Schedule the next check after a delay
          setTimeout(checkForNewMessages, 5000); // Check every 5 seconds
        }
      });
    }
  
    // Start checking for new messages
    checkForNewMessages();
  });
  