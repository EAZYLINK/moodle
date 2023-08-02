<?php
// Include Moodle's core files
require_once('../../config.php');
require_login();

// Your code to retrieve chat messages from the database goes here
// Replace 'your_plugin' with the actual name of your local plugin
global $DB;
$messages = $DB->get_records('esupervision_chat_messages');

// Prepare an array to store the retrieved messages
$chat_messages = array();
foreach ($messages as $message) {
    $chat_messages[] = array(
        'id' => $message->id,
        'sender' => $message->sender,
        'message' => $message->message,
        'timestamp' => $message->timestamp
    );
}

// Return the chat messages as a JSON response
header('Content-Type: application/json');
echo json_encode($chat_messages);
