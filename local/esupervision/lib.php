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
require_once($CFG->dirroot . '/mod/chat/lib.php');

function submit_topic($data)
{
    global $DB;
    $table = 'esupervision_topics';
    // Create a new project record
    $newTopic = new stdClass();
    $newTopic->topic = $data->topic;
    $newTopic->studentid = $data->studentid;
    $newTopic->groupid = $data->groupid;
    $newTopic->topic = $data->topic;
    $newTopic->description = $data->description;
    $newTopic->format = $data->format;
    $newTopic->status = 'pending';
    $newTopic->timecreated = date('Y-m-d H:i:s');
    $newTopic->timemodified = date('Y-m-d H:i:s');
    $newTopicId = $DB->insert_record($table, $newTopic);
    return $newTopicId;
}

function get_topic_by_studentid($id)
{
    global $DB;
    $table = 'esupervision_topics';
    $sql = 'SELECT * FROM {esupervision_topics} WHERE studentid = ' . $id;
    $params = array($id);
    $topic = $DB->get_records_sql($sql, $params);
    // $topic = $DB->get_record($table, array('studentid' => $id));
    return $topic;
}

function get_topic_by_topicid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_topics} WHERE id = ' . $id;
    $params = array($id);
    $topic = $DB->get_record_sql($sql, $params);
    return $topic;
}

function update_topic($topic)
{
    global $DB;
    $table = 'esupervision_topics';
    $topic->status = 'pending';
    $topic->timemodified = date('Y-m-d H:i:s');
    $update_topicid = $DB->update_record($table, $topic);
    return $update_topicid;
}

function delete_topic($id)
{
    global $DB;
    $table = 'esupervision_topics';
    $delete_topicid = $DB->delete_records($table, array('id' => $id));
    return $delete_topicid;
}



function submit_report($data)
{
    global $DB;
    $table = 'esupervision_reports';
    $newReport = new stdClass();
    $newReport->title = $data->title;
    $newReport->studentid = $data->studentid;
    $newReport->groupid = $data->groupid;
    $newReport->content = $data->content;
    $newReport->format = $data->format;
    $newReport->status = 'pending';
    $newReport->timecreated = date('Y-m-d H:i:s');
    $newReport->timemodified = date('Y-m-d H:i:s');
    $newReportId = $DB->insert_record($table, $newReport);
    return $newReportId;
}
function get_all_reports_by_groupid($groupid)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_reports} WHERE groupid = :groupid';
    $param = array('groupid' => $groupid);
    $report = $DB->get_records_sql($sql, $param);
    return $report;
}

function get_report_by_projectid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_reports} WHERE supervisorid = ' . $id;
    $param = array($id);
    $report = $DB->get_records_sql($sql, $param);
    return $report;
}

function get_report_by_studentid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_reports} WHERE studentid = ' . $id;
    $param = array($id);
    $report = $DB->get_records_sql($sql, $param);
    return $report;
}
function get_report_by_reportid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_reports} WHERE id = ' . $id;
    $param = array($id);
    $report = $DB->get_record_sql($sql, $param);
    return $report;
}

function get_all_reports()
{
    global $DB;
    $table = 'esupervision_reports';
    $report = $DB->get_records($table);
    return $report;
}

function update_report($data)
{
    global $DB;
    $table = 'esupervision_reports';
    $data->timemodified = date('Y-m-d H:i:s');
    $update_reportid = $DB->update_record($table, $data);
    return $update_reportid;
}

function delete_report($id)
{
    global $DB;
    $table = 'esupervision_reports';
    $delete_reportid = $DB->delete_records($table, array('id' => $id));
    return $delete_reportid;
}

function reject_report($id)
{
    global $DB;
    $table = 'esupervision_reports';
    $report = $DB->get_record($table, array('id' => $id));
    $report->status = 'rejected';
    $report->timemodified = date('Y-m-d H:i:s');
    $update_reportid = $DB->update_record($table, $report);
    return $update_reportid;
}

function approve_report($id)
{
    global $DB;
    $table = 'esupervision_reports';
    $report = $DB->get_record($table, array('id' => $id));
    $report->status = 'approved';
    $report->timemodified = date('Y-m-d H:i:s');
    $update_reportid = $DB->update_record($table, $report);
    return $update_reportid;

}


function get_topic_by_groupid($groupid)
{
    global $DB;
    $topictable = 'esupervision_topics';
    $sql = 'SELECT * FROM {' . $topictable . '} WHERE groupid = :groupid';
    $params = array('groupid' => $groupid);
    $topic = $DB->get_records_sql($sql, $params);
    return $topic;
}

function approve_topic($id)
{
    global $DB;
    $table = 'esupervision_topics';
    $topic = $DB->get_record($table, array('id' => $id));
    $topic->status = 'approved';
    $topic->timemodified = date('Y-m-d H:i:s');
    $update_topicid = $DB->update_record($table, $topic);
    return $update_topicid;
}

