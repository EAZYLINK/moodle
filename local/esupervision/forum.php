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
 * TODO describe file chat.php
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_login();
// require_once(__DIR__ . '/classes/form/forum_form.php');

$PAGE->set_url('/local/esupervision/forum.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add('Home', new moodle_url('/local/esupervision/index.php'));
$PAGE->navbar->add('Discussion Forum', new moodle_url('/local/esupervision/forum.php'));

$editoroptions = array(
    'maxfiles' => 1,
    'maxbytes' => 10485760,
    'context' => context_system::instance(),
    'trusttext' => false,
    'enable_filemanagement' => true,
    'noclean' => true,
    'enable_upload' => true,
    'editor_height' => 100,
    'editor_width' => '100%'
);

$forum_form = new \local_esupervision\form\forum_form(null, ['editoroptions' => $editoroptions]);

echo $OUTPUT->header();
$forum_form->display();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($data = $forum_form->get_data()) {
        $data->userid = $USER->id;
        ['format' => $format, 'text' => $message] = $data->message;
        $data->format = $format;
        $data->message = $message;
        $newPost = submit_forumpost($data);
        if ($newPost) {
            $posts = array_values($newPost);
            $OUTPUT->render_from_template('forum', $posts);
        }
    }
}
echo $OUTPUT->footer();