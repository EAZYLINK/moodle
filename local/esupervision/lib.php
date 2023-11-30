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

function submit_topic($data)
{
    global $DB;
    $table = 'esupervision_projecttopics';
    // Create a new project record
    $newTopic = new stdClass();
    $newTopic->topic = $data->topic;
    $newTopic->studentid = $data->studentid;
    $newTopic->description = $data->description;
    $newTopic->status = 'pending';
    $newTopic->timecreated = date('Y-m-d H:i:s');
    $newTopic->timemodified = date('Y-m-d H:i:s');
    $newTopicId = $DB->insert_record($table, $newTopic);
    return $newTopicId;
}

function get_topic_by_studentid($id)
{
    global $DB;
    $table = 'esupervision_projecttopics';
    $sql = 'SELECT * FROM {esupervision_projecttopics} WHERE studentid = ' . $id;
    $params = array($id);
    $topic = $DB->get_records_sql($sql, $params);
    // $topic = $DB->get_record($table, array('studentid' => $id));
    return $topic;
}

function get_topic_by_topicid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_projecttopics} WHERE id = ' . $id;
    $params = array($id);
    $topic = $DB->get_records_sql($sql, $params);
    return $topic;
}

function update_topic($topic)
{
    global $DB;
    $table = 'esupervision_projecttopics';
    $topic->status = 'pending';
    $topic->timemodified = date('Y-m-d H:i:s');
    $update_topicid = $DB->update_record($table, $topic);
    return $update_topicid;
}

function delete_topic($id)
{
    global $DB;
    $table = 'esupervision_projecttopics';
    $delete_topicid = $DB->delete_records($table, array('id' => $id));
    return $delete_topicid;
}



function submit_project_report($data)
{
    global $DB;
    $table = 'esupervision_projectreports';
    $newReport = new stdClass();
    $newReport->topic = $data->title;
    $newReport->studentid = $data->studentid;
    $newReport->supervisorid = 3;
    $newReport->description = $data->description;
    $newReport->filename = $data->filename;
    $newReport->status = 'pending';
    $newReport->timecreated = date('Y-m-d H:i:s');
    $newReport->timemodified = date('Y-m-d H:i:s');
    $newReportId = $DB->insert_record($table, $newReport);
    return $newReportId;
}
function get_report_by_supervisorid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_projectreports} WHERE supervisorid = ' . $id;
    $param = array($id);
    $report = $DB->get_records_sql($sql, $param);
    return $report;
}

function get_report_by_projectid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_projectreports} WHERE supervisorid = ' . $id;
    $param = array($id);
    $report = $DB->get_records_sql($sql, $param);
    return $report;
}

function get_report_by_studentid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_projectreports} WHERE studentid = ' . $id;
    $param = array($id);
    $report = $DB->get_records_sql($sql, $param);
    return $report;
}
function get_report_by_reportid($id)
{
    global $DB;
    $sql = 'SELECT * FROM {esupervision_projectreports} WHERE id = ' . $id;
    $param = array($id);
    $report = $DB->get_record_sql($sql, $param);
    return $report;
}

function get_all_reports()
{
    global $DB;
    $table = 'esupervision_projectreports';
    $report = $DB->get_records($table);
    return $report;
}

function update_report($data)
{
    global $DB;
    $table = 'esupervision_projectreports';
    $data->timemodified = time();
    $update_reportid = $DB->update_record($table, $data);
    return $update_reportid;
}

function delete_report($id)
{
    global $DB;
    $table = 'esupervision_projectreports';
    $delete_reportid = $DB->delete_records($table, array('id' => $id));
    return $delete_reportid;
}

function reject_report($id)
{
    global $DB;
    $table = 'esupervision_projectreports';
    $report = $DB->get_record($table, array('id' => $id));
    $report->status = 'rejected';
    $report->timemodified = date('Y-m-d H:i:s');
    $update_reportid = $DB->update_record($table, $report);
    return $update_reportid;
}

