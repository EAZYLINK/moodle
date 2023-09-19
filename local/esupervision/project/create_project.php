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
require_once(__DIR__ . '/../classes/create_project.php'); 
// require_login(); 


// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('incourse');
$PAGE->set_title('Create Project List');
$PAGE->set_heading('Create Project List');
$PAGE->set_url("/local/esupervision/project/create_project.php");
echo $OUTPUT->header();

$mform = new local_esupervision_create_project_form();
$project['project'] = array_values(get_project_list());

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $mform->get_data()) {
    $data = $mform->get_data();
    $newProjectId = create_project($data->title, $data->description, $data->supervisor_name, $data->student_name, $data->status);
    if(!$newProjectId) {
        \core\notification::add('Project creation failed', \core\output\notification::NOTIFY_ERROR);
        $mform->display();
        echo $OUTPUT->render_from_template('local_esupervision/project_list', $project);
    } else {
        \core\notification::add('Project created successfully', \core\output\notification::NOTIFY_SUCCESS);
        $mform->display();
    }
  } elseif($mform->is_cancelled()) {
    \core\notification::add('Project submission cancelled', \core\output\notification::NOTIFY_WARNING);
    $mform->display();
} else {
    $mform->display();
  }
echo $OUTPUT->render_from_template('local_esupervision/project_list', $project);
echo $OUTPUT->footer();