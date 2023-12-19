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
 * project submission form for project supervision
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package local_esupervision
 */
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_login();
global $USER;

$context = context_system::instance();
// Set up the page context and layout
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Project proposal');
$PAGE->set_heading('Project proposal');
$PAGE->set_url("/local/esupervision/proposals.php");

global $home_url, $download_url;
navigation_node::override_active_url(new moodle_url('/local/esupervision/project/proposals.php'));

$allowpost = has_capability('local/esupervision:submitproposal', $context);
$allowview = has_capability('local/esupervision:viewproposals', $context);
$approveproposal = has_capability('local/esupervision:approveproposal', $context);
$action = optional_param('action', null, PARAM_TEXT);
$id = optional_param('id', null, PARAM_INT);
$fs = get_file_storage();
$comments = array();

$proposal_form = new \local_esupervision\form\proposal_form();
$comment_form = new \local_esupervision\form\comment_form();

if (!$action) {
	$home_url = new moodle_url('/local/esupervision/index.php');
} elseif ($action) {
	$home_url = new moodle_url('/local/esupervision/proposals.php');
}
$htmlstring = '<a href="' . $home_url . '" class="btn btn-primary mb-4">Back</a>';

echo $OUTPUT->header();
echo $htmlstring;
if ($allowpost) {
	if (!$action) {
		$proposal_form->add_action_buttons(true, 'Submit proposal');
		$proposal_form->display();
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$action) {
		if ($proposal_form->is_cancelled()) {
			redirect(
				$PAGE->url,
				'Proposal submission cancelled',
				\core\output\notification::NOTIFY_WARNING
			);
		} elseif ($proposal_form->get_data()) {
			$data = $proposal_form->get_data();
			if (!$data->id) {
				$filename = $proposal_form->get_new_filename('proposal_document');
				$data->filename = $filename;
				$data->groupid = $USER->idnumber;
				$data->studentid = $USER->id;
				$filepath = 'uploads/' . $filename;
				$proposal_form->save_file('proposal_document', $filepath);
				$submissionid = submit_proposal($data);
				if ($submissionid) {
					$fileinfo = array(
						'contextid' => $context->id,
						'component' => 'local_esupervision',
						'filearea' => 'proposals',
						'itemid' => $submissionid,
						'filepath' => '/',
						'filename' => $filename,
					);
					$fs->create_file_from_pathname($fileinfo, $filepath);
					unlink($filepath);
					redirect(
						$PAGE->url,
						'proposal submitted successfully!',
						null,
						\core\output\notification::NOTIFY_SUCCESS
					);
				} else {
					redirect(
						$PAGE->url,
						'Error submitting proposal!',
						\core\output\notification::NOTIFY_ERROR
					);
				}
			} else {
				$filename = $proposal_form->get_new_filename('proposal_document');
				$data->filename = $filename;
				$data->studentid = $USER->id;
				$filepath = 'uploads/' . $filename;
				$fs->delete_area_files($context->id, 'local_esupervision', 'proposals', $data->id);
				$proposal_form->save_file('proposal_document', $filepath);
				$submissionid = update_proposal($data);
				if ($submissionid) {
					$fileinfo = array(
						'contextid' => $context->id,
						'component' => 'local_esupervision',
						'filearea' => 'proposals',
						'itemid' => $data->id,
						'filepath' => '/',
						'filename' => $filename,
					);
					$fs->create_file_from_pathname($fileinfo, $filepath);
					unlink($filepath);
					redirect(
						$PAGE->url,
						'proposal updated successfully!',
						null,
						\core\output\notification::NOTIFY_SUCCESS
					);
				} else {
					redirect(
						$PAGE->url,
						'Error updating proposal!',
						\core\output\notification::NOTIFY_ERROR
					);
				}
			}
		}
	} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && !$action) {
		$proposals = get_proposal_by_studentid($USER->id);
		foreach ($proposals as $proposal) {
			$comments = get_comments_by_submissionid($proposal->id);
			$file = $fs->get_file($context->id, 'local_esupervision', 'proposals', $proposal->id, '/', $proposal->filename);
			if ($file) {
				$download_url = moodle_url::make_pluginfile_url(
					$file->get_contextid(),
					$file->get_component(),
					$file->get_filearea(),
					$file->get_itemid(),
					$file->get_filepath(),
					$file->get_filename(),
					true
				);
			}
			$proposal->url = $download_url;
			$proposal->comments = array_values($comments);
		}
		$data = array(
			'issupervisor' => false,
			'proposals' => array_values($proposals),
		);
		echo $OUTPUT->render_from_template('local_esupervision/proposals', $data);
	} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'edit' && $id) {
		$proposal = get_proposal_by_proposalid($id);
		$draftfileid = file_get_submitted_draft_itemid('proposal_document');
		file_prepare_draft_area(
			$draftfileid,
			$context->id,
			'local_esupervision',
			'proposals',
			$proposal->id,
			array('subdirs' => true),
			$proposal->filename
		);
		$proposal->proposal_document = $draftfileid;
		$proposal_form->set_data($proposal);
		$proposal_form->add_action_buttons(true, 'Update proposal');
		$proposal_form->display();
	} else if ($action == 'delete' && $id) {
		$fs->delete_area_files($context->id, 'local_esupervision', 'proposals', $id);
		$proposalid = delete_proposal($id);
		if ($proposalid) {
			redirect(
				$PAGE->url,
				'proposal deleted successfully!',
				\core\output\notification::NOTIFY_SUCCESS
			);
		} else {
			redirect(
				$PAGE->url,
				'Error deleting proposal!',
				\core\output\notification::NOTIFY_ERROR
			);
		}
	}
}


