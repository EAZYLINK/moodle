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
 * @package course
 */

require_once(__DIR__ . '/../classes/submission_form.php'); 

 // Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('project sumbission');
$PAGE->set_heading('project_submission');
$PAGE->set_url("/local/esupervision/project/submit_project.php");

echo $OUTPUT->header();

$form = new local_esupervision_submission_form();

if($form->is_cancelled()) {
    \core\notification::add('Project submission cancelled', \core\output\notification::NOTIFY_WARNING);
    $form->display();
} else if ($form->get_data()) {
    \core\notification::add('Project submitted successfully!', \core\output\notification::NOTIFY_SUCCESS);
    $form->display();
    $data = $form->get_data();
    $filename = $form->get_new_filename('project_document');
    $fullpath = dirname(__FILE__);
    $override = true;
    $success = $form->save_file('project_document', $fullpath, $override);
    if(!$success){
      echo "failed to save file";
    }
  } else {
    $form->display();
  }

echo $OUTPUT->footer();