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
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../lib.php');
require_login();

$context = context_system::instance();
// Set up the page context and layout
$PAGE->set_context($context);
$PAGE->set_pagelayout('popup');
$PAGE->set_title('Project Report');
$PAGE->set_heading('Project Report');
$PAGE->set_url("/local/esupervision/project/reports.php");

global $home_url, $download_url;
$report_form = new \local_esupervision\form\project_report();
$update_form = new \local_esupervision\form\updatereportform();
$allowpost = has_capability('local/esupervision:submitreport', $context);
$allowview = has_capability('local/esupervision:viewreports', $context);
$approvereport = has_capability('local/esupervision:approvereport', $context);
$action = optional_param('action', null, PARAM_TEXT);
$id = optional_param('id', null, PARAM_INT);
$fs = get_file_storage();

if (!$action) {
    $home_url = new moodle_url('/local/esupervision/index.php');
} elseif ($action) {
    $home_url = new moodle_url('/local/esupervision/project/reports.php');
}
$htmlstring = '<a href="' . $home_url . '" class="btn btn-primary mb-4">Back</a>';

echo $OUTPUT->header();
echo $htmlstring;
if ($allowpost) {
    if (!$action) {
        $report_form->display();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$action) {
        if ($report_form->is_cancelled()) {
            redirect(
                $PAGE->url,
                'Project submission cancelled',
                \core\output\notification::NOTIFY_WARNING
            );
        } elseif ($report_form->get_data()) {
            $data = $report_form->get_data();
            $filename = $report_form->get_new_filename('project_document');
            $data->filename = $filename;
            $data->studentid = $USER->id;
            $submissionid = submit_project_report($data);
            if ($submissionid) {
                $fileinfo = array(
                    'contextid' => $context->id,
                    'component' => 'local_esupervision',
                    'filearea' => 'reports',
                    'itemid' => $submissionid,
                    'filepath' => '/',
                    'filename' => $filename,
                );
                $fs->create_file_from_string($fileinfo, 'project report');
                redirect(
                    $PAGE->url,
                    'Report submitted successfully!',
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            } else {
                redirect(
                    $PAGE->url,
                    'Error submitting Report!',
                    \core\output\notification::NOTIFY_ERROR
                );
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && !$action) {
        $report = get_report_by_studentid($USER->id);
        $data = array(
            'issupervisor' => false,
            'reports' => array_values($report),
        );
        foreach ($report as $report) {
            $file = $fs->get_file($context->id, 'local_esupervision', 'reports', $report->id, '/', $report->filename);
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
                    $report->url = $download_url;
                } else {
                    $report->url = null;
                }
            }
        }
        echo $OUTPUT->render_from_template('local_esupervision/reports', $data);
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'edit' && $id) {
        $report = get_report_by_reportid($id);
        $update_form->set_data($report);
        $update_form->display();
        if ($update_form->is_cancelled()) {
            redirect(
                $PAGE->url,
                'Project submission cancelled',
                \core\output\notification::NOTIFY_WARNING
            );
        } elseif ($update_form->get_data()) {
            $data = $update_form->get_data();
            $filename = $update_form->get_new_filename('project_document');
            $data->filename = $filename;
            $data->studentid = $USER->id;
            $projectid = update_project($data);
            if ($projectid) {
                redirect(
                    $PAGE->url,
                    'Project submitted successfully!',
                    \core\output\notification::NOTIFY_SUCCESS
                );
            } else {
                redirect(
                    $PAGE->url,
                    'Error submitting project!',
                    \core\output\notification::NOTIFY_ERROR
                );
            }
        }
    } else if ($action == 'delete' && $id) {
        $projectid = delete_report($id);
        if ($projectid) {
            redirect(
                $PAGE->url,
                'Project deleted successfully!',
                \core\output\notification::NOTIFY_SUCCESS
            );
        } else {
            redirect(
                $PAGE->url,
                'Error deleting project!',
                \core\output\notification::NOTIFY_ERROR
            );
        }
    }
}


if ($allowview && $approvereport) {
    if (!$action) {
        $reports = get_all_reports();
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
    } else {
        if ($action == 'view' && $id) {
            $report = get_report_by_reportid($id);
            $student = get_user_by_id($report->studentid);
            if ($student) {
                $report->studentname = $student->firstname . ' ' . $student->lastname;
            }
            $file = $fs->get_file($context->id, 'local_esupervision', 'reports', $report->id, '/', $report->id);
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
                    $report['url'] = $download_url;
                } else {
                    $report['url'] = null;
                }
            }
            $data = array(
                'viewreport' => true,
                'report' => $report,
            );
            echo $OUTPUT->render_from_template('local_esupervision/reports', $data);
        } elseif ($action == 'approve' && $id) {
            $approve = approve_report($id);
            if ($approve) {
                redirect(
                    $PAGE->url,
                    'Report approved successfully!',
                    \core\output\notification::NOTIFY_SUCCESS
                );
            } else {
                redirect(
                    $PAGE->url,
                    'Error approving report!',
                    \core\output\notification::NOTIFY_ERROR
                );
            }
        } elseif ($action == 'reject' && $id) {
            $reject = reject_report($id);
            if ($reject) {
                redirect(
                    $PAGE->url,
                    'Report rejected successfully!',
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