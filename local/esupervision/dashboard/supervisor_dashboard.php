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
 * handles AJAX requests to send a message
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 global $CFG, $DB, $PAGE, $USER;

require_once(__DIR__. '/../../../config.php');
require_once($CFG->libdir.'/admninlib.php');
require_login();

// Check if the current user is a supervisor (you can customize the role name)
if (!is_siteadmin() && !has_capability('local/esupervision:supervisor', context_system::instance())) {
    // If not a supervisor, redirect to the home page or show an error message
    redirect(new moodle_url('/'));
}

// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Supervisor Dashboard');
$PAGE->set_heading('Supervisor Dashboard');

echo $OUTPUT->header();

// Get the current user ID (supervisor ID)
$supervisorId = $USER->id;

// Retrieve projects assigned to the supervisor
$projects = get_supervisor_projects($supervisorId);

// Display supervisor dashboard
echo '<h1>Hello, ' . fullname($USER) . '!</h1>';
echo '<h2>Your Assigned Students:</h2>';

if (!empty($projects)) {
    echo '<ul>';
    foreach ($projects as $project) {
        echo '<li>';
        echo '<strong>' . $project->student_name . '</strong>';
        echo '<br>Project Description: ' . $project->description . '<br>';
        echo 'Project Status: ' . $project->status;
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No students assigned to your projects.</p>';
}

// Include the Moodle footer
echo $OUTPUT->footer();
?>