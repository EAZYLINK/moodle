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
$PAGE->set_url('/local/esupervision/groups.php');
$PAGE->set_context($context);
$PAGE->set_title('Project group');
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add('Dashboard', new moodle_url('/local/esupervision/index.php'));
$PAGE->navbar->add('Group', new moodle_url('/local/esupervision/groups.php'));
$PAGE->navbar->add('Group assign', new moodle_url('/local/esupervision/groupassign.php'));

$allowgrading = has_capability('local/esupervision:gradeproject', $context, $user = null, $doanything = true);
$viewgrading = has_capability('local/esupervision:viewgrade', $context, $user = null, $doanything = true);
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);

$data= get_all_users();
$groupdata = get_groups();
$userlist = [];
$groupnames = [];
foreach ($data as $value) {
    $fullname = $value->firstname .' '. $value->lastname;
    $userlist[] = $fullname;
}
foreach ($groupdata as $value) {
    $groupname = $value->name;
    $groupnames[] = $groupname;
}
$groupassign_form = new \local_esupervision\form\groupassign_form(null, ['groups' => $groupnames, 'students' => $userlist]);

echo $OUTPUT->header();


if($allowgrading) {
    $groupassign_form->display();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if($groupassign_form->is_cancelled()) {
            redirect(
                $_SERVER['REQUEST_URI'],
                'submission cancelled',
                null,
                \core\output\notification::NOTIFY_WARNING
            );
        } elseif($data = $groupassign_form->get_data()) {
            $name = $userlist[$data->student];
            $user = get_user_by_name($name);
            $data->studentid = $user->id;
            $groupname = $groupnames[$data->group];
            $group = get_group_by_name($groupname);
            $data->groupid = $group->id;
            $data->assignedby = $USER->id;
            $record_exists = record_exists('esupervision_students', 'studentid', $data->studentid);
            if ($record_exists) {
                redirect(
                    $_SERVER['REQUEST_URI'],
                    'Student already assigned!',
                    null,
                    \core\output\notification::NOTIFY_ERROR
                );
            }
            $assignid = assign_group($data);
            if ($assignid) {
                redirect(
                    $_SERVER['REQUEST_URI'],
                    'Student assigned successfully!',
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            }
        }
    } elseif($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!$action) {
            $students = get_group_students();
            foreach($students as $student) {
                $user = get_user_by_id($student->studentid);
                $group = get_group_by_id($student->groupid);
                $student->group = $group->name;
                $supervisor = get_user_by_id($group->supervisorid);
                $student->student = $user->firstname . ' ' .$user->lastname;
                $student->supervisor = $supervisor->firstname . ' ' .$supervisor->lastname;
                var_dump($student->groupid);
            }
            $data = [
                'issupervisor' => true,
                'students' => array_values($students)
            ];
            echo $OUTPUT->render_from_template('local_esupervision/students', $data);
        }
        elseif($action == 'delete' && $id) {
            $deleteid = unassign_student($id);
            if ($deleteid) {
                redirect(
                    $_SERVER['SCRIPT_NAME'],
                    'group deleted successfully!',
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            }
        }
    }
} 
if($viewgrading) {
    // $group = get_group_by_id($USER->id);
    // $data = [
    //     'isstudent' => true,
    //     'group' => array_values($group)
    // ];
    // echo $OUTPUT->render_from_template('local_esupervision/groups', $data);
}


echo $OUTPUT->footer();
