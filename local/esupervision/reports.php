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
// Set up the page context and layout
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Project Report');
$PAGE->set_heading('Project Report');
$PAGE->set_url("/local/esupervision/reports.php");

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

global $home_url, $download_url;
$report_form = new \local_esupervision\form\report_form(null, ['editoroptions' => $editoroptions]);
$feedback_form = new \local_esupervision\form\feedback_form(null, ['feedbackoptions' => $editoroptions]);
$allowpost = has_capability('local/esupervision:submitreport', $context);
$allowview = has_capability('local/esupervision:viewreports', $context);
$approvereport = has_capability('local/esupervision:approvereport', $context);
$action = optional_param('action', null, PARAM_TEXT);
$id = optional_param('id', null, PARAM_INT);
$fs = get_file_storage();

if (!$action) {
    $home_url = new moodle_url('/local/esupervision/index.php');
} elseif ($action) {
    $home_url = new moodle_url('/local/esupervision/reports.php');
}
$htmlstring = '<a href="' . $home_url . '" class="btn btn-primary mb-4">Back</a>';

echo $OUTPUT->header();
echo $htmlstring;
if ($allowpost) {
    if (!$action) {
        $report_form->add_action_buttons(true, 'Submit Report');
        $report_form->display();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$action) {
        if ($report_form->is_cancelled()) {
            redirect(
                $PAGE->url,
                'Proposal submission cancelled',
                \core\output\notification::NOTIFY_WARNING
            );
        } else if ($report_form->get_data()) {
            $data = $report_form->get_data();
            $report = new stdClass();
            $report = $data;
            if (!$data->id) {
                $report->groupid = $USER->idnumber;
                $report->studentid = $USER->id;
                var_dump($data->content);
                ['format' => $format, 'text' => $content] = $data->content;
                $report->content = $content;
                $report->format = $format;
                $submissionid = submit_report($report);
                if ($submissionid) {
                    redirect(
                        $PAGE->url,
                        'report submitted successfully!',
                        null,
                        \core\output\notification::NOTIFY_SUCCESS
                    );
                } else {
                    redirect(
                        $PAGE->url,
                        'Error submitting report!',
                        null,
                        \core\output\notification::NOTIFY_ERROR
                    );
                }
            } else {
                $report->studentid = $USER->id;
                ['format' => $format, 'text' => $content] = $data->content;
                $report->content = $content;
                $report->format = $format;
                $submissionid = update_report($report);
                if ($submissionid) {
                    redirect(
                        $PAGE->url,
                        'report updated successfully!',
                        null,
                        \core\output\notification::NOTIFY_SUCCESS
                    );
                } else {
                    redirect(
                        $PAGE->url,
                        null,
                        'Error updating report!',
                        \core\output\notification::NOTIFY_ERROR
                    );
                }
            }
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET' && !$action) {
        $reports = get_report_by_studentid($USER->id);
        foreach ($reports as $report) {
            $feedbacks = get_feedbacks_by_submissionid($report->id);
            $report->feedbacks = array_values($feedbacks);
        }
        $data = array(
            'issupervisor' => false,
            'reports' => array_values($reports),
        );
        echo $OUTPUT->render_from_template('local_esupervision/reports', $data);
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'edit' && $id) {
        $report = get_report_by_reportid($id);
        $report_form->set_data($report);
        $report_form->setEditorDefault($report->content, $report->format);
        $report_form->add_action_buttons(true, 'Update report');
        $report_form->display();
    } else if ($action == 'delete' && $id) {
        $fs->delete_area_files($context->id, 'local_esupervision', 'reports', $id);
        $commentid = delete_feedback($id, 'report');
        $reportid = delete_report($id);
        if ($reportid) {
            redirect(
                $PAGE->url,
                'report deleted successfully!',
                \core\output\notification::NOTIFY_SUCCESS
            );
        } else {
            redirect(
                $PAGE->url,
                'Error deleting report!',
                \core\output\notification::NOTIFY_ERROR
            );
        }
    }
}


if ($allowview && $approvereport) {
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
            $feedback->type = 'report';
            ['format' => $format, 'text' => $content] = $data->content;
            $feedback->feedback = $content;
            $feedback->format = $format;
            $data->type = 'report';
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
            $data->type = 'report';
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
        $reports = get_all_reports_by_groupid($USER->idnumber);
        foreach ($reports as $report) {
            $student = get_user_by_id($report->studentid);
            if ($student) {
                $report->studentname = $student->firstname . ' ' . $student->lastname;
            }
        }
        $data = array(
            'issupervisor' => true,
            'reports' => array_values($reports),
        );
        echo $OUTPUT->render_from_template('local_esupervision/reports', $data);
    } else if ($action) {
        if ($action == 'view' && $id) {
            $report = get_report_by_reportid($id);
            $student = get_user_by_id($report->studentid);
            if ($student) {
                $report->studentname = $student->firstname . ' ' . $student->lastname;
            }
            if ($report) {
                $report->feedbacks = array_values(get_feedbacks_by_submissionid($report->id));
            }
            $data = array(
                'viewreport' => true,
                'report' => $report,
            );
            echo $OUTPUT->render_from_template('local_esupervision/reports', $data);
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
            $approve = approve_report($id);
            if ($approve) {
                redirect(
                    $PAGE->url,
                    'report approved successfully!',
                    \core\output\notification::NOTIFY_SUCCESS
                );
            } else {
                redirect(
                    $PAGE->url,
                    'Error approving report!',
                    \core\output\notification::NOTIFY_ERROR
                );
            }
        } else if ($action == 'reject' && $id) {
            $reject = reject_report($id);
            if ($reject) {
                redirect(
                    $PAGE->url,
                    'report rejected successfully!',
                    \core\output\notification::NOTIFY_SUCCESS
                );
            } else {
                redirect(
                    $PAGE->url,
                    'Error rejecting report!',
                    \core\output\notification::NOTIFY_ERROR
                );
            }
        }
    }
}


echo $OUTPUT->footer();