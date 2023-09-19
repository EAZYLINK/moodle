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
 * @package    local_esupervision
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 global $CFG, $DB, $PAGE, $USER;

require_once(__DIR__. '/../../../config.php');
require_once(__DIR__.'/../lib.php');
require_login();

// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('mydashboard');
// $PAGE->set_pagetype('my-index');
$PAGE->set_title('Student Dashboard');
$PAGE->set_heading('Dashboard');
$PAGE->set_url('/local/esupervision/dashboard/student.php');

echo $OUTPUT->header();

// Get the current user ID
$userId = $USER->id;
$CFG;
// $projects = get_student_projects($userId);
$data = array(
    'student_name' => fullname($USER)
);

    $navigation = array(
        array(
            'name' => 'Announcement',
            'link' => new moodle_url('/local/esupervision/dashboard/student.php')
        )
    );
    $general = new stdclass();
    $general->item1 = 'item1';
    $general->item2 = 'item2';
    $general->item3 = 'item3';
    echo $OUTPUT->render_from_template('local_esupervision/navigation', $general);
    // echo $OUTPUT->render_from_template('local_esupervision/view_announcement', $announcement);
// if (empty($projects)) {
//     echo '<p>No projects assigned.</p>';
// } else{
//     echo $OUTPUT->render_from_template('local_esupervision/student_dashboard', $data, $projects);
// }



echo $OUTPUT->footer();