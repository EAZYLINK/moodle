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
 * TODO describe file submitted_topics.php
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

require_login();

// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Topics');
$PAGE->set_url("/local/esupervision/topics.php");
$PAGE->navbar->add('Dashboard', new moodle_url('/local/esupervision/index.php'));
$PAGE->navbar->add('Project Topic', new moodle_url('/local/esupervision/topics.php'));


$context = context_user::instance($USER->id);
$allowpost = has_capability('local/esupervision:submittopic', $context);
$allowview = has_capability('local/esupervision:approvetopic', $context);
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);
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

$topicform = new \local_esupervision\form\topic_form(null, ['editoroptions' => $editoroptions]);
$feedback_form = new \local_esupervision\form\feedback_form(null, ['feedbackoptions' => $editoroptions]);

echo $OUTPUT->header();

if ($allowpost) {
    if (!$action) {
        $topicform->add_action_buttons(true, 'Submit Topic');
        $topicform->display();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$action) {
        if ($topicform->is_cancelled()) {
            redirect(
                $PAGE->url,
                'form cancelled',
                null,
                \core\output\notification::NOTIFY_WARNING
            );

        } elseif ($fromform = $topicform->get_data()) {
            $data = $topicform->get_data();
            $data->studentid = $USER->id;
            $data->groupid = $USER->idnumber;
            $topic = new stdClass();
            $topic = $data;
            if (!$data->id) {
                ['text' => $content, 'format' => $format] = $data->description;
                $topic->description = $content;
                $topic->format = $format;
                $topic_id = submit_topic($topic);
                if ($topic_id) {
                    redirect(
                        $PAGE->url,
                        'topic submitted',
                        null,
                        \core\output\notification::NOTIFY_SUCCESS
                    );
                } else {
                    redirect(
                        $PAGE->url,
                        'an error occured',
                        null,
                        \core\output\ntification::NOTIFY_ERROR
                    );
                }
            } else {
                ['text' => $content, 'format' => $format] = $data->description;
                $topic->description = $content;
                $topic->format = $format;
                $topic_id = update_topic($topic);
                if ($topic_id) {
                    redirect(
                        $PAGE->url,
                        'topic updated successfully',
                        null,
                        \core\output\notification::NOTIFY_SUCCESS
                    );
                } else {
                    redirect(
                        $PAGE->url,
                        'an error occured',
                        null,
                        \core\output\ntification::NOTIFY_ERROR
                    );
                }
            }

        }

    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && !$action) {
        $topics = get_topic_by_studentid($USER->id);
        foreach ($topics as $topic) {
            $feedbacks = get_feedbacks_by_submissionid($topic->id);
            $topic->feedbacks = array_values($feedbacks);
        }
        $data = array(
            'issupervisor' => false,
            'topics' => array_values($topics),
        );
        echo $OUTPUT->render_from_template('local_esupervision/topic', $data);
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'edit' && $id) {
        $topic = get_topic_by_topicid($id);
        $toform = array(
            'topic' => $topic->topic,
            'description' => $topic->description,
            'id' => $topic->id,
        );
        $topicform->set_data($toform);
        $topicform->setEditorDefaults($topic->description, $topic->format);
        $topicform->add_action_buttons(true, 'Update Topic');
        $topicform->display();
    } elseif ($action == 'delete' && $id) {
        $deleteId = delete_topic($id);
        if ($deleteId) {
            redirect($PAGE->url, 'topic deleted successfully');

        } else {
            redirect($PAGE->url, 'an error occured');

        }
    }
}

if ($allowview) {
    if (!$action) {
        if ($feedback_form->is_cancelled()) {
            redirect($PAGE->url, 'form cancelled', \core\output\notification::NOTIFY_WARNING);
        } elseif ($fromform = $feedback_form->get_data()) {
            // Handle form successful operation, if any
            $data = $feedback_form->get_data();
            $data->supervisorid = $USER->id;
            $data->type = 'topic';
            if (!$data->id) {
                $feedbackid = submit_feedback($data);
                if ($feedbackid) {
                    redirect($PAGE->url . '?action=view&id=' . $data->submissionid, 'feedback submitted successfully', \core\output\notification::NOTIFY_SUCCESS);
                } else {
                    redirect($PAGE->url, 'an error occured', \core\output\ntification::NOTIFY_ERROR);
                }
            } else {
                $feedbackid = update_feedback($data);
                if ($feedbackid) {
                    redirect($PAGE->url . '?action=view&id=' . $data->submissionid, 'feedback updated successfully', \core\output\notification::NOTIFY_SUCCESS);
                } else {
                    redirect($PAGE->url, 'an error occured', \core\output\ntification::NOTIFY_ERROR);
                }
            }
        }
        $topics = get_topic_by_groupid($USER->idnumber);
        foreach ($topics as $key => $topic) {
            $user = get_user_by_id($topic->studentid);
            $topics[$key]->studentname = $user->firstname . ' ' . $user->lastname;
        }
        $data = array(
            'issupervisor' => true,
            'topics' => array_values($topics),
        );

        echo $OUTPUT->render_from_template('local_esupervision/topic', $data);
    } elseif ($action) {
        if ($action == 'view' && $id) {
            $topic = get_topic_by_topicid($id);
            $user = get_user_by_id($topic->studentid);
            $topic->studentname = $user->firstname . ' ' . $user->lastname;
            $feedbacks = get_feedbacks_by_submissionid($topic->id);
            $data = array(
                'viewtopic' => true,
                'topic' => $topic,
                'feedbacks' => array_values($feedbacks)
            );
            echo $OUTPUT->render_from_template('local_esupervision/topic', $data);
            $feedback = new stdClass();
            $feedback->submissionid = $id;
            $feedback_form->set_data($feedback);
            $feedback_form->add_action_buttons(true, "Submit feedback");
            $feedback_form->display();

        } elseif ($action == 'approve') {
            $approve = approve_topic($id);
            if ($approve) {
                redirect($PAGE->url, 'topic approved successfully');

            } else {
                redirect($PAGE->url, 'an error occured');

            }
        } elseif ($action == 'reject') {
            $reject = reject_topic($id);
            if ($reject) {
                redirect($PAGE->url, 'topic rejected successfully');
            } else {
                redirect($PAGE->url, 'an error occured');

            }
        } elseif ($action == 'deletefeedback' && $id) {
            $feedback = get_feedback_by_id($id);
            $deleteId = delete_feedback($id, $feedback->type);
            if ($deleteId) {
                redirect($PAGE->url . '?action=view&id=' . $feedback->submissionid, 'feedback deleted successfully');

            } else {
                redirect($PAGE->url . '?action=view&id=' . $feedback->submissionid, 'an error occured');

            }
        } elseif ($action == 'editfeedback' && $id) {
            $feedback = get_feedback_by_id($id);
            $feedback_form->add_action_buttons(true, 'Update feedback');
            $feedback_form->set_data($feedback);
            $feedback_form->display();
        }
    }
}





echo $OUTPUT->footer();