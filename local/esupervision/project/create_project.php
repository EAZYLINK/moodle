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
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__. '/../../../config.php');
require_once(__DIR__ . '/../lib.php');
// require_login(); 

// Check if the current user is an admin
if (!is_siteadmin()) {
    // If not an admin, redirect to the home page or show an error message
    redirect(new moodle_url('/'));
}

// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Create Project List');
$PAGE->set_heading('Create Project List');
$PAGE->set_url("/local/esupervision/project/create_project.php");

echo $OUTPUT->header();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data (replace with your form field names)
    $projectName = $_POST['project_name'];
    $projectDescription = $_POST['project_description'];
    $projectSupervisor = $_POST['project_supervisor'];
    $projectStatus = $_POST['project_status'];

    $newProjectId = create_project($projectName, $projectDescription, $projectSupervisor, $projectStatus);
    if ($newProjectId->project_id) {
        \core\notification::add('Project created successfully!', \core\output\notification::NOTIFY_SUCCESS);
    } else {
        \core\notification::add('Failed to create project. Please try again.', \core\output\notification::NOTIFY_ERROR);
    }
}

$title = array(
    "title"=> "Create Project"
);
echo $OUTPUT->render_from_template('local_esupervision/create_project', $title);
echo $OUTPUT->footer();