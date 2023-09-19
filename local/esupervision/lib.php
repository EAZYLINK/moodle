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
 * @package    local_esupervision
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Include necessary files
require_once(__DIR__ . '/../../config.php');
function create_project($title, $description, $supervisor_name, $student_name, $status) {
    global $DB;
    $table = 'esupervision_projects';
    // Create a new project record
    $newProject = new stdClass();
    $newProject->title = $title;
    $newProject->description = $description;
    $newProject->supervisor_name = $supervisor_name;
    $newProject->student_name = $student_name;
    $newProject->status = $status;
    $newProjectId = $DB->insert_record($table, $newProject);
    return $newProjectId;
 
}

function submit_project_topic($topic, $student_name, $description) {
    global $DB;
    $table = 'esupervision_project_topics';
    // Create a new project record
    $newTopic = new stdClass();
    $newTopic->topic = $topic;
    $newTopic->student_name = $student_name;
    $newTopic->description = $description;
    $newTopicId = $DB->insert_record($table, $newTopic);
    return $newTopicId;
}

function get_project_list() {
    global $DB;
    $table = 'esupervision_projects';
    $project = $DB->get_records($table);
    return $project;
}

function get_project_by_keyword($keyword) {
    global $DB;
    $table = 'esupervision_projects';
    $project = $DB->get_records_sql("SELECT * FROM {esupervision_projects} WHERE title LIKE '%$keyword%' OR description LIKE '%$keyword%' OR supervisor_name LIKE '%$keyword%' OR student_name LIKE '%$keyword%'");
    return $project;
}

function grade_student($data) {
    $table = 'esupervision_project_scores';
    global $DB;
    $newScore = new stdClass();
    $newScore->student_id = $data->student_id;
    $newScore->attendance = $data->attendance;
    $newScore->punctuality = $data->punctuality;
    $newScore->attention = $data->attention_to_instruction;
    $newScore->turnover = $data->turnover_to_work;
    $newScore->resourcefulness = $data->resourcefulness;
    $newScore->attitude = $data->attitude_to_work;
    $newScore->comment = $data->comment;
    $newScore->timecreated = time();
    $newScore->timemodified = time();
    $newScoreId = $DB->insert_record($table, $newScore);
    return $newScoreId;
}
