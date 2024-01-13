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
$PAGE->set_url('/local/esupervision/grading.php');
$PAGE->set_context($context);
$PAGE->set_title('Project Scores System');
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add('Dashboard', new moodle_url('/local/esupervision/index.php'));
$PAGE->navbar->add('Project Score', new moodle_url('/local/esupervision/grading.php'));

$allowgrading = has_capability('local/esupervision:gradeproject', $context, $user = null, $doanything = true);
$viewgrading = has_capability('local/esupervision:viewgrade', $context, $user = null, $doanything = true);
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);

$data= get_all_users();
$studentlist = [];
foreach ($data as $value) {
    $fullname = $value->firstname .' '. $value->lastname;
    $studentlist[] = $fullname;
}
$grade_form = new \local_esupervision\form\grading_form(null, ['studentlist' => $studentlist]);

echo $OUTPUT->header();

if($allowgrading) {
    $grade_form->display();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if($grade_form->is_cancelled()) {
            redirect(
                $PAGE->url,
                'submission cancelled',
                null,
                \core\output\notification::NOTIFY_WARNING
            );
        } elseif($data = $grade_form->get_data()) {
            $name = $studentlist[$data->student];
            $user = get_user_by_name($name);
            $data->studentid = $user->id;
            $record_exists = record_exists('esupervision_grades', 'studentid', $data->studentid);
            if ($record_exists) {
                redirect(
                    $PAGE->url,
                    'Student already graded!',
                    null,
                    \core\output\notification::NOTIFY_ERROR
                );
            }
            $data->total = $data->attendance + $data->punctuality 
                            + $data->attentiontoinstruction + $data->turnover
                            + $data->attitudetowork + $data->resoursefulness;
            $grade = '';
            if ($data->total >= 24) {
                $grade = 'A';
            } elseif ($data->total >= 18 && $data->total < 24) {
                $grade = 'B';
            } elseif ($data->total >= 12 && $data->total < 18) {
                $grade = 'C';
            } elseif ($data->total >= 6 && $data->total < 12) {
                $grade = 'D';
            } elseif ($data->total < 6) {
                $grade = 'F';
            }
            $data->grade = $grade;
            $data->supervisorid = $USER->id;
            $gradeid = submit_grade($data);
            redirect(
                $PAGE->url,
                'submitted successfully!',
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }
    } elseif($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!$action) {
            $grades = get_students_grade($USER->id);
            foreach($grades as $grade) {
                $user = get_user_by_id($grade->studentid);
                $grade->studentname = $user->firstname . ' ' .$user->lastname;
            }
            $data = [
                'issupervisor' => true,
                'grades' => array_values($grades)
            ];
            echo $OUTPUT->render_from_template('local_esupervision/grades', $data);
        }
        elseif($action == 'delete' && $id) {
            $deleteid = delete_grade($id);
            if ($deleteid) {
                redirect(
                    $PAGE->url,
                    'grade deleted successfully!',
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            }
        }
    }
} 
if($viewgrading) {
    $grade = get_grade_by_studentid($USER->id);
    $data = [
        'isstudent' => true,
        'grade' => array_values($grade)
    ];
    echo $OUTPUT->render_from_template('local_esupervision/grades', $data);
}


echo $OUTPUT->footer();
