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
 * lib file.
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Include necessary files
require_once(__DIR__ . '/../../config.php');
function create_project($projectName, $projectDescription, $assignedSupervisor, $projectStatus) {
    global $DB;
    $table = 'esupervision_projects';
    // Create a new project record
    $newProject = new stdClass();
    $newProject->name = $projectName;
    $newProject->description = $projectDescription;
    $newProject->supervisor = $assignedSupervisor;
    $newProject->status = $projectStatus;
   $auto_increment_id= $DB->insert_record($table, $newProject);
    $fixed_prefix = 'PROJ-';
    $project_id = generate_project_id($fixed_prefix, $auto_increment_id);
    $newProject->project_id = $project_id;
    $newProject->id = $auto_increment_id;
    $DB->update_record($table, $newProject);
    return $newProject;
}

function generate_project_id($prefix, $auto_increment_id) {
    $padding_length = 3; // Set the desired length of the auto-incrementing number (e.g., 001, 002, ...)
    $auto_increment_number = str_pad($auto_increment_id, $padding_length, '0', STR_PAD_LEFT);
    return $prefix . $auto_increment_number;
}

function get_student_projects($userId) {
    global $DB;

    $table = 'mdl_esupervision_projects';

    // Retrieve projects based on the student's ID
    $sql = "SELECT * FROM $table WHERE id = {$userId}";
    $params = ['userId' => $userId];
    return $DB->get_records_sql($sql, $params);
}


// // Function to get projects assigned to the supervisor
function get_supervisor_assigned_students($supervisorId) {
    global $DB;
        $table = 'mdl_esupervision_projects';
        $table_supervisor = 'mdl_esupervision_supervisors';
        $sql1 = "SELECT * FROM $table_supervisor WHERE id = $supervisorId";
        $params = ['id' => $supervisorId];
        $supervisor = $DB->get_records_sql($sql1, $params);

        if (!empty($supervisor)) {
            $supervisor_name = $supervisor->name;
            $sql2 = "SELECT * FROM $table WHERE supervisor = $supervisor_name";
            $params = ['supervisor' => $supervisor_name];
            return $DB->get_records_sql($sql2, $params);
        } else {
            return [];
        }
}

function get_project_list() {
    global $DB;
    $table = 'esupervision_projects';
    return $DB->get_records($table);
}

function get_project($projectId) {
    global $DB;
    $table = 'mdl_esupervision_projects';

    // Retrieve the project based on the project ID
    $sql = "SELECT * FROM { $table } WHERE id = :projectId";
    $params = ['projectId' => $projectId];

    return $DB->get_record_sql($sql, $params);
}

function get_announcement($supervisor_name) {
    global $DB;
    $table = 'mdl_esupervision_announcement';
    $sql = "SELECT * FROM {$table} WHERE id = :supervisor_name";
    $params = ['supervisor_name' => $supervisor_name];
    return $DB->get_record_sql($sql, $params);
}

function local_esupervision_extends_navigation(global_navigation $navigation) {
    $navigation->add('student', new moodle_url('/local/esupervision/dashboard/student.php'));
    $navigation->add('supervisor', new moodle_url('/local/esupervision/dashboard/student.php'));
}