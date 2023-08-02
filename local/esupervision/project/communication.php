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



echo '<h2>Messages</h2>';
echo '<div class="messages">';
// Display the messages
$messages = get_messages();
foreach ($messages as $message) {
    echo '<div class="message">';
    echo '<div class="message-sender">' . $message->sender . '</div>';
    echo '<div class="message-content">' . $message->content . '</div>';
    echo '</div>';
}
echo '</div>';

// Allow composing a new message
echo '<form action="send_message.php" method="POST">';
echo '<textarea name="message_content"></textarea>';
echo '<input type="submit" value="Send">';
echo '</form>';

// Handle the message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageContent = $_POST['message_content'];

    // Process the message sending
    send_message($messageContent);
}