function reject_topic($id)
{
    global $DB;
    $table = 'esupervision_topics';
    $topic = $DB->get_record($table, array('id' => $id));
    $topic->status = 'rejected';
    $topic->timemodified = date('Y-m-d H:i:s');
    $update_topicid = $DB->update_record($table, $topic);
    return $update_topicid;
}

function submit_feedback($data)
{
    global $DB;
    $table = 'esupervision_feedbacks';
    $data->timecreated = date('Y-m-d H:i:s');
    $data->timemodified = date('Y-m-d H:i:s');
    $newCommentId = $DB->insert_record($table, $data);
    return $newCommentId;
}

function get_feedbacks_by_submissionid($id)
{
    global $DB;
    $table = 'esupervision_feedbacks';
    $sql = 'SELECT * FROM {' . $table . '} WHERE submissionid = ' . $id;
    $feedbacks = $DB->get_records_sql($sql, array($id));
    return $feedbacks;
}

function get_feedback_by_id($id)
{
    global $DB;
    $table = 'esupervision_feedbacks';
    $sql = 'SELECT * FROM {' . $table . '} WHERE id= ' . $id;
    $comment = $DB->get_record_sql($sql, array($id));
    return $comment;
}

function update_feedback($data)
{
    global $DB;
    $table = 'esupervision_feedbacks';
    $updateid = $DB->update_record($table, $data);
    return $updateid;
}

function delete_feedback($id, $type)
{
    global $DB;
    $table = 'esupervision_feedbacks';
    $select = 'id = :id AND type = :type';
    $params = array('id' => $id, 'type' => $type);
    $delete_commentid = $DB->delete_records_select($table, $select, $params);
    return $delete_commentid;
}

function get_user_by_id($id)
{
    global $DB;
    $table = 'user';
    $user = $DB->get_record($table, array('id' => $id));
    return $user;
}

function submit_proposal($data)
{
    global $DB;
    $table = 'esupervision_proposals';
    $newProposal = new stdClass();
    $newProposal->title = $data->title;
    $newProposal->studentid = $data->studentid;
    $newProposal->groupid = $data->groupid;
    $newProposal->content = $data->content;
    $newProposal->format = $data->format;
    $newProposal->status = 'pending';
    $newProposal->timecreated = date('Y-m-d H:i:s');
    $newProposal->timemodified = date('Y-m-d H:i:s');
    $newProposalId = $DB->insert_record($table, $newProposal);
    return $newProposalId;
}

function get_all_proposals_by_groupid($groupid)
{
    global $DB;
    $table = 'esupervision_proposals';
    $sql = 'SELECT * FROM {' . $table . '} WHERE groupid = :groupid';
    $proposals = $DB->get_records_sql($sql, array('groupid' => $groupid));
    return $proposals;
}
function get_proposal_by_studentid($id)
{
    global $DB;
    $table = 'esupervision_proposals';
    $sql = 'SELECT * FROM {' . $table . '} WHERE studentid = :id';
    $param = array('id' => $id);
    $proposal = $DB->get_records_sql($sql, $param);
    return $proposal;
}

function get_proposal_by_proposalid($id)
{
    global $DB;
    $table = 'esupervision_proposals';
    $sql = 'SELECT * FROM {' . $table . '} WHERE id = ' . $id;
    $param = array($id);
    $proposal = $DB->get_record_sql($sql, $param);
    return $proposal;
}

function approve_proposal($id)
{
    global $DB;
    $table = 'esupervision_proposals';
    $proposal = $DB->get_record($table, array('id' => $id));
    $proposal->status = 'approved';
    $proposal->timemodified = date('Y-m-d H:i:s');
    $proposalid = $DB->update_record($table, $proposal);
    return $proposalid;
}

function get_user_by_groupid($groupid, $userid)
{
    global $DB;
    $table = 'groups_members';
    $sql = 'SELECT * FROM {' . $table . '} WHERE groupid = ' . $groupid . ' AND id = ' . $userid;
    $param = array($groupid, $userid);
    $user = $DB->get_record_sql($sql, $param);
    return $user;
}

function get_all_users() {
    global $DB;
    $table = 'user';
    $users = $DB->get_records($table);
    return $users;
}

function reject_proposal($id)
{
    global $DB;
    $table = 'esupervision_proposals';
    $proposal = $DB->get_record($table, array('id' => $id));
    $proposal->status = 'rejected';
    $proposal->timemodified = date('Y-m-d H:i:s');
    $proposalid = $DB->update_record($table, $proposal);
    return $proposalid;
}

function update_proposal($data)
{
    global $DB;
    $table = 'esupervision_proposals';
    $data->timemodified = date('Y-m-d H:i:s');
    $update_proposalid = $DB->update_record($table, $data);
    return $update_proposalid;
}

function delete_proposal($id)
{
    global $DB;
    $table = 'esupervision_proposals';
    $proposalid = $DB->delete_records($table, array('id' => $id));
    return $proposalid;
}


function submit_forumpost($data)
{
    global $DB;
    $table = 'esupervision_forumposts';
    $data->createdat = date('Y-m-d H:m:s');
    return $DB->insert_record($table, $data);

}

