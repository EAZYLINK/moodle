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
 * @package    esupervision
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
require_login();

global $CFG, $DB, $PAGE, $USER;

$PAGE->set_context(context_system::instance());
$PAGE->set_title('Announcement');
$PAGE->set_url('/local/esupervision/create_announcement.php');
$PAGE->set_heading('Announcement');
$PAGE->set_pagelayout('standard');



echo $OUTPUT->header();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $announcement = new stdClass();
    $announcement->supervisor_name = fullname($USER);
    $announcement->title = $title;
    $announcement->content = $content;
    $announcementId = $DB->insert_record('esupervision_announcement', $announcement);
    if($announcementId) {
        \core\notification::add('Announcement created successfully!', \core\output\notification::NOTIFY_SUCCESS);
    } else {
        \core\notification::add('Failed to create announcement. Please try again.', \core\output\notification::NOTIFY_ERROR);
    }
}

echo $OUTPUT->render_from_template('local_esupervision/announcement', '');

echo $OUTPUT->footer();