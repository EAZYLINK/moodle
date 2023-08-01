<?php
// Include Moodle's core files
require_once(__DIR__ . '/../../../config.php');
require_login();

// Set up the page context and theme
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Chat Interface');
$PAGE->set_heading('Chat Interface');
$PAGE->set_url($CFG->wwwroot . '/local/esupervision/chat/chat.php');

// Output the header
echo '<link rel="stylesheet" type="text/css" href="../styles.css">';
echo $OUTPUT->header();
$PAGE->requires->js(new moodle_url('/local/esupervision/chat/script.js'));
?>

<div id="chat-container">
  <div id="chat-messages"></div>
  <div id="chat-input">
    <input type="text" id="message-input" placeholder="Type your message...">
    <button id="send-button">Send</button>
  </div>
</div>

<?php

// Output the footer
echo $OUTPUT->footer();
?>
