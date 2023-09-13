<?php
require_once(__DIR__. '/../../../config.php');
require_login();

global $DB;
$messages = $DB->get_records('esupervision_chat_message');
if (empty($messages)) {
    header('Content-Type: application/json');
    echo json_encode(array());
    exit;
}
$chat_messages = array();
foreach ($messages as $message) {
    $chat_messages[] = array(
        'id' => $message->id,
        'sender' => $message->sender,
        'message' => $message->message,
        'timestamp' => $message->timestamp
    );
}
header('Content-Type: application/json');
echo json_encode($chat_messages);
