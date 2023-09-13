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
require_once(__DIR__. '/../lib.php');
require_login();

global $CFG, $DB, $PAGE, $USER;

// Check if the current user is a supervisor (you can customize the role name)
if (!is_siteadmin() && !has_capability('local/esupervision:supervisor', context_system::instance())) {
    // If not a supervisor, redirect to the home page or show an error message
    redirect(new moodle_url('/'));
}

$PAGE->set_pagelayout('mydashboard');
$PAGE->set_context(context_system::instance());
// $PAGE->set_title(get_string('supervisor_dashboard', 'local_esupervision'));
$PAGE->set_heading(get_string('supervisor_dashboard', 'local_esupervision'));
$PAGE->set_url('/local/esupervision/dashboard/supervisor.php');
$PAGE->navbar->add("dashboard");


echo $OUTPUT->header();

$supervisorId = $USER->id;
$projects = get_supervisor_assigned_students($supervisorId);
$supervisorName = $USER->firstname;
$data = array(
    'supervisor_name'=> $supervisorName
);
$project_list['assignedStudent'] = array_values($projects);
echo $OUTPUT->render_from_template('local_esupervision/supervisor', $data, $project_list);

echo $OUTPUT->footer();