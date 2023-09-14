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
 * project submission form for project supervision
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package local_esupervision
 */
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../classes/submission_form.php'); 
require_login();

 // Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('project sumbission');
$PAGE->set_heading('project_submission');
$PAGE->set_url("/local/esupervision/project/submit_project.php");

echo $OUTPUT->header();

$mform = new local_esupervision_submission_form();

if($mform->is_cancelled()) {
    \core\notification::add('Project submission cancelled', \core\output\notification::NOTIFY_WARNING);
    $mform->display();
} else if ($mform->get_data()) {
    $mform->display();
    $data = $mform->get_data();
    
    $context = context_system::instance();
    $filename = $mform->get_new_filename('project_document');
} else {
    $mform->display();
  }

echo $OUTPUT->footer();