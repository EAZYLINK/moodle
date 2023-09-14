<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * create project page
 *
 * @package    local_esupervision
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__. '/../../../config.php');
require_once(__DIR__ . '/../lib.php');
require_once(__DIR__ . '/../classes/project_topic.php'); 
// require_login(); 


// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Project Topic');
$PAGE->set_heading('Project Topic');
$PAGE->set_url("/local/esupervision/project/project_topic.php");

echo $OUTPUT->header();

$mform = new local_esupervision_project_topic_form();

if($mform->is_cancelled()) {
    \core\notification::add('Topic submission cancelled', \core\output\notification::NOTIFY_WARNING);
    $mform->display();
} else if ($mform->get_data()) {
    $data = $mform->get_data();
    $student_id = $USER->id;
    $data->student_id = $student_id;
    $topic_id = submit_project_topic($data);
    \core\notification::add('Topic submitted successfully!', \core\output\notification::NOTIFY_SUCCESS);
    $mform->display();
  } else {
    $mform->display();
  }

echo $OUTPUT->footer();