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
 * TODO describe file student_list
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../lib.php');
require_login();

global $DB, $OUTPUT, $PAGE;

$PAGE->set_url(new moodle_url('/local/esupervision/project/studentlist.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Student List');
$PAGE->set_pagelayout('standard');

$upload_studentlist_form = new \local_esupervision\form\upload_studentlist_form();

echo $OUTPUT->header();

$upload_studentlist_form->display();
if ($upload_studentlist_form->is_cancelled()) {
    redirect($PAGE->url, 'form cancelled');
} elseif ($upload_studentlist_form->get_data()) {
    $data = $upload_studentlist_form->get_data();
    $content = $upload_studentlist_form->get_file_content('student_list');
    var_dump($content);
}

echo $OUTPUT->footer();