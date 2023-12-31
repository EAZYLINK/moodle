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
 * Communication and collaboration file.
 *
 * @package    local_esupervision
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_login();

$context = context_system::instance();
$PAGE->set_url('/local/esupervision/project/grading.php');
$PAGE->set_context($context);
$PAGE->set_title('Project Scores');
$PAGE->set_pagelayout('standard');

$allowgrading = has_capability('local/esupervision:gradeproject', $context, $user = null, $doanything = true);

$grade_form = new \local_esupervision\form\grading_form();

echo $OUTPUT->header();

if ($allowgrading) {
    $grade_form->display();
}


echo $OUTPUT->footer();
