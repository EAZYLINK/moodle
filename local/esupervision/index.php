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


// Include necessary files
require_once(__DIR__ . '/../../config.php');
require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_esupervision'));
$PAGE->set_pagelayout('popup');
$PAGE->set_url('/local/esupervision/index.php');


echo $OUTPUT->header();

$topic = new stdClass();
$topic->title = 'Topics';
$topic->content = "view and manage topics";
$topic->url = new moodle_url("/local/esupervision/project/topics.php");

$proposal = new stdClass();
$proposal->title = "Proposals";
$proposal->content = "view and manage proposals";
$proposal->url = new moodle_url("/local/esupervision/project/proposals.php");

$report = new stdClass();
$report->title = 'Reports';
$report->content = "view and manage reports";
$report->url = new moodle_url("/local/esupervision/project/reports.php");

$grade = new stdClass();
$grade->title = 'Grades';
$grade->content = "view and manage grades";
$grade->url = new moodle_url("/local/esupervision/project/grading.php");


$name = $USER->firstname . " " . $USER->lastname;
$pages = array($topic, $proposal, $report, $grade);
$data = array(
    "name" => $name,
    "pages" => array_values($pages)
);
echo $OUTPUT->render_from_template("local_esupervision/view", $data);
echo $OUTPUT->footer();