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

require_once(__DIR__ .'/../../../config.php');
require_once(__DIR__ .'/../classes/grading_form.php');
require_once(__DIR__ .'/../lib.php');
$PAGE->set_url('/local/esupervision/project/grading.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Grading Form');
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();

$grade_form = new local_esupervision_grading_form();
if ($grade_form->is_cancelled()) {
    \core\notification::add(get_string('cancelled', 'local_esupervision'), \core\outpu\notification::NOTIFY_ERROR);
    $grade_form->display();
} else if ($fromform = $grade_form->get_data()) {
    $data = $grade_form->get_data();
    $newScoreId = grade_student($data);
    if (!$newScoreId) {
        \core\notification::add(get_string('fail', 'local_esupervision'), \core\output\notification::NOTIFY_ERROR);
    } else {
        \core\notification::add(get_string('success', 'local_esupervision'), \core\output\notification::NOTIFY_SUCCESS);
    }
    $grade_form->display();
} else {
    $grade_form->display();
}
echo $OUTPUT->footer();
