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
 * handles AJAX requests to send a message
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__. '/../../../config.php');
require_login();

// Get the message data from the AJAX request
    global $DB, $USER;
    $message = required_param('message', PARAM_TEXT);
    $data = new stdClass();
    $data->sender_id = $USER->id;
    $data->message = $message;
    $data->timestamp = time();
    $DB->insert_record('esupervision_chat__table', $data);

    // Return a success response (you can customize this as needed)
    echo json_encode(['status' => 'success']);