function submit_grade($grade) {
    global $DB;
    $table = 'esupervision_grades';
    $grade->timecreated = date('Y-m-d H:m:s');
    $gradeid = $DB->insert_record($table, $grade);
    return $gradeid;
}

function get_students_grade($supervisorid) {
    global $DB;
    $table = 'esupervision_grades';
    $grades = $DB->get_records($table, ['supervisorid' => $supervisorid]);
    return $grades;
}

function get_grade_by_studentid($studentid) {
    global $DB;
    $table = 'esupervision_grades';
    $grades = $DB->get_records($table, ['studentid' => $studentid]);
    return $grades;
}

function delete_grade($id) {
    global $DB;
    $table = 'esupervision_grades';
    $deleteid = $DB->delete_records($table, ['id' => $id]);
    return $deleteid;
}

function submit_group($group) {
    global $DB;
    $table = 'esupervision_groups';
    $group->timecreated = date('Y-m-d H:m:s');
    $groupid = $DB->insert_record($table, $group);
    return $groupid;
}

function get_groups() {
    global $DB;
    $table = 'esupervision_groups';
    $groups = $DB->get_records($table);
    return $groups;
}

function get_group_by_id($id) {
    global $DB;
    $table = 'esupervision_groups';
    $group = $DB->get_record($table, ['id' => $id]);
    return $group;
}

function get_group_by_name($name) {
    global $DB;
    $table = 'esupervision_groups';
    $sql = 'SELECT * FROM {'.$table.'} WHERE TRIM(name) = :name';
    $group = $DB->get_record_sql($sql, ['name' => $name]);
    return $group;
}

function delete_group($id) {
    global $DB;
    $table = 'esupervision_groups';
    $deleteid = $DB->delete_records($table, ['id' => $id]);
    return $deleteid;
}

function assign_group($data) {
    global $DB;
    $table = 'esupervision_students';
    $data->timecreated = date('Y-m-d H:m:s');
    $data->timemodified = date('Y-m-d H:m:s');
    $assignid = $DB->insert_record($table, $data);
    return $assignid;
}

function get_group_students() {
    global $DB;
    $table = 'esupervision_students';
    $students = $DB->get_records($table);
    return $students;
}

function get_group_student_by_studentid($studentid) {
    global $DB;
    $table = 'esupervision_students';
    $student = $DB->get_record($table, ['studentid' => $studentid]);
    return $student;
}

function unassign_student($id) {
    global $DB;
    $table = 'esupervision_students';
    $deleteid = $DB->delete_records($table, ['id' => $id]);
    return $deleteid;
}

function get_user_by_name($fullname) {
    global $DB;
    $sql = "SELECT id from {user} WHERE CONCAT(firstname, ' ', lastname) = ?";
    $userid = $DB->get_record_sql($sql, array($fullname));
    return $userid;
}

function record_exists($table, $field, $value) {
    global $DB;
    if ($DB->record_exists($table, [$field => $value])) {
        return true;
    } else {
        return false;
    }
}

function local_esupervision_extend_navigation(global_navigation $root)
{
    $node = navigation_node::create(
        get_string('pluginname', 'local_esupervision'),
        new moodle_url('/local/esupervision/index.php'),
        navigation_node::TYPE_CUSTOM,
        null,
        null,
        new pix_icon('t/info', '')
    );
    $root->add_node($node);
}
function local_esupervision_pluginfile(
    $course,
    $cm,
    $context,
    string $filearea,
    array $args,
    bool $forcedownload,
    array $options = []
): bool {
    global $DB;
    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/local_esupervision/$filearea/$relativepath";
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }
    send_stored_file($file, 0, 0, true, $options);
}

function local_esupervision_create_chatroom($courseid, $chatroomname)
{
    global $DB;

    $chat = new stdClass();
    $chat->course = $courseid;
    $chat->name = $chatroomname;
    $chat->intro = 'Chat room for' . $chatroomname;
    $chat->introformat = FORMAT_MOODLE;
    $chat->keepdays = 365;
    $chat->studentlogs = 1;
    $chat->chattime = 0;
    $chat->timemodified = time();

    $chat->id = $DB->insert_record('chat', $chat);

    return $chat->id;
}

function local_esupervision_display_chat($chatid)
{
    global $CFG, $OUTPUT, $DB, $USER;

    if (!file_exists($CFG->dirroot . '/mod/chat/lib.php')) {
        throw new moodle_exception('chatmodulemissing', 'local_esupervision');
    }

    require_once($CFG->dirroot . '/mod/chat/lib.php');

    if (!$chat = $DB->get_record('chat', array('id' => $chatid))) {
        throw new moodle_exception('invalidchatid', 'local_esupervision', '', $chatid);
    }
    // require_capability('local/esupervision:viewchat', context_system::instance());

    echo $OUTPUT->header();
    chat_print_chat_script($chat);
    chat_print_chat_header($chat, $USER->id, false, 0, false);
    echo $OUTPUT->footer();
}