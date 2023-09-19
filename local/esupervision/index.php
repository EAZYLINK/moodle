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

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/esupervision/lib.php');
$PAGE->set_title(get_string('pluginname', 'local_esupervision'));
$PAGE->set_heading(get_string('pluginname', 'local_esupervision'));
$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('local-esupervision-index');
$PAGE->set_url('/local/esupervision/index.php');
$PAGE->navbar->add(get_string('supervisor_dashboard', 'local_esupervision'), new moodle_url('/local/esupervision/dashboard/supervisor.php'));
$PAGE->navbar->add(get_string('home_page', 'local_esupervision'), new moodle_url('/local/esupervision/index.php'));

echo $OUTPUT->header();
echo "<h2>Welcome to E-Supervisor!</h2>";
echo "<p>E-Supervisor is a plugin for Moodle that allows students to submit their project proposal and main project and supervisors to manage projects and provide feedback to students.</p>";
echo $OUTPUT->footer();

 