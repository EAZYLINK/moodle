<?php
// Include Moodle's core files

/**
 * TODO describe file chat
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
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
$PAGE->requires->js(new moodle_url('/local/esupervision/chat/script.js'));

echo $OUTPUT->footer();
