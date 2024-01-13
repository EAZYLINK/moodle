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

$allowgrading = has_capability('local/esupervision:gradeproject', $context, $user = null, $doanything = true);
$viewgrading = has_capability('local/esupervision:viewgrade', $context, $user = null, $doanything = true);
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);
$htmlstring = '<a href="/local/esupervision/groupassign.php" class="btn btn-primary mb-4">Assign student</a>';

$data= get_all_users();
$userlist = [];
foreach ($data as $value) {
    $fullname = $value->firstname .' '. $value->lastname;
    $userlist[] = $fullname;
}
$group_form = new \local_esupervision\form\group_form(null, ['userlist' => $userlist]);

echo $OUTPUT->header();

if($allowgrading) {
    echo $htmlstring;
    $group_form->display();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if($group_form->is_cancelled()) {
            redirect(
                $PAGE->url,
                'submission cancelled',
                null,
                \core\output\notification::NOTIFY_WARNING
            );
        } elseif($data = $group_form->get_data()) {
            $name = $userlist[$data->supervisor];
            $user = get_user_by_name($name);
            $data->supervisorid = $user->id;
            $record_exists = record_exists('esupervision_groups', 'supervisorid', $data->supervisorid);
            if ($record_exists) {
                redirect(
                    $PAGE->url,
                    'Student already graded!',
                    null,
                    \core\output\notification::NOTIFY_ERROR
                );
            }
            $data->supervisorid = $USER->id;
            ['text' => $content, 'format' => $format] = $data->description;
            $data->description = $content;
            $data->format = $format;
            $gradeid = submit_group($data);
            redirect(
                $PAGE->url,
                'submitted successfully!',
                null,
                \core\output\notification::NOTIFY_SUCCESS
            );
        }
    } elseif($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!$action) {
            $groups = get_groups();
            foreach($groups as $group) {
                $user = get_user_by_id($group->supervisorid);
                $group->supervisorname = $user->firstname . ' ' .$user->lastname;
            }
            $data = [
                'issupervisor' => true,
                'groups' => array_values($groups)
            ];
            echo $OUTPUT->render_from_template('local_esupervision/groups', $data);
        }
        elseif($action == 'delete' && $id) {
            $deleteid = delete_group($id);
            if ($deleteid) {
                redirect(
                    $PAGE->url,
                    'group deleted successfully!',
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            }
        }
    }
} 
if($viewgrading) {
    $student = get_group_student_by_studentid($USER->id);
    if ($student) {
        $group = get_group_by_id($student->groupid);
        $supervisor = get_user_by_id($group->supervisorid);
        $group->supervisor = $supervisor->firstname .' '. $supervisor->lastname;
        $data = [
            'isstudent' => true,
            'group' => $group
        ];
        echo $OUTPUT->render_from_template('local_esupervision/groups', $data);
    }
}


echo $OUTPUT->footer();
