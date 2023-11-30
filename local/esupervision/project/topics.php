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

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../lib.php');

require_login();

// Set up the page context and layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('popup');
$PAGE->set_title('Topics');
$PAGE->set_url("/local/esupervision/project/topics.php");


$topic = get_topic_list();
$topic['topic'] = array_values($topic);
$context = context_user::instance($USER->id);
$allowpost = has_capability('local/esupervision:submittopic', $context);
$allowview = has_capability('local/esupervision:approvetopic', $context);
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);

$mform = new \local_esupervision\form\topic_form();
$update_topic_form = new \local_esupervision\form\updatetopicform();
$comment_form = new \local_esupervision\form\comment_form();

echo $OUTPUT->header();

if (!$action) {
    $home_url = new moodle_url('/local/esupervision/index.php');
} elseif ($action) {
    $home_url = new moodle_url('/local/esupervision/project/proposals.php');
}
$htmlstring = '<a href="' . $home_url . '" class="btn btn-primary mb-4">Back</a>';

echo $htmlstring;

if ($allowpost) {
    if (!$action) {
        $mform->display();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$action) {
        if ($mform->is_cancelled()) {
            redirect(
                new moodle_url('/local/esupervision/project/topics.php'),
                'form cancelled',
                null,
                \core\output\notification::NOTIFY_WARNING
            );

        } elseif ($fromform = $mform->get_data()) {
            // Handle form successful operation, if any
            $data = $mform->get_data();
            $data->studentid = $USER->id;
            $topic_id = submit_topic($data);
            if ($topic_id) {
                redirect(
                    new moodle_url('/local/esupervision/project/topics.php'),
                    'topic submitted',
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            } else {
                redirect(
                    new moodle_url('/local/esupervision/project/topics.php'),
                    'an error occured',
                    null,
                    \core\output\ntification::NOTIFY_ERROR
                );
            }

        }
        if ($update_topic_form->is_cancelled()) {
            redirect(
                new moodle_url('/local/esupervision/project/topics.php'),
                'form cancelled',
                null,
                \core\output\notification::NOTIFY_WARNING
            );

        } elseif ($fromform = $update_topic_form->get_data()) {
            // Handle form successful operation, if any
            $data = $update_topic_form->get_data();
            $topic_id = update_topic($data);
            if ($topic_id) {
                redirect(
                    new moodle_url('/local/esupervision/project/topics.php'),
                    'topic updated',
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            } else {
                redirect(
                    new moodle_url('/local/esupervision/project/topics.php'),
                    'an error occured',
                    null,
                    \core\output\ntification::NOTIFY_ERROR
                );
            }
        }

        if ($comment_form->is_cancelled()) {
            redirect(new moodle_url('/local/esupervision/project/topics.php?action=view&id=' . $id), 'form cancelled', \core\output\notification::NOTIFY_WARNING);
        } elseif ($fromform = $comment_form->get_data()) {
            // Handle form successful operation, if any
            $data = $comment_form->get_data();
            $data->submissionid = $id;
            $comment_id = submit_comment($data);
            if ($comment_id) {
                redirect(new moodle_url('/local/esupervision/project/topics.php'), 'comment submitted', \core\output\notification::NOTIFY_SUCCESS);
            } else {
                redirect(new moodle_url('/local/esupervision/project/topics.php'), 'an error occured', \core\output\ntification::NOTIFY_ERROR);
            }
        }

    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && !$action) {
        $topics = get_topic_by_studentid($USER->id);
        $data = array(
            'issupervisor' => false,
            'topics' => array_values($topics),
        );
        echo $OUTPUT->render_from_template('local_esupervision/topic', $data);
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && $action == 'edit' && $id) {
        $topic = get_topic_by_topicid($id);
        $toform = array(
            'topic' => $topic[$id]->topic,
            'description' => $topic[$id]->description,
            'id' => $topic[$id]->id,
        );
        $update_topic_form->set_data($toform);
        $update_topic_form->display();
    } elseif ($action == 'delete' && $id) {
        $deleteId = delete_topic($id);
        if ($deleteId) {
            redirect(new moodle_url('/local/esupervision/project/topics.php'), 'topic deleted successfully');

        } else {
            redirect(new moodle_url('/local/esupervision/project/topics.php'), 'an error occured');

        }
    }
}

if ($allowview) {
    if (!$action) {
        $topics = get_topic_list();
        foreach ($topics as $key => $topic) {
            $user = get_user_by_id($topic->studentid);
            $topics[$key]->studentname = $user->firstname . ' ' . $user->lastname;
        }
        $data = array(
            'issupervisor' => true,
            'topics' => array_values($topics),
        );

        echo $OUTPUT->render_from_template('local_esupervision/topic', $data);
    } else {
        $topic = get_topic_by_topicid($id);
        $user = get_user_by_id($topic[$id]->studentid);
        $topic[$id]->studentname = $user->firstname . ' ' . $user->lastname;
        $data = array(
            'viewtopic' => true,
            'topic' => array_values($topic),
        );
        echo $OUTPUT->render_from_template('local_esupervision/topic', $data);
        $comment_form->display();
        if ($action == 'approve') {
            $approve = approve_topic($id);
            if ($approve) {
                redirect(new moodle_url('/local/esupervision/project/topics.php'), 'topic approved successfully');

            } else {
                redirect(new moodle_url('/local/esupervision/project/topics.php'), 'an error occured');

            }
        } elseif ($action == 'reject') {
            $reject = reject_topic($id);
            if ($reject) {
                redirect(new moodle_url('/local/esupervision/project/topics.php'), 'topic rejected successfully');
            } else {
                redirect(new moodle_url('/local/esupervision/project/topics.php'), 'an error occured');

            }
        }
    }
}





echo $OUTPUT->footer();