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

require_once(__DIR__. '/../../../config.php');
require_once('lib.php');
require_login();

// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Student Dashboard');
$PAGE->set_heading('Student Dashboard');

// Include the Moodle header
echo $OUTPUT->header();

// Get the current user ID
$userId = $USER->id;

// Retrieve student-specific data (replace with your own logic)
// For example, get projects assigned to the student
$projects = get_student_projects($userId);

// Display student dashboard
echo '<h1>Hello, ' . fullname($USER) . '!</h1>';
echo '<h2>Your Projects:</h2>';

if (!empty($projects)) {
    echo '<ul>';
    foreach ($projects as $project) {
        echo '<li>';
        echo '<strong>' . $project->name . '</strong>';
        echo '<br>Description: ' . $project->description . '<br>';
        echo 'Assigned Supervisor: ' . $project->supervisor . '<br>';
        echo 'Status: ' . $project->status;
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No projects assigned.</p>';
}

// Include the Moodle footer
echo $OUTPUT->footer();
?>