if ($allowview && $approveproposal) {
	if ($comment_form->is_cancelled()) {
		redirect(
			$PAGE->url,
			'Comment submission cancelled',
			\core\output\notification::NOTIFY_WARNING
		);
	} elseif ($comment_form->get_data()) {
		$data = $comment_form->get_data();
		if (!$data->id) {
			$data->supervisorid = $USER->id;
			$data->type = 'proposal';
			$commentid = submit_comment($data);
			if ($commentid) {
				redirect(
					$PAGE->url,
					'Comment submitted successfully!',
					\core\output\notification::NOTIFY_SUCCESS
				);
			} else {
				redirect(
					$PAGE->url,
					'Error submitting comment!',
					\core\output\notification::NOTIFY_ERROR
				);
			}
		} else {
			$data->supervisorid = $USER->id;
			$data->type = 'proposal';
			$commentid = update_comment($data);
			if ($commentid) {
				redirect(
					$PAGE->url,
					'Comment updated successfully!',
					\core\output\notification::NOTIFY_SUCCESS
				);
			} else {
				redirect(
					$PAGE->url,
					'Error updating comment!',
					\core\output\notification::NOTIFY_ERROR
				);
			}
		}
	}
	if (!$action) {
		$proposals = get_all_proposals_by_groupid($USER->idnumber);
		var_dump($proposals);
		foreach ($proposals as $proposal) {
			$student = get_user_by_id($proposal->studentid);
			if ($student) {
				$proposal->studentname = $student->firstname . ' ' . $student->lastname;
			}
		}
		$data = array(
			'issupervisor' => true,
			'proposals' => array_values($proposals),
		);
		echo $OUTPUT->render_from_template('local_esupervision/proposals', $data);
	} elseif ($action) {
		if ($action == 'view' && $id) {
			$proposal = get_proposal_by_proposalid($id);
			$student = get_user_by_id($proposal->studentid);
			if ($student) {
				$proposal->studentname = $student->firstname . ' ' . $student->lastname;
			}
			if ($proposal) {
				$file = $fs->get_file($context->id, 'local_esupervision', 'proposals', $proposal->id, '/', $proposal->filename);
				if ($file) {
					$download_url = moodle_url::make_pluginfile_url(
						$file->get_contextid(),
						$file->get_component(),
						$file->get_filearea(),
						$file->get_itemid(),
						$file->get_filepath(),
						$file->get_filename(),
						false
					);
					if ($download_url) {
						$proposal->url = $download_url;
					} else {
						$proposal->url = null;
					}
				}
			}
			$data = array(
				'viewproposal' => true,
				'proposal' => $proposal,
			);
			echo $OUTPUT->render_from_template('local_esupervision/proposals', $data);
			$data = new stdClass();
			$data->submissionid = $id;
			$comment_form->set_data($data);
			$comment_form->display();
		} elseif ($action == 'approve' && $id) {
			$approve = approve_proposal($id);
			if ($approve) {
				redirect(
					$PAGE->url,
					'proposal approved successfully!',
					\core\output\notification::NOTIFY_SUCCESS
				);
			} else {
				redirect(
					$PAGE->url,
					'Error approving proposal!',
					\core\output\notification::NOTIFY_ERROR
				);
			}
		} elseif ($action == 'reject' && $id) {
			$reject = reject_proposal($id);
			if ($reject) {
				redirect(
					$PAGE->url,
					'proposal rejected successfully!',
					\core\output\notification::NOTIFY_SUCCESS
				);
			} else {
				redirect(
					$PAGE->url,
					'Error rejecting proposal!',
					\core\output\notification::NOTIFY_ERROR
				);
			}
		}
	}
}


echo $OUTPUT->footer();