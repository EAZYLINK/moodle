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
 * TODO describe file get_message
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__. '/../../../config.php');
require_login();

global $DB;
$messages = $DB->get_records('esupervision_chat_message');
if (empty($messages)) {
    header('Content-Type: application/json');
    echo json_encode(array());
    exit;
}
$chat_messages = array();
foreach ($messages as $message) {
    $chat_messages[] = array(
        'id' => $message->id,
        'sender' => $message->sender,
        'message' => $message->message,
        'timestamp' => $message->timestamp
    );
}
header('Content-Type: application/json');
echo json_encode($chat_messages);
