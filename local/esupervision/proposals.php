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

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Project proposal');
$PAGE->set_heading('Project proposal');
$PAGE->set_url("/local/esupervision/proposals.php");
$PAGE->navbar->add('Dashboard', new moodle_url('/local/esupervision/index.php'));
$PAGE->navbar->add('Project proposal', new moodle_url('/local/esupervision/proposals.php'));
global $home_url, $download_url;
$editoroptions = array(
	'maxfiles' => 1,
	'maxbytes' => 10485760,
	'context' => context_system::instance(),
	'trusttext' => false,
	'enable_filemanagement' => true,
	'noclean' => true,
	'enable_upload' => true,
	'editor_height' => 400,
	'editor_width' => '100%'
);

$allowpost = has_capability('local/esupervision:submitproposal', $context);
$allowview = has_capability('local/esupervision:viewproposals', $context);
$approveproposal = has_capability('local/esupervision:approveproposal', $context);
$action = optional_param('action', null, PARAM_TEXT);
$id = optional_param('id', null, PARAM_INT);
$fs = get_file_storage();
$feedbacks = array();

$proposal_form = new \local_esupervision\form\proposal_form(null, ['editoroptions' => $editoroptions]);
$feedback_form = new \local_esupervision\form\feedback_form(null, ['feedbackoptions' => $editoroptions]);

if (!$action) {
	$home_url = new moodle_url('/local/esupervision/index.php');
} else if ($action) {
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
		} else if ($proposal_form->get_data()) {
			$data = $proposal_form->get_data();
			$proposal = new stdClass();
			$proposal = $data;
			if (!$data->id) {
				$proposal->groupid = $USER->idnumber;
				$proposal->studentid = $USER->id;
				['format' => $format, 'text' => $content] = $data->content;
				$proposal->content = $content;
				$proposal->format = $format;
				$submissionid = submit_proposal($proposal);
				if ($submissionid) {
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
						null,
						\core\output\notification::NOTIFY_ERROR
					);
				}
			} else {
				$proposal->studentid = $USER->id;
				['format' => $format, 'text' => $content] = $data->content;
				$proposal->content = $content;
				$proposal->format = $format;
				$submissionid = update_proposal($proposal);
				if ($submissionid) {
					redirect(
						$PAGE->url,
						'proposal updated successfully!',
						null,
						\core\output\notification::NOTIFY_SUCCESS
					);
				} else {
					redirect(
						$PAGE->url,
						null,
						'Error updating proposal!',
						\core\output\notification::NOTIFY_ERROR
					);
				}
			}
		}
	} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && !$action) {
		$proposals = get_proposal_by_studentid($USER->id);
		foreach ($proposals as $proposal) {
			$feedbacks = get_feedbacks_by_submissionid($proposal->id);
			$proposal->feedbacks = array_values($feedbacks);
		}
		$data = array(
			'issupervisor' => false,
			'proposals' => array_values($proposals),
		);
		echo $OUTPUT->render_from_template('local_esupervision/proposals', $data);
	} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'edit' && $id) {
		$proposal = get_proposal_by_proposalid($id);
		$proposal_form->set_data($proposal);
		$proposal_form->setEditorDefault($proposal->content, $proposal->format);
		$proposal_form->add_action_buttons(true, 'Update proposal');
		$proposal_form->display();
	} else if ($action == 'delete' && $id) {
		$fs->delete_area_files($context->id, 'local_esupervision', 'proposals', $id);
		$commentid = delete_feedback($id, 'proposal');
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
	if ($feedback_form->is_cancelled()) {
		redirect(
			$PAGE->url,
			'Feedback submission cancelled',
			null,
			\core\output\notification::NOTIFY_WARNING
		);
	} else if ($feedback_form->get_data()) {
		$data = $feedback_form->get_data();
		$feedback = new stdClass();
		if (!$data->id) {
			$feedback->supervisorid = $USER->id;
			$feedback->studentid = $USER->id;
			$feedback->submissionid = $data->submissionid;
			$feedback->type = 'proposal';
			['format' => $format, 'text' => $content] = $data->content;
			$feedback->feedback = $content;
			$feedback->format = $format;
			$data->type = 'proposal';
			$feedbackid = submit_feedback($feedback);
			if ($feedbackid) {
				redirect(
					$PAGE->url,
					'Feedback submitted successfully!',
					null,
					\core\output\notification::NOTIFY_SUCCESS
				);
			} else {
				redirect(
					$PAGE->url,
					'Error submitting feedback!',
					null,
					\core\output\notification::NOTIFY_ERROR
				);
			}
		} else {
			$data->supervisorid = $USER->id;
			$data->type = 'proposal';
			$feedbackid = update_feedback($data);
			if ($feedbackid) {
				redirect(
					$PAGE->url,
					'Comment updated successfully!',
					\core\output\notification::NOTIFY_SUCCESS
				);
			} else {
				redirect(
					$PAGE->url,
					'Error updating feedback!',
					\core\output\notification::NOTIFY_ERROR
				);
			}
		}
	}
	if (!$action) {
		$proposals = get_all_proposals_by_groupid($USER->idnumber);
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
	} else if ($action) {
		if ($action == 'view' && $id) {
			$proposal = get_proposal_by_proposalid($id);
			$student = get_user_by_id($proposal->studentid);
			if ($student) {
				$proposal->studentname = $student->firstname . ' ' . $student->lastname;
			}
			if ($proposal) {
				$proposal->feedbacks = array_values(get_feedbacks_by_submissionid($proposal->id));
			}
			$data = array(
				'viewproposal' => true,
				'proposal' => $proposal,
			);
			echo $OUTPUT->render_from_template('local_esupervision/proposals', $data);
			$data = new stdClass();
			$data->submissionid = $id;
			$feedback_form->add_action_buttons(true, 'Submit feedback');
			$feedback_form->set_data($data);
			$feedback_form->display();
		} else if ($action == 'editfeedback' && $id) {
			$feedback = get_feedback_by_id($id);
			$feedback_form->add_action_buttons(true, 'Update feedback');
			$feedback_form->set_data($feedback);
			$feedback_form->display();
		} else if ($action == 'approve' && $id) {
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
		} else if ($action == 'reject' && $id) {
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