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
 * project tracking file.
 *
 * @package    local_esupervision
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$milestones = get_project_milestones();

// Display the progress tracking
echo '<ul>';
foreach ($milestones as $milestone) {
    echo '<li>';
    echo '<span class="milestone-name">' . $milestone->name . '</span>';
    echo '<span class="milestone-status">' . $milestone->status . '</span>';
    echo '</li>';
}
echo '</ul>';

// Allow updating the progress status
echo '<form action="update_progress.php" method="POST">';
echo '<label for="milestone">Select Milestone:</label>';
echo '<select name="milestone">';
foreach ($milestones as $milestone) {
    echo '<option value="' . $milestone->id . '">' . $milestone->name . '</option>';
}
echo '</select>';
echo '<label for="status">Update Status:</label>';
echo '<input type="text" name="status">';
echo '<input type="submit" value="Update">';
echo '</form>';

// Handle the progress update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $milestoneId = $_POST['milestone'];
    $status = $_POST['status'];

    // Process the progress update
    update_progress_status($milestoneId, $status);
}
