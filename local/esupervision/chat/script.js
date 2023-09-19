// JavaScript code for sending and receiving chat messages
$(document).ready(function () {
async function displayMessage(sender, message) {
      var chatMessages = $('#chat-container');
      chatMessages.append('<div><strong>' + sender + ':</strong> ' + message + '</div>');
      chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
    }
  
    $('#send-button').on('click', function () {
      var messageInput = $('#message-input');
      var message = messageInput.val().trim();

      if (message !== '') {
        $.ajax({
          url: '/local/esupervision/chat/send_message.php',
          type: 'POST',
          data: { message: message },
          success: async function (response) {
          await   displayMessage('You', message)
            messageInput.val('')
            console.log("sending message")
          },

          error: function (xhr, status, error) {
            console.error('Error sending message:', error);
          }
        });
        return true
      }
    });
  
    // Function to periodically check for new messages
    function checkForNewMessages() {
      // AJAX request to check for new messages from the server
      $.ajax({
        url: '/local/esupervision/chat/get_message.php',
        type: 'GET',
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
  