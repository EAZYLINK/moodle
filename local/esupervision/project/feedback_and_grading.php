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
 * Communication and collaboration file.
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Display the feedback and grading form
echo '<form action="submit_feedback.php" method="POST">';
echo '<label for="feedback">Feedback:</label>';
echo '<textarea name="feedback"></textarea>';
echo '<label for="grade">Grade:</label>';
echo '<input type="number" name="grade">';
echo '<input type="submit" value="Submit">';
echo '</form>';

// Handle the feedback and grading submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = $_POST['feedback'];
    $grade = $_POST['grade'];

    // Process the feedback and grading
    process_feedback_and_grading($feedback, $grade);
}