function approve_report($id)
{
    global $DB;
    $table = 'esupervision_projectreports';
    $report = $DB->get_record($table, array('id' => $id));
    $report->status = 'approved';
    $report->timemodified = date('Y-m-d H:i:s');
    $update_reportid = $DB->update_record($table, $report);
    return $update_reportid;

}


function get_topic_list()
{
    global $DB;
    $table = 'esupervision_projecttopics';
    $topic = $DB->get_records($table);
    return $topic;
}

function approve_topic($id)
{
    global $DB;
    $table = 'esupervision_projecttopics';
    $topic = $DB->get_record($table, array('id' => $id));
    $topic->status = 'approved';
    $topic->timemodified = date('Y-m-d H:i:s');
    $update_topicid = $DB->update_record($table, $topic);
    return $update_topicid;
}

function reject_topic($id)
{
    global $DB;
    $table = 'esupervision_projecttopics';
    $topic = $DB->get_record($table, array('id' => $id));
    $topic->status = 'rejected';
    $topic->timemodified = date('Y-m-d H:i:s');
    $update_topicid = $DB->update_record($table, $topic);
    return $update_topicid;
}

function submit_comment($data)
{
    global $DB;
    $table = 'esupervision_comments';
    $data->timecreated = date('Y-m-d H:i:s');
    $data->timemodified = date('Y-m-d H:i:s');
    $newCommentId = $DB->insert_record($table, $data);
    return $newCommentId;
}

function get_comments_by_submissionid($id)
{
    global $DB;
    $table = 'esupervision_comments';
    $sql = 'SELECT * FROM {' . $table . '} WHERE submissionid = ' . $id;
    $comments = $DB->get_records_sql($sql, array($id));
    return $comments;
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
    $table = 'esupervision_projectproposals';
    $newProposal = new stdClass();
    $newProposal->title = $data->title;
    $newProposal->studentid = $data->studentid;
    $newProposal->supervisorid = 3;
    $newProposal->description = $data->description;
    $newProposal->filename = $data->filename;
    $newProposal->status = 'pending';
    $newProposal->timecreated = date('Y-m-d H:i:s');
    $newProposal->timemodified = date('Y-m-d H:i:s');
    $newProposalId = $DB->insert_record($table, $newProposal);
    return $newProposalId;
}

function get_all_proposals_by_groupid($groupid)
{
    global $DB;
    $table = 'esupervision_projectproposals';
    $sql = `SELECT * FROM {$table} WHERE groupid = $groupid`;
    $proposals = $DB->get_records($table);
    return $proposals;
}
function get_proposal_by_studentid($id)
{
    global $DB;
    $table = 'esupervision_projectproposals';
    $sql = 'SELECT * FROM {' . $table . '} WHERE studentid = ' . $id;
    $param = array($id);
    $proposal = $DB->get_records_sql($sql, $param);
    return $proposal;
}

function get_proposal_by_proposalid($id)
{
    global $DB;
    $table = 'esupervision_projectproposals';
    $sql = 'SELECT * FROM {' . $table . '} WHERE id = ' . $id;
    $param = array($id);
    $proposal = $DB->get_record_sql($sql, $param);
    return $proposal;
}

function approve_proposal($id)
{
    global $DB;
    $table = 'esupervision_projectproposals';
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

function reject_proposal($id)
{
    global $DB;
    $table = 'esupervision_projectproposals';
    $proposal = $DB->get_record($table, array('id' => $id));
    $proposal->status = 'rejected';
    $proposal->timemodified = date('Y-m-d H:i:s');
    $proposalid = $DB->update_record($table, $proposal);
    return $proposalid;
}

function delete_proposal($id)
{
    global $DB;
    $table = 'esupervision_projectproposals';
    $proposalid = $DB->delete_record($table, array('id' => $id));
    return $proposalid;
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