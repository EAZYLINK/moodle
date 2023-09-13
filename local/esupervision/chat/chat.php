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

echo $OUTPUT->header();
$data = array(
    'name'=> "Chat Forum"
);
echo $OUTPUT->render_from_template('local_esupervision/chat', $data);
$PAGE->requires->js(new moodle_url('/local/esupervision/chat/script.js'));

echo $OUTPUT->footer();
